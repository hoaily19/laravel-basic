<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Mail\PaymentConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    private function generateVnpayUrl($order, $amount)
    {
        $vnp_TmnCode = env('VNPAY_TMN_CODE');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $vnp_Url = env('VNPAY_URL');
        $vnp_Returnurl = route('vnpay.callback');

        $vnp_TxnRef = $order->id;
        $vnp_OrderInfo = "Thanh toán đơn hàng #$vnp_TxnRef";
        $vnp_Amount = $amount * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "250000",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        ksort($inputData);
        $query = http_build_query($inputData);
        $hashdata = $query . "&vnp_SecureHash=" . hash_hmac('sha512', $query, $vnp_HashSecret);

        return $vnp_Url . "?" . $hashdata;
    }

    // Tạo URL thanh toán MoMo
    private function generateMomoUrl($order, $amount)
    {
        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey = env('MOMO_ACCESS_KEY');
        $secretKey = env('MOMO_SECRET_KEY');
        $endpoint = env('MOMO_URL', 'https://test-payment.momo.vn/v2/gateway/api/create');
        $returnUrl = route('momo.callback');

        if (empty($partnerCode)) {
            Log::error('MOMO_PARTNER_CODE is not set in .env');
            abort(500, 'Cấu hình MoMo không hợp lệ: partnerCode bị trống');
        }

        Log::info('MoMo partnerCode: ' . $partnerCode);

        $randomNumber = rand(10000, 99999);
        $orderId = $order->id . 'MOMOPAY' . $randomNumber;
        $orderInfo = "Thanh toán đơn hàng #$order->id";
        $requestId = $partnerCode . time();
        $requestType = "payWithATM";
        $extraData = "";

        $rawSignature = "accessKey=" . $accessKey .
            "&amount=" . $amount .
            "&extraData=" . $extraData .
            "&ipnUrl=" . $returnUrl .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&partnerCode=" . $partnerCode .
            "&redirectUrl=" . $returnUrl .
            "&requestId=" . $requestId .
            "&requestType=" . $requestType;

        $signature = hash_hmac("sha256", $rawSignature, $secretKey);

        $data = [
            "partnerCode" => $partnerCode,
            "accessKey" => $accessKey,
            "requestId" => $requestId,
            "amount" => $amount,
            "orderId" => $orderId,
            "orderInfo" => $orderInfo,
            "redirectUrl" => $returnUrl,
            "ipnUrl" => $returnUrl,
            "extraData" => $extraData,
            "requestType" => $requestType,
            "signature" => $signature,
            "lang" => "vi"
        ];

        $response = $this->execPostRequest($endpoint, json_encode($data));
        $result = json_decode($response, true);

        if (!empty($result['payUrl'])) {
            return $result['payUrl'];
        } else {
            Log::error('MoMo payment URL creation failed', ['response' => $result]);
            abort(500, 'Không thể tạo URL thanh toán MoMo');
        }
    }

    // Gửi POST request cho MoMo
    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    // Callback cho VNPay
    public function vnpayCallback(Request $request)
    {
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $inputData = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'];

        unset($inputData['vnp_SecureHashType']);
        unset($inputData['vnp_SecureHash']);

        ksort($inputData);

        $hashData = '';
        foreach ($inputData as $key => $value) {
            $hashData .= ($hashData ? '&' : '') . urlencode($key) . "=" . urlencode($value);
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash) {
            $order = Orders::where('id', $inputData['vnp_TxnRef'])->first();

            if ($order) {
                $responseCode = $inputData['vnp_ResponseCode'];

                if ($responseCode === "00") {
                    $order->status = 'paid';
                    $order->save();

                    $orderItems = $order->orderItems;
                    foreach ($orderItems as $orderItem) {
                        if ($orderItem->product_variations_id) {
                            $variant = $orderItem->variant;
                            if ($variant) {
                                $variant->stock -= $orderItem->quantity; 
                                $variant->save();
                            }
                        } else {
                            $product = $orderItem->product;
                            if ($product) {
                                $product->stock -= $orderItem->quantity;
                                $product->save();
                            }
                        }
                    }

                    $user = $order->user;
                    if ($user && !empty($user->email)) {
                        $order->email = $user->email;
                        Mail::to($order->email)->send(new PaymentConfirmation($order));
                    }

                    return redirect()->route('waring.success');
                } else {
                    $order->status = ($responseCode === "24") ? 'canceled' : 'fail';
                    $order->save();
                    return redirect()->route('waring.fail');
                }
            } else {
                return redirect()->route('waring.fail');
            }
        } else {
            return redirect()->route('waring.fail');
        }
    }

    // Callback cho MoMo
    public function momoCallback(Request $request)
    {
        $data = $request->all();
        $secretKey = env('MOMO_SECRET_KEY');
        $accessKey = env('MOMO_ACCESS_KEY');

        Log::info('MoMo Callback Data: ', $data);

        if (!isset($data['orderId']) || !isset($data['resultCode'])) {
            Log::error('Invalid MoMo callback data', $data);
            return redirect()->route('waring.fail')->with('error', 'Dữ liệu callback không hợp lệ');
        }

        $orderIdParts = explode('MOMOPAY', $data['orderId']);
        $originalOrderId = $orderIdParts[0];

        $amount = $data['amount'];
        $extraData = $data['extraData'] ?? '';
        $message = $data['message'] ?? '';
        $orderInfo = $data['orderInfo'] ?? '';
        $orderType = $data['orderType'] ?? '';
        $partnerCode = $data['partnerCode'] ?? '';
        $payType = $data['payType'] ?? '';
        $requestId = $data['requestId'] ?? '';
        $responseTime = $data['responseTime'] ?? '';
        $resultCode = $data['resultCode'] ?? '';
        $transId = $data['transId'] ?? '';

        $rawSignature = "accessKey=" . $accessKey .
            "&amount=" . $amount .
            "&extraData=" . $extraData .
            "&message=" . $message .
            "&orderId=" . $data['orderId'] .
            "&orderInfo=" . $orderInfo .
            "&orderType=" . $orderType .
            "&partnerCode=" . $partnerCode .
            "&payType=" . $payType .
            "&requestId=" . $requestId .
            "&responseTime=" . $responseTime .
            "&resultCode=" . $resultCode .
            "&transId=" . $transId;

        $calculatedSignature = hash_hmac('sha256', $rawSignature, $secretKey);

        if ($calculatedSignature === $data['signature']) {
            $order = Orders::where('id', $originalOrderId)->first();

            if ($order) {
                if ($resultCode == '0') {
                    $order->status = 'paid';
                    $order->save();

                    $orderItems = $order->orderItems;
                    foreach ($orderItems as $orderItem) {
                        if ($orderItem->product_variations_id) {
                            $variant = $orderItem->variant;
                            if ($variant) {
                                $variant->stock -= $orderItem->quantity; 
                                $variant->save();
                            }
                        } else {
                            $product = $orderItem->product;
                            if ($product) {
                                $product->quantity -= $orderItem->quantity;
                                $product->save();
                            }
                        }
                    }

                    $user = $order->user;
                    if ($user && !empty($user->email)) {
                        $order->email = $user->email;
                        Mail::to($order->email)->send(new PaymentConfirmation($order));
                    }

                    return redirect()->route('waring.success');
                } else {
                    Log::error('MoMo payment failed', ['orderId' => $originalOrderId, 'resultCode' => $resultCode]);
                    $order->status = 'fail';
                    $order->save();
                    return redirect()->route('waring.fail')->with('error', 'Thanh toán thất bại');
                }
            } else {
                Log::error('Order not found', ['orderId' => $originalOrderId]);
                return redirect()->route('waring.fail')->with('error', 'Không tìm thấy đơn hàng');
            }
        } else {
            Log::error('Invalid MoMo signature', [
                'calculated' => $calculatedSignature,
                'received' => $data['signature'],
                'rawSignature' => $rawSignature
            ]);
            return redirect()->route('waring.fail')->with('error', 'Chữ ký không hợp lệ');
        }
    }

    // IPN cho MoMo
    public function momoIpn(Request $request)
    {
        $secretKey = env('MOMO_SECRET_KEY');
        $accessKey = env('MOMO_ACCESS_KEY');

        $data = $request->all();
        $rawSignature = "accessKey=" . $accessKey .
            "&amount=" . $data['amount'] .
            "&extraData=" . ($data['extraData'] ?? '') .
            "&message=" . $data['message'] .
            "&orderId=" . $data['orderId'] .
            "&orderInfo=" . $data['orderInfo'] .
            "&orderType=" . $data['orderType'] .
            "&partnerCode=" . $data['partnerCode'] .
            "&payType=" . $data['payType'] .
            "&requestId=" . $data['requestId'] .
            "&responseTime=" . $data['responseTime'] .
            "&resultCode=" . $data['resultCode'] .
            "&transId=" . $data['transId'];

        $calculatedSignature = hash_hmac('sha256', $rawSignature, $secretKey);

        if ($calculatedSignature === $data['signature']) {
            $order = Orders::where('id', $data['orderId'])->first();
            if ($order && $data['resultCode'] == 0) {
                $order->status = 'paid';
                $order->save();
            }
        }
    }

    // Phương thức xử lý thanh toán chung
    public function processPayment($order, $paymentMethod, $totalPrice)
    {
        switch ($paymentMethod) {
            case 'cod':
                $orderItems = $order->orderItems;
                foreach ($orderItems as $orderItem) {
                    if ($orderItem->product_variations_id) {
                        $variant = $orderItem->variant;
                        if ($variant) {
                            $variant->stock -= $orderItem->quantity; // Use 'stock'
                            $variant->save();
                        }
                    } else {
                        $product = $orderItem->product;
                        if ($product) {
                            $product->stock -= $orderItem->quantity;
                            $product->save();
                        }
                    }
                }
                Mail::to($order->user->email)->send(new PaymentConfirmation($order));
                return redirect()->route('waring.success', $order->id)->with('success', 'Thêm đơn hàng thành công!');
                break;
            case 'vnpay':
                $vnpayUrl = $this->generateVnpayUrl($order, $totalPrice);
                return redirect()->away($vnpayUrl);
                break;
            case 'momo':
                $momoUrl = $this->generateMomoUrl($order, $totalPrice);
                return redirect()->away($momoUrl);
                break;
            default:
                throw new \Exception('Phương thức thanh toán không hợp lệ');
        }
    }
}