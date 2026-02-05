<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Makbuzu - {{ $payment->receipt_no ?: 'NO-' . $payment->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
        }

        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #0f766e;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 15px;
        }

        .logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            background: white;
            border-radius: 8px;
            padding: 5px;
            border: 2px solid #0f766e;
        }

        .organization-name {
            font-size: 24px;
            font-weight: bold;
            color: #0f766e;
            margin-bottom: 10px;
        }

        .receipt-title {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }

        .receipt-number {
            display: inline-block;
            background: #0f766e;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #0f766e;
            border-bottom: 2px solid #0f766e;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 10px;
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-value {
            color: #333;
        }

        .amount-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: linear-gradient(135deg, #f0fdfa 0%, #e6fffa 100%);
            border: 3px solid #0f766e;
            border-radius: 10px;
        }

        .amount {
            font-size: 32px;
            font-weight: bold;
            color: #0f766e;
            margin-bottom: 10px;
        }

        .amount-period {
            font-size: 16px;
            color: #666;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .footer-section {
            font-size: 12px;
            color: #666;
        }

        .footer-section h4 {
            color: #0f766e;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .signature-area {
            text-align: center;
            margin-top: 20px;
        }

        .signature-line {
            border-top: 2px solid #0f766e;
            width: 200px;
            margin: 15px auto 10px;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #0f766e;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .print-button:hover {
            background: #0d5a52;
        }

        @media print {
            .print-button {
                display: none;
            }

            body {
                background: white;
                padding: 0;
            }

            .receipt-container {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        🖨️ Yazdır / Drucken
    </button>

    <div class="receipt-container">
        <!-- Logo at the top -->
        @if(\App\Models\Settings::hasLogo())
            <div style="text-align: center; margin-bottom: 15px;">
                <img src="{{ asset('storage/' . \App\Models\Settings::get('logo')) }}"
                     style="width: 100px; height: 100px; object-fit: contain;"
                     alt="Logo">
            </div>
        @endif

        <div class="header">
            <div class="organization-name">{{ $settings['organization_name'] }}</div>
            <div class="receipt-title">Ödeme Makbuzu / Zahlungsbeleg</div>
        </div>

        <div class="section">
            <div class="section-title">Üye Bilgileri / Mitgliedsdaten</div>
            <div class="info-grid">
                <div class="info-label">Ad Soyad / Name:</div>
                <div class="info-value">{{ $payment->member->full_name }}</div>
            </div>
            <div class="info-grid">
                <div class="info-label">Üye No / Mitglieds-Nr.:</div>
                <div class="info-value">{{ $payment->member->member_no }}</div>
            </div>
            <div class="info-grid">
                <div class="info-label">E-posta / E-Mail:</div>
                <div class="info-value">{{ $payment->member->email ?? 'Belirtilmemiş' }}</div>
            </div>
            @if($payment->member->phone)
            <div class="info-grid">
                <div class="info-label">Telefon:</div>
                <div class="info-value">{{ $payment->member->phone }}</div>
            </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Ödeme Bilgileri / Zahlungsdaten</div>
            <div class="info-grid">
                <div class="info-label">Makbuz No / Beleg-Nr.:</div>
                <div class="info-value">{{ $payment->receipt_no ?: 'NO-' . $payment->id }}</div>
            </div>
            <div class="info-grid">
                <div class="info-label">Ödeme Tarihi / Datum:</div>
                <div class="info-value">{{ $payment->payment_date->format('d.m.Y') }}</div>
            </div>
            <div class="info-grid">
                <div class="info-label">Aidat Dönemi / Periode:</div>
                                        <div class="info-value">{{ $payment->due ? $payment->due->due_date->formatTr('F Y') : 'Genel Ödeme' }}</div>
            </div>
            <div class="info-grid">
                <div class="info-label">Ödeme Yöntemi / Methode:</div>
                <div class="info-value">
                    @switch($payment->payment_method)
                        @case('cash')
                            Nakit / Bar
                            @break
                        @case('bank_transfer')
                            Banka Transferi / Überweisung
                            @break
                        @case('credit_card')
                            Kredi Kartı / Kreditkarte
                            @break
                        @case('direct_debit')
                            Otomatik Ödeme / Lastschrift
                            @break
                        @case('standing_order')
                            Düzenli Transfer / Dauerauftrag
                            @break
                        @case('other')
                            Diğer / Sonstiges
                            @break
                        @default
                            {{ $payment->payment_method_text ?? $payment->payment_method }}
                    @endswitch
                </div>
            </div>
        </div>

        <div class="amount-section">
            <div class="amount">{{ number_format($payment->amount, 2) }} €</div>
            @if($payment->due)
                <div class="amount-period">
                                            ({{ $payment->due->due_date->formatTr('F Y') }} Aidatı)
                </div>
            @endif
        </div>

        @if($payment->description)
        <div class="section">
            <div class="section-title">Açıklama / Beschreibung</div>
            <div class="info-value">{{ $payment->description }}</div>
        </div>
        @endif

        <!-- Makbuz Bilgileri ve İmza Alanı - Alt Alta -->
        <div style="margin-top: 40px; text-align: center;">
            <div style="margin-bottom: 30px;">
                <h4 style="color: #0f766e; margin-bottom: 10px; font-size: 14px;">Makbuz Bilgileri / Belegdaten</h4>
                <div style="font-size: 12px; color: #666;">
                    <strong>Düzenleme Tarihi:</strong> {{ now()->format('d.m.Y H:i') }}
                </div>
            </div>
            <div class="signature-area">
                <div class="signature-line"></div>
                <strong>Yetkili İmza / Unterschrift</strong>
            </div>
        </div>

        <!-- Footer İletişim Bilgileri -->
        <div style="margin-top: 50px; padding-top: 20px; border-top: 2px solid #0f766e; text-align: center; font-size: 11px; color: #666; background-color: #f8fafc;">
            <div style="margin-bottom: 5px;">
                <strong style="color: #0f766e;">{{ $settings['organization_name'] }}</strong>
            </div>
            <div style="display: inline-block;">
                @if($settings['organization_address'])
                    <span><strong>Adres:</strong> {{ $settings['organization_address'] }}</span>
                @endif
                @if($settings['organization_phone'])
                    <span style="margin-left: 20px;"><strong>Tel:</strong> {{ $settings['organization_phone'] }}</span>
                @endif
                @if($settings['organization_email'])
                    <span style="margin-left: 20px;"><strong>E-posta:</strong> {{ $settings['organization_email'] }}</span>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Auto-print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
