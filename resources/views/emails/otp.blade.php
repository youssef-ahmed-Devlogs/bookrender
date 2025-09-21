<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #f0f0f0;
        }

        .otp-container {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #1876f1;
            letter-spacing: 8px;
            margin: 20px 0;
            padding: 15px;
            background-color: white;
            border: 2px dashed #00bef5;
            border-radius: 8px;
            display: inline-block;
        }

        .warning {
            color: #dc3545;
            font-size: 14px;
            margin-top: 20px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Login Verification</h1>
    </div>

    <div class="content">
        <p>Hello {{$user->fname . ' ' . $user->lname}},</p>

        <p> You have requested to login to your account. Please use the following One-Time Password (OTP) to complete
            your login:</p>

        <div class="otp-container">
            <div class="otp-code">{{ $otp }}</div>
            <p>
                <strong>This code will expire in 10 minutes.</strong>
            </p>
        </div>

        <p>If you did not request this login, please ignore this email and ensure your account is secure.</p>

        <div class="warning">
            <p>
                <strong>Security Note:</strong> Never share this OTP with anyone. Our team will never ask for your OTP
                over phone or email.
            </p>
        </div>
    </div>

    <div class="footer">
        <p>This is an automated message, please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>

</html>