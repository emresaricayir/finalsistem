<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üyelik Başvurunuz Alındı / Mitgliedschaftsantrag erhalten - {{ $settings['organization_name'] }}</title>
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
        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        .info-box h3 {
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
            <p>Üyelik Başvurunuz Alındı / Mitgliedschaftsantrag erhalten</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Welcome Message -->
            <p><strong>Sehr geehrte/r {{ $member->name }} {{ $member->surname }},</strong></p>
            <p><strong>Değerli {{ $member->name }} {{ $member->surname }},</strong></p>

            <p>Vielen Dank für Ihre Mitgliedschaftsbewerbung.</p>
            <p>Derneğimize üye başvurusu yaptığınız için teşekkür ederiz.</p>

            <!-- Application Information -->
            <div class="info-box">
                <h3>Başvuru Bilgileri / Antragsinformationen</h3>
                <div class="info-row">
                    <span class="info-label">Üye No / Mitgliedsnummer:</span>
                    <span class="info-value">{{ $member->member_no }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ad Soyad / Name:</span>
                    <span class="info-value">{{ $member->name }} {{ $member->surname }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">E-mail:</span>
                    <span class="info-value">{{ $member->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Aylık Aidat / Monatlicher Beitrag:</span>
                    <span class="info-value">{{ $member->monthly_dues }} €</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ödeme Yöntemi / Zahlungsmethode:</span>
                    <span class="info-value">
                        @if($member->payment_method == 'cash') Elden Ödeme / Barzahlung
                        @elseif($member->payment_method == 'direct_debit') Otomatik Ödeme / Lastschrift
                        @elseif($member->payment_method == 'standing_order') Düzenli Ödeme / Dauerauftrag
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Başvuru Tarihi / Antragsdatum:</span>
                    <span class="info-value">{{ $member->application_date->format('d.m.Y H:i') }}</span>
                </div>
            </div>

            <!-- Process Information -->
            <div class="info-box">
                <h3>Süreç / Prozess</h3>
                <p><strong>✅ Formu doldurup gönderdiniz / Sie haben das Formular ausgefüllt und gesendet</strong> - Bu adım tamamlandı / Dieser Schritt ist abgeschlossen</p>
                <p><strong>⏳ Yönetim kurulu tarafından değerlendirilecektir / Wird vom Vorstand geprüft</strong> - Yaklaşık 15 gün / Etwa 15 Tage</p>
                <p><strong>🔜 Onay sonrası üyelik sistemine giriş yapabilirsiniz / Nach Genehmigung können Sie sich im Mitgliedersystem anmelden</strong> - E-mail ile bilgilendirme / Benachrichtigung per E-Mail</p>
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
