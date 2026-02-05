<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Makbuzu - {{ $payment->receipt_no ?: 'NO-' . $payment->id }}</title>
    <style>
                @page {
            margin: 15px;
            size: A4;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.3;
            font-size: 12px;
        }

        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #0f766e;
            border-radius: 10px;
            overflow: hidden;
        }

                .header {
            background: #0f766e;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .organization-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: white !important;
        }

        .receipt-title {
            font-size: 14px;
            color: white !important;
        }

                .receipt-body {
            padding: 20px;
            background: white;
        }

        .receipt-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-section h3 {
            font-size: 13px;
            color: #0f766e;
            margin-bottom: 10px;
            padding-bottom: 3px;
            border-bottom: 1px solid #0f766e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

                .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            padding: 3px 0;
            font-size: 11px;
        }

        .info-item.border {
            border-bottom: 1px solid #e5e7eb;
        }

        .info-label {
            font-weight: 600;
            color: #374151;
            width: 60%;
        }

        .info-value {
            font-weight: 400;
            color: #111827;
            width: 40%;
            text-align: right;
        }

        .payment-details {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px;
            margin: 10px 0;
            font-size: 11px;
        }

        .payment-amount {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #0f766e;
            margin: 15px 0;
            padding: 15px;
            background: linear-gradient(45deg, #f0fdfa, #ccfbf1);
            border-radius: 8px;
            border: 2px solid #0f766e;
        }

        .payment-method {
            display: inline-block;
            background: #0f766e;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

                .receipt-footer {
            background: #f8fafc;
            padding: 15px;
            border-top: 1px solid #e5e7eb;
            display: table;
            width: 100%;
        }

        .footer-section {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }

        .footer-section:last-child {
            padding-right: 0;
            padding-left: 10px;
        }

        .footer-section h4 {
            font-size: 12px;
            color: #374151;
            margin-bottom: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .footer-section p {
            font-size: 10px;
            color: #6b7280;
            line-height: 1.4;
            margin: 1px 0;
        }

                .signature-area {
            text-align: center;
            margin-top: 10px;
        }

        .signature-line {
            border-top: 1px solid #0f766e;
            width: 150px;
            margin: 15px auto 5px;
        }

        .receipt-number {
            position: absolute;
            top: 15px;
            right: 15px;
            background: white;
            color: #0f766e;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 12px;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(15, 118, 110, 0.08);
            font-weight: bold;
            z-index: -1;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        /* Ensure all elements are visible */
        * {
            box-sizing: border-box;
        }

        /* Fix for grid layout */
        .receipt-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .info-section {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }

        .info-section:last-child {
            padding-right: 0;
            padding-left: 10px;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="watermark">MAKBUZ</div>

        @if($payment->receipt_no)
            <div class="receipt-number">{{ $payment->receipt_no }}</div>
        @endif

        <!-- Header -->
        <div class="header">
            <div class="organization-name">{{ $settings['organization_name'] }}</div>
            <div class="receipt-title">Ödeme Makbuzu / Zahlungsbeleg</div>
        </div>

        <!-- Body -->
        <div class="receipt-body">
            <div class="receipt-info">
                <!-- Member Information -->
                <div class="info-section">
                    <h3><i class="fas fa-user"></i> Üye Bilgileri / Mitgliedsdaten</h3>
                    <div class="info-item border">
                        <span class="info-label">Ad Soyad / Name:</span>
                        <span class="info-value">{{ $payment->member->full_name }}</span>
                    </div>
                    <div class="info-item border">
                        <span class="info-label">Üye No / Mitglieds-Nr.:</span>
                        <span class="info-value">{{ $payment->member->member_no }}</span>
                    </div>
                    <div class="info-item border">
                        <span class="info-label">E-posta / E-Mail:</span>
                        <span class="info-value">{{ $payment->member->email ?? 'Belirtilmemiş' }}</span>
                    </div>
                    @if($payment->member->phone)
                    <div class="info-item">
                        <span class="info-label">Telefon:</span>
                        <span class="info-value">{{ $payment->member->phone }}</span>
                    </div>
                    @endif
                </div>

                <!-- Payment Information -->
                <div class="info-section">
                    <h3><i class="fas fa-receipt"></i> Ödeme Bilgileri / Zahlungsdaten</h3>
                    <div class="info-item border">
                        <span class="info-label">Makbuz No / Beleg-Nr.:</span>
                        <span class="info-value">{{ $payment->receipt_no ?: 'NO-' . $payment->id }}</span>
                    </div>
                    <div class="info-item border">
                        <span class="info-label">Ödeme Tarihi / Datum:</span>
                        <span class="info-value">{{ $payment->payment_date->format('d.m.Y') }}</span>
                    </div>
                    <div class="info-item border">
                        <span class="info-label">Aidat Dönemi / Periode:</span>
                        <span class="info-value">{{ $payment->due ? $payment->due->due_date->formatTr('F Y') : 'Genel Ödeme' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Ödeme Yöntemi / Methode:</span>
                        <span class="info-value">
                            <span class="payment-method">
                                @switch($payment->payment_method)
                                    @case('cash')
                                        Nakit / Bar
                                        @break
                                    @case('bank_transfer')
                                        Banka Transferi / Überweisung
                                        @break
                                    @case('lastschrift')
                                        Lastschrift (SEPA)
                                        @break
                                    @case('credit_card')
                                        Kredi Kartı / Kreditkarte
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
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Payment Amount -->
            <div class="payment-amount">
                {{ number_format($payment->amount, 2) }} €
                <div style="font-size: 14px; margin-top: 5px; color: #6b7280;">
                    @if($payment->due)
                        ({{ $payment->due->due_date->formatTr('F Y') }} Aidatı / {{ $payment->due->due_date->formatTr('F Y') }} Beitrag)
                    @else
                        (Genel Ödeme / Allgemeine Zahlung)
                    @endif
                </div>
            </div>

            <!-- Additional Details -->
            @if($payment->description)
            <div class="payment-details">
                <strong>Açıklama / Beschreibung:</strong><br>
                {{ $payment->description }}
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <div class="footer-section">
                <h4>İletişim Bilgileri / Kontaktdaten</h4>
                @if($settings['organization_address'])
                    <p><strong>Adres / Anschrift:</strong> {{ $settings['organization_address'] }}</p>
                @endif
                @if($settings['organization_phone'])
                    <p><strong>Telefon:</strong> {{ $settings['organization_phone'] }}</p>
                @endif
                @if($settings['organization_email'])
                    <p><strong>E-posta / E-Mail:</strong> {{ $settings['organization_email'] }}</p>
                @endif
            </div>

            <div class="footer-section">
                <h4>Makbuz Bilgileri / Belegdaten</h4>
                <p><strong>Düzenleme Tarihi / Ausstellungsdatum:</strong> {{ now()->format('d.m.Y H:i') }}</p>
                @if($payment->recordedBy ?? null)
                    <p><strong>Kaydeden / Erstellt von:</strong> {{ $payment->recordedBy->name ?? 'Sistem' }}</p>
                @endif
                <div class="signature-area">
                    <div class="signature-line"></div>
                    <p><strong>Yetkili İmza / Unterschrift</strong></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
