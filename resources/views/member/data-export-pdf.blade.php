<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meine Daten - {{ $member->member_no }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 18pt;
            margin: 0;
            color: #1a1a1a;
        }
        .header p {
            font-size: 9pt;
            color: #666;
            margin: 5px 0;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ccc;
        }
        .info-row {
            margin-bottom: 8px;
            padding: 5px 0;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 180px;
            color: #555;
        }
        .info-value {
            display: inline-block;
            color: #333;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9pt;
        }
        .table th {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        .table td {
            border: 1px solid #ddd;
            padding: 6px;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            font-size: 8pt;
            color: #666;
            text-align: center;
        }
        .no-data {
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Meine Daten / Verilerim</h1>
        <p>{{ $settings['organization_name'] }}</p>
        <p>Exportiert am: {{ \Carbon\Carbon::parse($memberData['export_date'])->format('d.m.Y H:i') }}</p>
    </div>

    <!-- Member Information -->
    <div class="section">
        <div class="section-title">Mitgliedsinformationen / Üye Bilgileri</div>
        <div class="info-row">
            <span class="info-label">Mitgliedsnummer:</span>
            <span class="info-value">{{ $memberData['member_information']['member_no'] }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Name:</span>
            <span class="info-value">{{ $memberData['member_information']['name'] }} {{ $memberData['member_information']['surname'] }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Geschlecht:</span>
            <span class="info-value">{{ $memberData['member_information']['gender'] ?? 'Nicht angegeben' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">E-Mail:</span>
            <span class="info-value">{{ $memberData['member_information']['email'] }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Telefon:</span>
            <span class="info-value">{{ $memberData['member_information']['phone'] ?? 'Nicht angegeben' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Geburtsdatum:</span>
            <span class="info-value">{{ $memberData['member_information']['birth_date'] ? \Carbon\Carbon::parse($memberData['member_information']['birth_date'])->format('d.m.Y') : 'Nicht angegeben' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Geburtsort:</span>
            <span class="info-value">{{ $memberData['member_information']['birth_place'] ?? 'Nicht angegeben' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Staatsangehörigkeit:</span>
            <span class="info-value">{{ $memberData['member_information']['nationality'] ?? 'Nicht angegeben' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Adresse:</span>
            <span class="info-value">{{ $memberData['member_information']['address'] ?? 'Nicht angegeben' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Beruf:</span>
            <span class="info-value">{{ $memberData['member_information']['occupation'] ?? 'Nicht angegeben' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Mitgliedschaftsdatum:</span>
            <span class="info-value">{{ $memberData['member_information']['membership_date'] ? \Carbon\Carbon::parse($memberData['member_information']['membership_date'])->format('d.m.Y') : 'Nicht angegeben' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span class="info-value">{{ $memberData['member_information']['status'] }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Datenschutz-Einwilligung:</span>
            <span class="info-value">
                @php
                    $privacyConsent = $memberData['member_information']['privacy_consent'];
                    $privacyConsentDate = $memberData['member_information']['privacy_consent_date'] ?? null;
                    $membershipDate = $memberData['member_information']['membership_date'] ?? null;
                    
                    if ($privacyConsent === true || $privacyConsent === 1) {
                        $displayText = 'Ja';
                        // Eğer privacy_consent_date varsa onu kullan, yoksa membership_date kullan
                        $dateToShow = $privacyConsentDate ? $privacyConsentDate : $membershipDate;
                        if ($dateToShow) {
                            $displayText .= ', ' . \Carbon\Carbon::parse($dateToShow)->format('d.m.Y');
                        }
                    } elseif ($privacyConsent === false || $privacyConsent === 0) {
                        $displayText = 'Nein';
                    } else {
                        $displayText = 'Nicht angegeben / Belirtilmemiş';
                    }
                @endphp
                {{ $displayText }}
            </span>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="section">
        <div class="section-title">Zahlungsinformationen / Ödeme Bilgileri</div>
        <div class="info-row">
            <span class="info-label">Monatlicher Beitrag:</span>
            <span class="info-value">{{ number_format($memberData['payment_information']['monthly_dues'], 2, ',', '.') }} €</span>
        </div>
        <div class="info-row">
            <span class="info-label">Zahlungsmethode:</span>
            <span class="info-value">{{ $memberData['payment_information']['payment_method'] ?? 'Nicht angegeben' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Zahlungsfrequenz:</span>
            <span class="info-value">{{ $memberData['payment_information']['payment_frequency'] ?? 'Nicht angegeben' }}</span>
        </div>
        @if($memberData['payment_information']['sepa_agreement'])
        <div class="info-row">
            <span class="info-label">SEPA-Lastschrift:</span>
            <span class="info-value">Ja</span>
        </div>
        <div class="info-row">
            <span class="info-label">Mandatsnummer:</span>
            <span class="info-value">{{ $memberData['payment_information']['mandate_number'] ?? 'Nicht angegeben' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Kontoinhaber:</span>
            <span class="info-value">{{ $memberData['payment_information']['account_holder'] ?? 'Nicht angegeben' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Bank:</span>
            <span class="info-value">{{ $memberData['payment_information']['bank_name'] ?? 'Nicht angegeben' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">IBAN:</span>
            <span class="info-value">{{ $memberData['payment_information']['iban'] ?? 'Nicht angegeben' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">BIC:</span>
            <span class="info-value">{{ $memberData['payment_information']['bic'] ?? 'Nicht angegeben' }}</span>
        </div>
        @endif
    </div>

    <!-- Payments -->
    <div class="section">
        <div class="section-title">Zahlungen / Ödemeler</div>
        @if(count($memberData['payments']) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Betrag</th>
                    <th>Zahlungsmethode</th>
                    <th>Quittungsnummer</th>
                    <th>Beschreibung</th>
                </tr>
            </thead>
            <tbody>
                @foreach($memberData['payments'] as $payment)
                <tr>
                    <td>{{ $payment['payment_date'] ? \Carbon\Carbon::parse($payment['payment_date'])->format('d.m.Y') : '-' }}</td>
                    <td>{{ number_format($payment['amount'], 2, ',', '.') }} €</td>
                    <td>{{ $payment['payment_method'] ?? '-' }}</td>
                    <td>{{ $payment['receipt_no'] ?? '-' }}</td>
                    <td>{{ $payment['description'] ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="no-data">Keine Zahlungen gefunden.</p>
        @endif
    </div>

    <div class="footer">
        <p>Dieses Dokument wurde gemäß DSGVO Artikel 15 (Auskunftsrecht) und Artikel 20 (Datenübertragbarkeit) erstellt.</p>
        <p>Bu belge DSGVO Madde 15 (Bilgi Alma Hakkı) ve Madde 20 (Veri Taşınabilirliği) uyarınca oluşturulmuştur.</p>
    </div>
</body>
</html>
