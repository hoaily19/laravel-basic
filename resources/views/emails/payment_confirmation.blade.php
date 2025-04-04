<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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
            background-color: #4CAF50;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }

        .content h2 {
            font-size: 20px;
            color: #4CAF50;
        }

        .order-details table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f9f9f9;
            margin-top: 15px;
            border-radius: 5px;
        }

        .order-details th,
        .order-details td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .order-details th {
            background-color: #f1f1f1;
            color: #333;
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #777777;
            background-color: #f4f4f4;
        }

        .button {
            display: block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px auto 0 auto;
            width: 50%;
            text-align: center;
        }

        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Xác Nhận Đơn Hàng</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Chào {{ $order->user->name }},</h2>
            <p>Cảm ơn bạn đã đặt hàng tại Kicap! Chúng tôi xin xác nhận rằng đơn hàng của bạn đã được ghi nhận thành
                công và đang được xử lý.</p>

            <div class="order-details">
                <table>
                    <tr>
                        <th>Số đơn hàng</th>
                        <td>#{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <th>Ngày đặt</th>
                        <td>{{ $order->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>{{ $order->status }}</td>
                    </tr>
                </table>
            </div>

            <p>Chúng tôi sẽ thông báo thêm khi đơn hàng của bạn được giao. Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ
                qua <a href="mailto:support@yourcompany.com">support@kicap.com</a>.</p>

            <a href="#" class="button" style="text-decoration: none; color:#ffffff">Xem chi tiết đơn hàng</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© 2025 Kicap.vn All rights reserved.</p>
        </div>
    </div>
</body>

</html>