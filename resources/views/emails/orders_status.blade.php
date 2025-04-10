<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cập nhật trạng thái đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #ff6200;
            padding: 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            line-height: 1.6;
            margin: 10px 0;
        }
        .order-details {
            background-color: #fff8f0;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .order-details ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .order-details li {
            padding: 8px 0;
            border-bottom: 1px solid #ffe6cc;
        }
        .order-details li:last-child {
            border-bottom: none;
        }
        .order-details strong {
            color: #ff6200;
        }
        .footer {
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
        }
        .footer a {
            color: #ff6200;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Cập nhật trạng thái đơn hàng #{{ $order->id }}</h1>
        </div>
        <div class="content">
            <p>Xin chào <strong>{{ $order->user->name }}</strong>,</p>
            <p>Chúng tôi xin thông báo rằng trạng thái đơn hàng của bạn đã được cập nhật. Dưới đây là chi tiết:</p>

            <div class="order-details">
                <ul>
                    <li><strong>Mã đơn hàng:</strong> {{ $order->id }}</li>
                    <li><strong>Trạng thái mới:</strong> {{ ucfirst($order->status) }}</li>
                    <li><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</li>
                    <li><strong>Tổng tiền:</strong> {{ number_format($order->total_price) }} VNĐ</li>
                </ul>
            </div>

            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email hoặc số điện thoại hỗ trợ.</p>
        </div>
        <div class="footer">
            <p>Trân trọng,<br>Đội ngũ cửa hàng</p>
            <p><a href="mailto:support@hoaily.click">support@hoaily.click</a> | <a href="tel:+123456789">+84 827-313-755</a></p>
        </div>
    </div>
</body>
</html>