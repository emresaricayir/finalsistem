<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoş Geldiniz / Willkommen - {{ $settings['organization_name'] }}</title>
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
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .content {
            padding: 20px;
        }
        .member-info {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        .member-info h3 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 16px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
        }
        .info-value {
            font-family: monospace;
            background-color: #e9ecef;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .login-section {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .login-button {
            display: inline-block;
            background-color: #007bff;
            color: white !important;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 10px;
        }
        .login-button:hover {
            background-color: #0056b3;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 12px;
        }
        .contact-info {
            margin-top: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ $settings['organization_name'] }}</h1>
            <p>Hoş Geldiniz / Willkommen</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Welcome Message -->
            <p><strong>Sehr geehrte/r {{ $member->name }} {{ $member->surname }},</strong></p>
            <p><strong>Değerli {{ $member->name }} {{ $member->surname }},</strong></p>

            <p>Vielen Dank für Ihre Mitgliedschaft in unserem Verein.</p>
            <p>Derneğimize üye olduğunuz için teşekkür ederiz.</p>

            <!-- Member Information -->
            <div class="member-info">
                <h3>Mitgliedschaftsinformationen / Üyelik Bilgileri</h3>
                <div class="info-row">
                    <span class="info-label">Mitgliedsnummer / Üye Numarası:</span>
                    <span class="info-value">{{ $member->member_no }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Mitgliedschaftsdatum / Üyelik Tarihi:</span>
                    <span class="info-value">{{ $member->membership_date->format('d.m.Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Monatlicher Beitrag / Aylık Aidat:</span>
                    <span class="info-value">€{{ number_format($member->monthly_dues, 2) }}</span>
                </div>
            </div>

            <!-- Login Information -->
            <div class="login-section">
                <h3 style="margin-top: 0;">Mitgliederbereich Login / Üye Paneli Girişi</h3>
                <p><strong>Benutzername / Kullanıcı Adınız:</strong> <code style="background: white; padding: 4px 8px; border-radius: 4px;">{{ $member->email }}</code></p>
                <p style="font-size: 14px; margin-top: 15px;">
                    <em>Bitte erstellen Sie Ihr Passwort über den untenstehenden Link.</em><br>
                    <em>Lütfen şifrenizi aşağıdaki bağlantı ile belirleyiniz.</em>
                </p>
                <a href="{{ url('/sifre-olustur?token='.$member->activation_token) }}" class="login-button">Passwort erstellen / Şifrenizi Belirleyin</a>
            </div>

            <p>Bei Fragen stehen wir Ihnen gerne zur Verfügung.</p>
            <p>Herhangi bir sorunuz olursa bizimle iletişime geçebilirsiniz.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="contact-info">
                @if($settings['organization_address'])
                    <p><strong>Adresse / Adres:</strong> {{ $settings['organization_address'] }}</p>
                @endif
                @if($settings['organization_phone'])
                    <p><strong>Telefon:</strong> {{ $settings['organization_phone'] }}</p>
                @endif
                @if($settings['organization_email'])
                    <p><strong>E-Mail / E-posta:</strong> {{ $settings['organization_email'] }}</p>
                @endif
            </div>
            <p style="margin-top: 15px; font-size: 12px;">
                Diese E-Mail wurde automatisch versendet. Bitte antworten Sie nicht.<br>
                Bu e-posta otomatik olarak gönderilmiştir. Lütfen yanıtlamayın.
            </p>
        </div>
    </div>
</body>
</html>
