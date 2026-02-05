<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aidat Ödeme Hatırlatması</title>
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
            background-color: #059669;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .due-info {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #dc2626;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
        }
        .button {
            display: inline-block;
            background-color: #059669;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🕌 Cami Üyelik Sistemi</h1>
        <p>Aidat Ödeme Hatırlatması</p>
    </div>

    <div class="content">
        <h2>Sayın {{ $member->full_name }},</h2>

        <p>Merhaba,</p>

        <p>Bu e-posta, {{ $due->month_name }} {{ $due->year }} ayına ait aidat ödemeniz hakkında bir hatırlatmadır.</p>

        <div class="due-info">
            <h3>📋 Aidat Bilgileri</h3>
            <p><strong>Dönem:</strong> {{ $due->month_name }} {{ $due->year }}</p>
            <p><strong>Vade Tarihi:</strong> {{ $due->due_date->format('d.m.Y') }}</p>
            <p><strong>Tutar:</strong> <span class="amount">{{ number_format($due->amount, 2) }} €</span></p>
            @if($totalOverdue > 0)
                <p><strong>Toplam Gecikmiş Borç:</strong> <span class="amount">{{ number_format($totalOverdue, 2) }} €</span></p>
            @endif
        </div>

        <p>Ödemenizi aşağıdaki yöntemlerle yapabilirsiniz:</p>
        <ul>
            <li>💳 Kredi/Banka Kartı ile online ödeme</li>
            <li>🏦 Banka havalesi</li>
            <li>💵 Nakit ödeme (cami idaresine)</li>
        </ul>

        <p><strong>Önemli:</strong> Gecikmiş ödemeler için ek ücret uygulanabilir.</p>

        <p>Herhangi bir sorunuz varsa, lütfen bizimle iletişime geçin.</p>

        <p>Teşekkürler,<br>
        <strong>Dernek Yönetimi</strong></p>

        <div class="footer">
            <p>
                Bu e-posta {{ \App\Models\Settings::get('organization_name', 'Cami Üyelik') }} tarafından gönderilmiştir.<br>
                © {{ date('Y') }} {{ \App\Models\Settings::get('organization_name', 'Cami Üyelik') }}. Tüm hakları saklıdır.
            </p>
        </div>
    </div>
</body>
</html>
