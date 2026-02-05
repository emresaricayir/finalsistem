<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Sıfırlama - {{ $settings['organization_name'] }}</title>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            padding: 32px 24px;
            text-align: center;
            color: white;
        }
        .content {
            padding: 32px 24px;
        }
        .title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
            text-align: center;
        }
        .subtitle {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 24px;
            text-align: center;
        }
        .subtitle-de {
            font-size: 14px;
            color: #9ca3af;
            margin-bottom: 24px;
            text-align: center;
            font-style: italic;
        }
        .info-box {
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            margin: 24px 0;
        }
        .info-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            color: white;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 24px 0;
            transition: all 0.2s;
        }
        .reset-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
        }
        .footer {
            background-color: #f9fafb;
            padding: 24px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer-text {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 8px;
        }
        .footer-link {
            color: #0d9488;
            text-decoration: none;
        }
        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 16px;
            margin: 24px 0;
        }
        .warning-title {
            font-weight: 600;
            color: #92400e;
            margin-bottom: 8px;
        }
        .warning-text {
            color: #92400e;
            font-size: 14px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
            .content {
                padding: 24px 16px;
            }
            .header {
                padding: 24px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 style="margin: 0; font-size: 20px; font-weight: 600;">{{ $settings['organization_name'] }}</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h2 class="title">🔐 Şifre Sıfırlama Talebi</h2>
            <h3 class="title" style="font-size: 18px; color: #6b7280; font-style: italic;">Passwort Zurücksetzen</h3>

            <p class="subtitle">Merhaba {{ $member->name }} {{ $member->surname }},</p>
            <p class="subtitle-de">Hallo {{ $member->name }} {{ $member->surname }},</p>

            <div style="margin: 24px 0; padding: 16px; background-color: #f8fafc; border-left: 4px solid #0d9488; border-radius: 4px;">
                <p style="margin: 0 0 8px 0; color: #374151;">Hesabınız için şifre sıfırlama talebinde bulundunuz. Aşağıdaki butona tıklayarak yeni şifrenizi belirleyebilirsiniz.</p>
                <p style="margin: 0; color: #6b7280; font-size: 14px; font-style: italic;">Sie haben eine Passwort-Zurücksetzung für Ihr Konto angefordert. Klicken Sie auf die Schaltfläche unten, um Ihr neues Passwort festzulegen.</p>
            </div>

            <div class="info-box">
                <div class="info-title">📧 E-posta Adresi:</div>
                <div style="margin-bottom: 8px; font-weight: 600;">{{ $member->email }}</div>
                <div style="font-size: 12px; color: #9ca3af; font-style: italic;">E-Mail-Adresse: {{ $member->email }}</div>
            </div>

            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ $resetUrl }}" class="reset-button" style="display: inline-block; margin-bottom: 8px;">
                    🔑 Şifremi Sıfırla
                </a>
                <div style="font-size: 12px; color: #6b7280; font-style: italic;">Passwort Zurücksetzen</div>
            </div>

            <div class="warning">
                <div class="warning-title">⚠️ Önemli Bilgiler:</div>
                <div class="warning-text" style="margin-bottom: 16px;">
                    • Bu bağlantı 24 saat geçerlidir<br>
                    • Bağlantıyı sadece bir kez kullanabilirsiniz<br>
                    • Eğer bu talebi siz yapmadıysanız, bu e-postayı görmezden gelebilirsiniz<br>
                    • Güvenliğiniz için şifrenizi kimseyle paylaşmayın
                </div>
                <div class="warning-title" style="font-size: 14px; color: #9ca3af; font-style: italic;">Wichtige Informationen:</div>
                <div class="warning-text" style="font-size: 12px; color: #9ca3af; font-style: italic;">
                    • Dieser Link ist 24 Stunden gültig<br>
                    • Sie können den Link nur einmal verwenden<br>
                    • Falls Sie diese Anfrage nicht gestellt haben, können Sie diese E-Mail ignorieren<br>
                    • Teilen Sie Ihr Passwort zu Ihrer Sicherheit mit niemandem
                </div>
            </div>

            <div style="margin-top: 24px; padding: 16px; background-color: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb;">
                <p style="margin: 0 0 8px 0; font-size: 14px; color: #374151;">
                    Eğer yukarıdaki buton çalışmıyorsa, aşağıdaki bağlantıyı kopyalayıp tarayıcınıza yapıştırabilirsiniz:
                </p>
                <p style="margin: 0 0 12px 0; font-size: 12px; color: #9ca3af; font-style: italic;">
                    Falls die obige Schaltfläche nicht funktioniert, können Sie den folgenden Link kopieren und in Ihren Browser einfügen:
                </p>
                <p style="word-break: break-all; font-size: 11px; color: #6b7280; background-color: #ffffff; padding: 8px; border-radius: 4px; border: 1px solid #d1d5db; margin: 0;">
                    {{ $resetUrl }}
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                <strong>{{ $settings['organization_name'] }}</strong><br>
                @if($settings['organization_address'])
                    {{ $settings['organization_address'] }}<br>
                @endif
                @if($settings['organization_phone'])
                    📞 {{ $settings['organization_phone'] }}<br>
                @endif
                @if($settings['organization_email'])
                    ✉️ {{ $settings['organization_email'] }}
                @endif
            </div>
            <div style="margin-top: 16px; font-size: 12px; color: #9ca3af;">
                Bu e-posta otomatik olarak gönderilmiştir. Lütfen yanıtlamayın.<br>
                Diese E-Mail wurde automatisch gesendet. Bitte antworten Sie nicht.
            </div>
        </div>
    </div>
</body>
</html>
