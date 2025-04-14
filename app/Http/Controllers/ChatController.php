<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class ChatController extends Controller
{
    public function searchProducts(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:1000',
        ]);

        $userQuery = $request->input('query');
        Log::info('User query: ' . $userQuery);

        try {
            // Kiểm tra nếu là lời chào
            $lowerQuery = strtolower($userQuery);
            $greetings = ['xin chào', 'chào', 'hello', 'hi', 'alo'];
            $isGreeting = false;
            foreach ($greetings as $greeting) {
                if (strpos($lowerQuery, $greeting) !== false) {
                    $isGreeting = true;
                    break;
                }
            }

            if ($isGreeting && strlen(trim($userQuery)) <= 15) { // Đảm bảo tin nhắn ngắn, tránh nhầm với câu như "Xin chào áo thun"
                Log::info('Detected greeting message');
                return response()->json([
                    'products' => [],
                    'message' => 'Chào bạn! Hân hạnh được hỗ trợ. Bạn muốn mua gì hôm nay?'
                ]);
            }

            // Lấy danh sách sản phẩm
            $products = Product::select('id', 'name', 'description', 'price', 'image')->take(50)->get()->toArray();
            if (empty($products)) {
                Log::warning('Không có sản phẩm nào trong database.');
                return response()->json([
                    'products' => [],
                    'message' => 'Hiện tại cửa hàng không có sản phẩm nào. Bạn có thể quay lại sau nhé!'
                ]);
            }

            $productContext = json_encode($products, JSON_UNESCAPED_UNICODE);
            Log::info('Product context sent to Gemini: ' . $productContext);

            // Gọi Gemini để tìm kiếm sản phẩm
            $aiResponse = $this->getAIResponseFromGemini($userQuery, $productContext);
            Log::info('Raw AI response: ' . $aiResponse);

            // Loại bỏ ký tự markdown nếu có
            $aiResponse = trim($aiResponse, "```json\n```");
            $aiResponse = trim($aiResponse);
            Log::info('Cleaned AI response: ' . $aiResponse);

            // Phân tích phản hồi từ Gemini
            $matchedProducts = json_decode($aiResponse, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Lỗi phân tích JSON từ Gemini: ' . json_last_error_msg() . ' - Response: ' . $aiResponse);
                return response()->json([
                    'products' => [],
                    'message' => 'Mình không hiểu ý bạn lắm. Bạn có thể nói rõ hơn không?'
                ]);
            }

            $matchedProducts = is_array($matchedProducts) ? $matchedProducts : [];
            Log::info('Matched products: ', $matchedProducts);

            $results = Product::whereIn('id', array_column($matchedProducts, 'id'))->get();
            Log::info('Final results: ', $results->toArray());

            // Tạo thông điệp tự nhiên
            $naturalMessage = $this->generateNaturalMessage($userQuery, $results);

            return response()->json([
                'products' => $results,
                'message' => $naturalMessage
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi xử lý yêu cầu tìm kiếm sản phẩm: ' . $e->getMessage() . ' - Stack: ' . $e->getTraceAsString());
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
            Log::error('GEMINI_API_KEY hoặc GEMINI_API_URL không được cấu hình trong .env');
            throw new \Exception('Cấu hình API không hợp lệ.');
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
            throw new \Exception('Không thể kết nối với Gemini API: ' . $response->body());
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
        if (strpos($lowerQuery, 'áo thun') !== false) {
            $productType = 'áo thun';
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