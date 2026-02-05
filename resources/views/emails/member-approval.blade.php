<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üyeliğiniz Onaylandı / Mitgliedschaft Genehmigt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #16a34a;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .header-subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            font-style: italic;
            margin: 5px 0 0 0;
        }
        .content {
            padding: 20px;
        }
        .info-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        .info-box h3 {
            margin-top: 0;
            color: #16a34a;
            font-size: 16px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            padding: 5px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-label {
            font-weight: bold;
            color: #374151;
        }
        .info-value {
            color: #6b7280;
        }
        .login-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        .login-box h3 {
            margin-top: 0;
            color: #1d4ed8;
            font-size: 16px;
        }
        .login-button {
            display: inline-block;
            background-color: #1d4ed8;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 12px;
        }
        .greeting-tr {
            font-size: 16px;
            color: #374151;
            margin-bottom: 8px;
        }
        .greeting-de {
            font-size: 14px;
            color: #6b7280;
            font-style: italic;
            margin-bottom: 16px;
        }
        .text-tr {
            color: #374151;
            margin-bottom: 8px;
        }
        .text-de {
            color: #6b7280;
            font-size: 14px;
            font-style: italic;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Üyeliğiniz Onaylandı</h1>
            <p class="header-subtitle">Mitgliedschaft Genehmigt</p>
            <p style="margin: 10px 0 0 0; font-size: 16px; font-weight: 600;">{{ $organizationName }}</p>
        </div>

        <div class="content">
            <p class="greeting-tr"><strong>Değerli {{ $member->name }} {{ $member->surname }},</strong></p>
            <p class="greeting-de"><strong>Sehr geehrte/r {{ $member->name }} {{ $member->surname }},</strong></p>

            <p class="text-tr">Tebrikler! Üyelik başvurunuz onaylanmıştır.</p>
            <p class="text-de">Herzlichen Glückwunsch! Ihre Mitgliedschaft wurde genehmigt.</p>

            <p class="text-tr">Artık cemaatimizin resmi bir üyesisiniz.</p>
            <p class="text-de">Sie sind nun offiziell Mitglied unserer Gemeinde.</p>

            <div class="info-box">
                <h3>📋 Üyelik Bilgileri</h3>
                <p style="font-size: 12px; color: #6b7280; font-style: italic; margin: -5px 0 10px 0;">Mitgliedsinformationen</p>
                <div class="info-row">
                    <span class="info-label">Üye No:</span>
                    <span class="info-value">{{ $member->member_no }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ad Soyad:</span>
                    <span class="info-value">{{ $member->name }} {{ $member->surname }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">E-posta:</span>
                    <span class="info-value">{{ $member->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Telefon:</span>
                    <span class="info-value">{{ $member->phone }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kayıt Tarihi:</span>
                    <span class="info-value">{{ $member->created_at }}</span>
                </div>
            </div>

            <div class="login-box">
                <h3>🔐 Sisteme Giriş</h3>
                <p style="font-size: 12px; color: #6b7280; font-style: italic; margin: -5px 0 15px 0;">System Login</p>

                <p class="text-tr">Aşağıdaki bilgilerle üye sistemine giriş yapabilirsiniz:</p>
                <p class="text-de">Sie können sich mit folgenden Daten in das Mitgliedersystem einloggen:</p>

                <div style="background-color: #f8fafc; padding: 12px; border-radius: 4px; margin: 15px 0;">
                    <p style="margin: 0 0 8px 0;"><strong>E-posta:</strong> {{ $member->email }}</p>
                    <p style="margin: 0; font-size: 12px; color: #6b7280; font-style: italic;">E-Mail: {{ $member->email }}</p>
                </div>

                <div style="background-color: #f8fafc; padding: 12px; border-radius: 4px; margin: 15px 0;">
                    <p style="margin: 0 0 8px 0;"><strong>Şifre:</strong> Kayıt sırasında belirlediğiniz şifre</p>
                    <p style="margin: 0; font-size: 12px; color: #6b7280; font-style: italic;">Passwort: Das von Ihnen bei der Registrierung festgelegte Passwort</p>
                </div>

                <div style="text-align: center; margin: 20px 0;">
                    <a href="{{ $loginUrl }}" style="display: inline-block; background-color: #1d4ed8; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px;">
                        👤 Üye Paneline Giriş Yap
                    </a>
                    <p style="margin: 8px 0 0 0; font-size: 12px; color: #6b7280; font-style: italic;">
                        Zum Mitgliederbereich
                    </p>
                </div>

                <div style="background-color: #fef3c7; padding: 10px; border-radius: 4px; margin: 15px 0; border-left: 4px solid #f59e0b;">
                    <p style="margin: 0 0 5px 0; font-size: 12px; color: #92400e;">
                        Şifrenizi unuttuysanız, giriş sayfasından "Şifremi Unuttum" linkini kullanabilirsiniz.
                    </p>
                    <p style="margin: 0; font-size: 11px; color: #a16207; font-style: italic;">
                        Falls Sie Ihr Passwort vergessen haben, können Sie den Link "Passwort vergessen" auf der Login-Seite verwenden.
                    </p>
                </div>
            </div>

            <div class="info-box">
                <h3>🏦 Banka Bilgileri</h3>
                <p style="font-size: 12px; color: #6b7280; font-style: italic; margin: -5px 0 15px 0;">Bankdaten</p>

                <div style="background-color: #f8fafc; padding: 12px; border-radius: 4px; margin: 10px 0;">
                    <p style="margin: 0 0 5px 0;"><strong>Banka:</strong> {{ $settings['bank_name'] }}</p>
                    <p style="margin: 0; font-size: 11px; color: #6b7280; font-style: italic;">Bank: {{ $settings['bank_name'] }}</p>
                </div>

                <div style="background-color: #f8fafc; padding: 12px; border-radius: 4px; margin: 10px 0;">
                    <p style="margin: 0 0 5px 0;"><strong>Hesap Sahibi:</strong> {{ $settings['account_holder'] }}</p>
                    <p style="margin: 0; font-size: 11px; color: #6b7280; font-style: italic;">Kontoinhaber: {{ $settings['account_holder'] }}</p>
                </div>

                <div style="background-color: #f8fafc; padding: 12px; border-radius: 4px; margin: 10px 0;">
                    <p style="margin: 0;"><strong>IBAN:</strong> {{ $settings['bank_iban'] }}</p>
                </div>

                <div style="background-color: #f8fafc; padding: 12px; border-radius: 4px; margin: 10px 0;">
                    <p style="margin: 0;"><strong>BIC/SWIFT:</strong> {{ $settings['bank_bic'] }}</p>
                </div>

                <div style="background-color: #f8fafc; padding: 12px; border-radius: 4px; margin: 10px 0;">
                    <p style="margin: 0 0 5px 0;"><strong>Açıklama:</strong> {{ $settings['bank_purpose'] }} - {{ $member->member_no }}</p>
                    <p style="margin: 0; font-size: 11px; color: #6b7280; font-style: italic;">Verwendungszweck: {{ $settings['bank_purpose'] }} - {{ $member->member_no }}</p>
                </div>
            </div>

            <div style="margin: 24px 0; padding: 16px; background-color: #f0f9ff; border-left: 4px solid #0ea5e9; border-radius: 4px;">
                <p class="text-tr" style="margin: 0 0 8px 0;">Herhangi bir sorunuz olursa bizimle iletişime geçebilirsiniz.</p>
                <p class="text-de" style="margin: 0;">Bei Fragen stehen wir Ihnen gerne zur Verfügung.</p>
            </div>

            <div style="text-align: center; margin: 24px 0; padding: 20px; background-color: #f0fdf4; border-radius: 8px;">
                <p style="margin: 0 0 8px 0; font-size: 18px; font-weight: 600; color: #16a34a;">🎉 Cemaatimize hoş geldiniz!</p>
                <p style="margin: 0; font-size: 14px; color: #6b7280; font-style: italic;">Willkommen in unserer Gemeinde!</p>
            </div>
        </div>

        <div class="footer">
            <p><strong>{{ $organizationName }}</strong></p>
            <div style="margin: 8px 0;">
                <p style="margin: 2px 0;">E-posta: {{ $settings['organization_email'] }}</p>
                <p style="margin: 2px 0; font-size: 10px; color: #9ca3af; font-style: italic;">E-Mail: {{ $settings['organization_email'] }}</p>
            </div>
            <div style="margin: 8px 0;">
                <p style="margin: 2px 0;">Telefon: {{ $settings['organization_phone'] }}</p>
                <p style="margin: 2px 0; font-size: 10px; color: #9ca3af; font-style: italic;">Phone: {{ $settings['organization_phone'] }}</p>
            </div>
            <div style="margin: 8px 0;">
                <p style="margin: 2px 0;">Adres: {{ $settings['organization_address'] }}</p>
                <p style="margin: 2px 0; font-size: 10px; color: #9ca3af; font-style: italic;">Address: {{ $settings['organization_address'] }}</p>
            </div>
        </div>
    </div>
</body>
</html>
