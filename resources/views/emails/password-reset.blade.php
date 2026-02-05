<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Sıfırlama</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 24px;
        }
        .content {
            margin-bottom: 30px;
        }
        .greeting {
            font-size: 24px;
            font-weight: bold;
            color: #0f766e;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 15px;
            color: #4b5563;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            transition: transform 0.2s;
        }
        .reset-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-text {
            color: #92400e;
            font-size: 14px;
            margin: 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .small-text {
            font-size: 12px;
            color: #9ca3af;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">
                🔐
            </div>
            <h1 style="color: #0f766e; margin: 0;">{{ config('app.name', 'Üyelik Sistemi') }}</h1>
        </div>

        <div class="content">
            <div class="greeting">Merhaba!</div>

            <p class="message">
                Hesabınız için bir <strong>şifre sıfırlama talebi</strong> aldık.
                Yeni şifrenizi oluşturmak için aşağıdaki butona tıklayın.
            </p>

            <div class="button-container">
                <a href="{{ $url }}" class="reset-button">
                    🔑 Şifreyi Sıfırla
                </a>
            </div>

            <div class="warning">
                <p class="warning-text">
                    <strong>⏰ Önemli:</strong> Bu şifre sıfırlama bağlantısının geçerlilik süresi <strong>60 dakika</strong>dır.
                </p>
            </div>

            <p class="message">
                Eğer bu talebi <strong>siz yapmadıysanız</strong>, herhangi bir işlem yapmanıza gerek yoktur.
                Hesabınız güvende kalacaktır.
            </p>

            <div style="background-color: #f3f4f6; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <p class="small-text" style="margin: 0;">
                    <strong>Güvenlik İpucu:</strong> Güçlü bir şifre seçin (en az 8 karakter, büyük-küçük harf, sayı ve özel karakter içeren).
                </p>
            </div>
        </div>

        <div class="footer">
            <p>Saygılarımızla,<br><strong>{{ config('app.name', 'Üyelik Sistemi') }} Ekibi</strong></p>
            <p class="small-text">
                Bu e-posta otomatik olarak gönderilmiştir. Lütfen yanıtlamayın.<br>
                © {{ date('Y') }} {{ config('app.name', 'Üyelik Sistemi') }}. Tüm hakları saklıdır.
            </p>
        </div>
    </div>
</body>
</html>
