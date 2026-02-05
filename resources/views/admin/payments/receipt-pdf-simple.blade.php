<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ödeme Makbuzu</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 20px; color: #333; font-size: 12px; line-height: 1.4;">

        <!-- Logo at the top -->
    @if(\App\Models\Settings::hasLogo())
        <div style="text-align: center; margin-bottom: 10px;">
            <img src="{{ public_path('storage/' . \App\Models\Settings::get('logo')) }}"
                 style="width: 80px; height: 80px; object-fit: contain;"
                 alt="Logo">
        </div>
    @endif

    <div style="text-align: center; border-bottom: 2px solid #0f766e; padding-bottom: 15px; margin-bottom: 20px;">
        <div style="font-size: 18px; font-weight: bold; color: #0f766e; margin-bottom: 5px;">{{ $settings['organization_name'] }}</div>
        <div style="font-size: 14px; color: #666;">Ödeme Makbuzu / Zahlungsbeleg</div>
    </div>

    <div style="margin-bottom: 20px;">
        <div style="margin-bottom: 20px;">
            <div style="font-size: 14px; font-weight: bold; color: #0f766e; border-bottom: 1px solid #0f766e; padding-bottom: 5px; margin-bottom: 10px;">Üye Bilgileri / Mitgliedsdaten</div>
            <table style="width: 100%; margin-bottom: 5px;">
                <tr>
                    <td style="width: 40%; font-weight: bold; color: #555;">Ad Soyad / Name:</td>
                    <td style="width: 60%; color: #333;">{{ $payment->member->full_name }}</td>
                </tr>
            </table>
            <table style="width: 100%; margin-bottom: 5px;">
                <tr>
                    <td style="width: 40%; font-weight: bold; color: #555;">Üye No / Mitglieds-Nr.:</td>
                    <td style="width: 60%; color: #333;">{{ $payment->member->member_no }}</td>
                </tr>
            </table>
            <table style="width: 100%; margin-bottom: 5px;">
                <tr>
                    <td style="width: 40%; font-weight: bold; color: #555;">E-posta / E-Mail:</td>
                    <td style="width: 60%; color: #333;">{{ $payment->member->email ?? 'Belirtilmemiş' }}</td>
                </tr>
            </table>
            @if($payment->member->phone)
            <table style="width: 100%; margin-bottom: 5px;">
                <tr>
                    <td style="width: 40%; font-weight: bold; color: #555;">Telefon:</td>
                    <td style="width: 60%; color: #333;">{{ $payment->member->phone }}</td>
                </tr>
            </table>
            @endif
        </div>

        <div style="margin-bottom: 20px;">
            <div style="font-size: 14px; font-weight: bold; color: #0f766e; border-bottom: 1px solid #0f766e; padding-bottom: 5px; margin-bottom: 10px;">Ödeme Bilgileri / Zahlungsdaten</div>
            <table style="width: 100%; margin-bottom: 5px;">
                <tr>
                    <td style="width: 40%; font-weight: bold; color: #555;">Makbuz No / Beleg-Nr.:</td>
                    <td style="width: 60%; color: #333;">{{ $payment->receipt_no ?: 'NO-' . $payment->id }}</td>
                </tr>
            </table>
            <table style="width: 100%; margin-bottom: 5px;">
                <tr>
                    <td style="width: 40%; font-weight: bold; color: #555;">Ödeme Tarihi / Datum:</td>
                    <td style="width: 60%; color: #333;">{{ $payment->payment_date->format('d.m.Y') }}</td>
                </tr>
            </table>
            <table style="width: 100%; margin-bottom: 5px;">
                <tr>
                    <td style="width: 40%; font-weight: bold; color: #555;">Aidat Dönemi / Periode:</td>
                                            <td style="width: 60%; color: #333;">{{ $payment->due ? $payment->due->due_date->formatTr('F Y') : 'Genel Ödeme' }}</td>
                </tr>
            </table>
            <table style="width: 100%; margin-bottom: 5px;">
                <tr>
                    <td style="width: 40%; font-weight: bold; color: #555;">Ödeme Yöntemi / Methode:</td>
                    <td style="width: 60%; color: #333;">
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
                    </td>
                </tr>
            </table>
        </div>

        <div style="text-align: center; font-size: 24px; font-weight: bold; color: #0f766e; background: #f0fdfa; padding: 15px; border: 2px solid #0f766e; border-radius: 8px; margin: 20px 0;">
            {{ number_format($payment->amount, 2) }} €
            @if($payment->due)
                <div style="font-size: 14px; margin-top: 5px; color: #666;">
                                            ({{ $payment->due->due_date->formatTr('F Y') }} Aidatı)
                </div>
            @endif
        </div>

        @if($payment->description)
        <div style="margin-bottom: 20px;">
            <div style="font-size: 14px; font-weight: bold; color: #0f766e; border-bottom: 1px solid #0f766e; padding-bottom: 5px; margin-bottom: 10px;">Açıklama / Beschreibung</div>
            <div style="color: #333;">{{ $payment->description }}</div>
        </div>
        @endif
    </div>

    <!-- Makbuz Bilgileri ve İmza - Alt Alta -->
    <div style="text-align: center; margin-top: 40px; font-size: 10px; color: #666;">
        <div style="margin-bottom: 30px;">
            <strong style="color: #0f766e; font-size: 12px;">Makbuz Bilgileri / Belegdaten</strong><br>
            <strong>Düzenleme Tarihi:</strong> {{ now()->format('d.m.Y H:i') }}
        </div>
        <div style="text-align: center;">
            <div style="border-top: 1px solid #0f766e; width: 150px; margin: 10px auto 5px;"></div>
            <strong>Yetkili İmza / Unterschrift</strong>
        </div>
    </div>

    <!-- Footer İletişim Bilgileri -->
    <div style="margin-top: 50px; padding-top: 15px; border-top: 2px solid #0f766e; text-align: center; font-size: 9px; color: #666; background-color: #f8fafc;">
        <div style="margin-bottom: 5px;">
            <strong style="color: #0f766e; font-size: 10px;">{{ $settings['organization_name'] }}</strong>
        </div>
        <div>
            @if($settings['organization_address'])
                <strong>Adres:</strong> {{ $settings['organization_address'] }}
            @endif
            @if($settings['organization_phone'])
                | <strong>Tel:</strong> {{ $settings['organization_phone'] }}
            @endif
            @if($settings['organization_email'])
                | <strong>E-posta:</strong> {{ $settings['organization_email'] }}
            @endif
        </div>
    </div>
</body>
</html>
