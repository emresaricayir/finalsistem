<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aidat Ödeme Hatırlatması / Beitragszahlung Erinnerung</title>
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
            background-color: #dc2626;
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
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        .info-box h3 {
            margin-top: 0;
            color: #dc2626;
            font-size: 16px;
        }
        .dues-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .dues-table th,
        .dues-table td {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            text-align: left;
        }
        .dues-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total-row {
            background-color: #fef2f2;
            font-weight: bold;
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
        <div class="header">
            <h1>⚠️ Aidat Ödeme Hatırlatması / Beitragszahlung Erinnerung</h1>
            <p style="margin: 5px 0 0 0; font-size: 14px;">{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik') }}</p>
        </div>

        <div class="content">
            <!-- Ödeme Uyarısı -->
            <div style="background-color: #fef3c7; border: 2px solid #f59e0b; border-radius: 8px; padding: 15px; margin-bottom: 20px; text-align: center;">
                <p style="margin: 0; font-weight: bold; color: #92400e; font-size: 14px;">
                    ⚠️ ÖDEME YAPTIYSANIZ LÜTFEN MAİLİ DİKKATE ALMAYINIZ
                </p>
                <p style="margin: 5px 0 0 0; font-size: 12px; color: #a16207; font-style: italic;">
                    Falls Sie bereits bezahlt haben, ignorieren Sie bitte diese E-Mail
                </p>
            </div>

            <p><strong>Sehr geehrte/r {{ $member->name }} {{ $member->surname }},</strong></p>
            <p><strong>Saygıdeğer {{ $member->name }} {{ $member->surname }},</strong></p>

            <p>Wir möchten Sie daran erinnern, dass folgende Beiträge noch ausstehen:</p>
            <p>Aşağıdaki aidatlarınızın henüz ödenmediğini hatırlatmak isteriz:</p>

            <div class="info-box">
                <h3>Üye Bilgileri / Mitgliedsinformationen</h3>
                <p><strong>Üye No / Mitgliedsnummer:</strong> {{ $member->member_no }}</p>
                <p><strong>Ad Soyad / Name:</strong> {{ $member->name }} {{ $member->surname }}</p>
                <p><strong>E-posta / E-Mail:</strong> {{ $member->email }}</p>
            </div>

            <div class="info-box">
                <h3>Gecikmiş Aidatlar / Ausstehende Beiträge</h3>
                <table class="dues-table">
                    <thead>
                        <tr>
                            <th>Dönem / Zeitraum</th>
                            <th>Vade Tarihi / Fälligkeitsdatum</th>
                            <th>Tutar / Betrag</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalAmount = 0; @endphp
                        @foreach($overdueDues as $due)
                            @php $totalAmount += $due->amount; @endphp
                            <tr>
                                <td>{{ $due->month_name }} {{ $due->year }}</td>
                                <td>{{ $due->formatted_due_date ?? \Carbon\Carbon::parse($due->due_date)->format('d.m.Y') }}</td>
                                <td>€{{ number_format($due->amount, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="2"><strong>Toplam / Gesamt</strong></td>
                            <td><strong>€{{ number_format($totalAmount, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="info-box">
                <h3>🏦 Banka Bilgileri / Bankdaten</h3>
                @if(\App\Models\Settings::get('bank_name'))
                    <p><strong>Banka / Bank:</strong> {{ \App\Models\Settings::get('bank_name') }}</p>
                @endif
                @if(\App\Models\Settings::get('account_holder'))
                    <p><strong>Hesap Sahibi / Kontoinhaber:</strong> {{ \App\Models\Settings::get('account_holder') }}</p>
                @endif
                @if(\App\Models\Settings::get('bank_iban'))
                    <p><strong>IBAN:</strong> {{ \App\Models\Settings::get('bank_iban') }}</p>
                @endif
                @if(\App\Models\Settings::get('bank_bic'))
                    <p><strong>BIC/SWIFT:</strong> {{ \App\Models\Settings::get('bank_bic') }}</p>
                @endif
                @if(\App\Models\Settings::get('bank_purpose'))
                    <p><strong>Verwendungszweck / Açıklama:</strong> {{ \App\Models\Settings::get('bank_purpose') }} - {{ $member->member_no }}</p>
                @endif
            </div>

            <p>Bitte überweisen Sie den ausstehenden Betrag bis zum nächstmöglichen Termin.</p>
            <p>Lütfen gecikmiş tutarı en kısa sürede ödeyiniz.</p>

            <p>Bei Fragen stehen wir Ihnen gerne zur Verfügung.</p>
            <p>Herhangi bir sorunuz olursa bizimle iletişime geçebilirsiniz.</p>
        </div>

        <div class="footer">
            <p><strong>{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik') }}</strong></p>
            <div class="contact-info">
                @if(\App\Models\Settings::get('organization_email'))
                    <p>E-posta / E-Mail: {{ \App\Models\Settings::get('organization_email') }}</p>
                @endif
                @if(\App\Models\Settings::get('organization_phone'))
                    <p>Telefon / Phone: {{ \App\Models\Settings::get('organization_phone') }}</p>
                @endif
                @if(\App\Models\Settings::get('organization_address'))
                    <p>Adres / Address: {{ \App\Models\Settings::get('organization_address') }}</p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
