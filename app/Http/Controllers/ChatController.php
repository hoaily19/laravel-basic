<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Product;

class ChatController extends Controller
{
    public function searchProducts(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:1000',
        ]);

        $userQuery = strip_tags($request->input('query')); 
        Log::info('User query: ' . $userQuery);

        try {
            $lowerQuery = strtolower($userQuery);
            $greetings = ['xin chào', 'chào', 'hello', 'hi', 'alo'];
            $isGreeting = false;
            foreach ($greetings as $greeting) {
                if (strpos($lowerQuery, $greeting) !== false) {
                    $isGreeting = true;
                    break;
                }
            }

            if ($isGreeting && strlen(trim($userQuery)) <= 15) {
                Log::info('Detected greeting message');
                Session::forget('last_product_id'); 
                return response()->json([
                    'products' => [],
                    'message' => 'Chào bạn! Hân hạnh được hỗ trợ. Bạn muốn mua gì hôm nay?'
                ]);
            }

            $isVariationQuery = preg_match('/(kích cỡ|size|màu sắc|color|cỡ|màu|có màu gì|có kích cỡ gì|có size gì)/i', $lowerQuery);

            if ($isVariationQuery) {
                $isContextual = preg_match('/(này|cái này|sản phẩm này|áo này|có những|có màu gì|có kích cỡ gì|có size gì)/i', $lowerQuery) || 
                                Session::has('last_product_id');

                $product = null;
                if ($isContextual && Session::has('last_product_id')) {
                    $product = Product::with(['variations.size', 'variations.color'])
                        ->select('id', 'name')
                        ->find(Session::get('last_product_id'));
                    Log::info('Using last product ID: ' . Session::get('last_product_id'));
                }

                if (!$product) {
                    $searchQuery = preg_replace('/(kích cỡ|size|màu sắc|color|cỡ|màu|có những|này|cái này|sản phẩm này|áo này|có màu gì|có kích cỡ gì|có size gì)/i', '', $lowerQuery);
                    $searchQuery = trim($searchQuery);
                    Log::info('Searching for product with query: ' . $searchQuery);

                    $products = Product::with(['variations.size', 'variations.color'])
                        ->select('id', 'name')
                        ->where('name', 'LIKE', "%{$searchQuery}%")
                        ->orWhere('description', 'LIKE', "%{$searchQuery}%")
                        ->take(1)
                        ->get();

                    $product = $products->isNotEmpty() ? $products->first() : null;
                }

                if (!$product) {
                    Log::info('No product found for variation query: ' . $userQuery);
                    $suggestions = Product::select('name')
                        ->where('name', 'LIKE', '%áo%')
                        ->take(3)
                        ->pluck('name')
                        ->toArray();
                    $suggestionText = $suggestions ? " Bạn có muốn thử: " . implode(', ', $suggestions) . "?" : "";
                    return response()->json([
                        'sizes' => [],
                        'colors' => [],
                        'message' => "Mình không tìm thấy sản phẩm nào liên quan đến '$userQuery'.$suggestionText"
                    ]);
                }

                Session::put('last_product_id', $product->id);
                Log::info('Set last product ID: ' . $product->id);

                $sizes = $product->variations
                    ->map(function ($variation) {
                        return $variation->size ? $variation->size->name : null;
                    })
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();
                $colors = $product->variations
                    ->map(function ($variation) {
                        return $variation->color ? $variation->color->name : null;
                    })
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();

                Log::info('Variations for product ID ' . $product->id . ': sizes=' . json_encode($sizes) . ', colors=' . json_encode($colors));

                if (empty($sizes) && empty($colors)) {
                    Log::info('No variations found for product ID: ' . $product->id);
                    return response()->json([
                        'sizes' => [],
                        'colors' => [],
                        'message' => "Sản phẩm '{$product->name}' hiện chưa có thông tin về kích cỡ hoặc màu sắc."
                    ]);
                }

                $messageParts = [];
                if ($sizes) {
                    $messageParts[] = "kích cỡ: " . implode(', ', $sizes);
                }
                if ($colors) {
                    $messageParts[] = "màu sắc: " . implode(', ', $colors);
                }
                $naturalMessage = "Sản phẩm '{$product->name}' có " . implode(' và ', $messageParts) . ".";

                return response()->json([
                    'sizes' => $sizes,
                    'colors' => $colors,
                    'message' => $naturalMessage
                ]);
            }

            $products = Product::select('id', 'name', 'description', 'price', 'image')
                ->where('is_active', true)
                ->take(50)
                ->get();

            if ($products->isEmpty()) {
                Log::warning('No active products in database.');
                return response()->json([
                    'products' => [],
                    'message' => 'Hiện tại cửa hàng không có sản phẩm nào. Bạn có thể quay lại sau nhé!'
                ]);
            }

            $productContext = json_encode($products->toArray(), JSON_UNESCAPED_UNICODE);
            Log::info('Product context sent to Gemini: ' . $productContext);

            $aiResponse = $this->getAIResponseFromGemini($userQuery, $productContext);
            Log::info('Raw AI response: ' . $aiResponse);

            $aiResponse = trim($aiResponse, "```json\n```");
            $aiResponse = trim($aiResponse);
            Log::info('Cleaned AI response: ' . $aiResponse);

            $matchedProducts = json_decode($aiResponse, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON parse error from Gemini: ' . json_last_error_msg() . ' - Response: ' . $aiResponse);
                return response()->json([
                    'products' => [],
                    'message' => 'Mình không hiểu ý bạn lắm. Bạn có thể nói rõ hơn không?'
                ]);
            }

            $matchedProducts = is_array($matchedProducts) ? $matchedProducts : [];
            Log::info('Matched products: ', $matchedProducts);

            $results = Product::whereIn('id', array_unique(array_column($matchedProducts, 'id')))
                ->with(['variations.size', 'variations.color'])
                ->get();

            if ($results->isNotEmpty()) {
                Session::put('last_product_id', $results->first()->id);
                Log::info('Set last product ID after search: ' . $results->first()->id);
            }

            $naturalMessage = $this->generateNaturalMessage($userQuery, $results);

            return response()->json([
                'products' => $results,
                'message' => $naturalMessage
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing search request: ' . $e->getMessage() . ' - Stack: ' . $e->getTraceAsString());
            return response()->json([
                'products' => [],
                'message' => 'Có lỗi xảy ra rồi. Bạn thử lại nhé!'
            ], 500);
        }
    }

    private function getAIResponseFromGemini($userQuery, $productContext)
    {
        $apiKey = env('GEMINI_API_KEY');
        $apiUrl = env('GEMINI_API_URL');

        if (empty($apiKey) || empty($apiUrl)) {
            Log::error('GEMINI_API_KEY or GEMINI_API_URL not configured in .env');
            throw new \Exception('Invalid API configuration.');
        }

        $prompt = "Bạn là trợ lý của HoaiLy Shop, chuyên bán thời trang. Dựa trên truy vấn: '$userQuery', tìm sản phẩm phù hợp từ danh sách sau: $productContext. Trả về **chỉ** danh sách sản phẩm dưới dạng JSON: [{id, name, price, image}]. Nếu không tìm thấy, trả về mảng rỗng []. Không thêm văn bản giải thích, không dùng markdown.";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($apiUrl . '?key=' . $apiKey, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);

        if ($response->failed()) {
            Log::error('Gemini API failed: ' . $response->body());
            throw new \Exception('Failed to connect to Gemini API: ' . $response->body());
        }

        $result = $response->json();
        Log::info('Full Gemini API response: ', $result);

        $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
        return trim($text);
    }

    private function generateNaturalMessage($userQuery, $results)
    {
        $lowerQuery = strtolower($userQuery);
        
        $productType = 'sản phẩm';
        if (strpos($lowerQuery, 'áo thun') !== false || strpos($lowerQuery, 'áo giữ nhiệt') !== false) {
            $productType = 'áo';
        } elseif (strpos($lowerQuery, 'giày') !== false) {
            $productType = 'giày';
        } elseif (strpos($lowerQuery, 'quần') !== false) {
            $productType = 'quần';
        }

        if ($results->isEmpty()) {
            return "Chào bạn! Mình không tìm thấy $productType nào phù hợp với yêu cầu '$userQuery'. Bạn có muốn thử từ khóa khác không?";
        }

        $count = $results->count();
        $plural = $count > 1 ? 'các' : 'một';
        $message = "Chào bạn! Mình tìm thấy $count $plural $productType phù hợp đây!";

        return $message;
    }
}