<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Mã OTP Đặt Lại Mật Khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            text-align: center;
            margin: 20px 0;
            letter-spacing: 5px;
        }
        .expiry {
            color: #6c757d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Đặt Lại Mật Khẩu</h2>
        
        <p>Xin chào,</p>
        
        <p>Bạn đã yêu cầu đặt lại mật khẩu. Sử dụng mã OTP dưới đây để xác nhận:</p>
        
        <div class="otp-code">
            {{ $otp }}
        </div>
        
        <p class="expiry">Mã OTP này có hiệu lực trong vòng 15 phút.</p>
        
        <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
        
        <p>Trân trọng,<br>Đội ngũ Hỗ Trợ</p>
    </div>
</body>
</html>