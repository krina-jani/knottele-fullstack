<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your KNOTELLE Account</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #FDF7F4;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2E211E;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #FDF7F4;
            padding: 40px 15px;
            box-sizing: border-box;
        }
        .container {
            max-width: 540px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 24px;
            border: 1px solid #E7D1CC;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(145, 54, 56, 0.05);
        }
        .header {
            background-color: #FFF9F6;
            padding: 32px 24px 24px 24px;
            text-align: center;
            border-bottom: 1px solid #FCE9E5;
        }
        .brand-title {
            font-size: 24px;
            font-weight: 700;
            color: #913638;
            letter-spacing: 2px;
            margin: 10px 0 0 0;
            text-transform: uppercase;
        }
        .brand-subtitle {
            font-size: 11px;
            color: #786864;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .content {
            padding: 36px 32px;
            text-align: center;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #2E211E;
            margin-bottom: 12px;
        }
        .message {
            font-size: 14px;
            color: #61504C;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .otp-box {
            background-color: #FFF9F6;
            border: 2px dashed #E7D1CC;
            border-radius: 16px;
            padding: 24px;
            margin: 0 auto 28px auto;
            max-width: 320px;
        }
        .otp-code {
            font-size: 36px;
            font-weight: 800;
            color: #913638;
            letter-spacing: 12px;
            margin: 0;
            font-family: 'Courier New', Courier, monospace;
        }
        .expiry-note {
            font-size: 12px;
            color: #913638;
            font-weight: 600;
            margin-top: 10px;
        }
        .security-notice {
            background-color: #FDF4F2;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 12px;
            color: #786864;
            line-height: 1.5;
            text-align: left;
            margin-bottom: 24px;
        }
        .footer {
            background-color: #FFF9F6;
            padding: 24px;
            text-align: center;
            border-top: 1px solid #FCE9E5;
            font-size: 12px;
            color: #786864;
        }
        .footer a {
            color: #913638;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <div class="brand-title">KNOTELLE</div>
                <div class="brand-subtitle">Little Stitches, Big Happiness</div>
            </div>
            
            <div class="content">
                <div class="greeting">Hello {{ $name }},</div>
                <p class="message">
                    Welcome to the KNOTELLE Crochet community! Please verify your email address to activate your account and start exploring our handcrafted collection.
                </p>

                <div class="otp-box">
                    <div class="otp-code">{{ $otp }}</div>
                    <div class="expiry-note">⏳ This code expires in 10 minutes</div>
                </div>

                <div class="security-notice">
                    🔒 <strong>Security Warning:</strong> Never share this verification code with anyone. KNOTELLE team members will never ask for your verification OTP.
                </div>

                <p style="font-size: 12px; color: #786864; margin: 0;">
                    If you did not request this verification code, please ignore this email.
                </p>
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} <strong>KNOTELLE</strong>. All rights reserved.<br>
                Handmade with love in Bengaluru, India.
            </div>
        </div>
    </div>
</body>
</html>
