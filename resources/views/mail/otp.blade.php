<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kode OTP Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
        }
        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
        }
        .otp {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            padding: 10px;
            border: 2px dashed #333;
            display: inline-block;
            margin: 10px 0;
        }
        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Kode OTP Anda</h2>
        <p>Gunakan kode berikut untuk masuk atau memverifikasi akun Anda:</p>
        <div class="otp">{{ $data['otp'] }}</div>
        <p>Kode ini hanya berlaku selama <strong>{{setting('site.otp_expired') ?? '20'}} menit</strong>.</p>
        <p>Jika Anda tidak meminta kode ini, abaikan email ini dan segera hubungi admin.</p>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Semua Hak Dilindungi.
        </div>
    </div>
</body>
</html>