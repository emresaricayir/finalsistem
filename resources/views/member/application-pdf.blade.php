<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üyelik Başvuru Formu - {{ $member->name }} {{ $member->surname }}</title>
    <style>
        @page {
            margin: 20mm;
            size: A4;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
            color: #1e293b;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            color: white;
            border-radius: 12px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 10px 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 14px;
            margin: 0;
            opacity: 0.9;
        }

        .section {
            margin-bottom: 25px;
            padding: 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: #f9fafb;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #0d9488;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #0d9488;
        }

        .form-row {
            display: flex;
            margin-bottom: 15px;
        }

        .form-group {
            flex: 1;
            margin-right: 15px;
        }

        .form-group:last-child {
            margin-right: 0;
        }

        .form-label {
            font-size: 12px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-value {
            font-size: 14px;
            color: #1f2937;
            padding: 8px 12px;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            min-height: 20px;
        }

        .form-value.full-width {
            width: 100%;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .footer {
            margin-top: 30px;
            padding: 15px;
            background: #f3f4f6;
            border-radius: 8px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }

        .signature-section {
            margin-top: 30px;
            padding: 20px;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #374151;
            width: 200px;
            margin: 20px auto 5px;
        }

        .signature-label {
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>ÜYELİK BAŞVURU FORMU</h1>
        <p>MITGLIEDSCHAFTSANTRAG</p>
    </div>

    <!-- Personal Information -->
    <div class="section">
        <div class="section-title">Kişisel Bilgiler / Persönliche Angaben</div>

        <div class="form-row">
            <div class="form-group">
                <div class="form-label">Adı / Vorname</div>
                <div class="form-value">{{ $member->name }}</div>
            </div>
            <div class="form-group">
                <div class="form-label">Soyadı / Nachname</div>
                <div class="form-value">{{ $member->surname }}</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <div class="form-label">E-Mail</div>
                <div class="form-value">{{ $member->email }}</div>
            </div>
            <div class="form-group">
                <div class="form-label">Telefon / Telefon</div>
                <div class="form-value">{{ $member->phone ?: 'Belirtilmemiş' }}</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <div class="form-label">Doğum Tarihi / Geburtsdatum</div>
                <div class="form-value">{{ $member->birth_date ? $member->birth_date->format('d.m.Y') : 'Belirtilmemiş' }}</div>
            </div>
            <div class="form-group">
                <div class="form-label">Doğum Yeri / Geburtsort</div>
                <div class="form-value">{{ $member->birth_place ?: 'Belirtilmemiş' }}</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <div class="form-label">Uyruk / Staatsangehörigkeit</div>
                <div class="form-value">{{ $member->nationality ?: 'Belirtilmemiş' }}</div>
            </div>
            <div class="form-group">
                <div class="form-label">Meslek / Beruf</div>
                <div class="form-value">{{ $member->occupation ?: 'Belirtilmemiş' }}</div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-label">Adres / Anschrift</div>
            <div class="form-value full-width">{{ $member->address }}</div>
        </div>
    </div>

    <!-- Membership Information -->
    <div class="section">
        <div class="section-title">Üyelik Bilgileri / Mitgliedschaftsinformationen</div>

        <div class="form-row">
            <div class="form-group">
                <div class="form-label">Üye No / Mitgliedsnummer</div>
                <div class="form-value">{{ $member->member_no }}</div>
            </div>
            <div class="form-group">
                <div class="form-label">Üyelik Tarihi / Mitgliedschaftsdatum</div>
                <div class="form-value">{{ $member->membership_date->format('d.m.Y') }}</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <div class="form-label">Aylık Aidat / Monatlicher Beitrag</div>
                <div class="form-value">{{ number_format($member->monthly_dues, 2) }} €</div>
            </div>
            <div class="form-group">
                <div class="form-label">Ödeme Yöntemi / Zahlungsmethode</div>
                <div class="form-value">
                    @switch($member->payment_method)
                        @case('cash')
                            Nakit / Bar
                            @break
                        @case('bank_transfer')
                            Banka Havalesi / Banküberweisung
                            @break
                        @case('lastschrift_monthly')
                            Lastschrift (Aylık) / Lastschrift (Monatlich)
                            @break
                        @case('lastschrift_semi_annual')
                            Lastschrift (6 Aylık) / Lastschrift (Halbjährlich)
                            @break
                        @case('lastschrift_annual')
                            Lastschrift (Yıllık) / Lastschrift (Jährlich)
                            @break
                        @default
                            {{ $member->payment_method }}
                    @endswitch
                </div>
            </div>
        </div>
    </div>

    <!-- Application Status -->
    <div class="section">
        <div class="section-title">Başvuru Durumu / Antragsstatus</div>

        <div class="form-row">
            <div class="form-group">
                <div class="form-label">Başvuru Durumu / Antragsstatus</div>
                <div class="form-value">
                    @switch($member->application_status)
                        @case('pending')
                            <span class="status-badge status-pending">Onay Bekliyor / Ausstehend</span>
                            @break
                        @case('approved')
                            <span class="status-badge status-approved">Onaylandı / Genehmigt</span>
                            @break
                        @case('rejected')
                            <span class="status-badge status-rejected">Reddedildi / Abgelehnt</span>
                            @break
                        @default
                            {{ $member->application_status }}
                    @endswitch
                </div>
            </div>
            <div class="form-group">
                <div class="form-label">Başvuru Tarihi / Antragsdatum</div>
                <div class="form-value">{{ $member->application_date ? $member->application_date->format('d.m.Y H:i') : 'Belirtilmemiş' }}</div>
            </div>
        </div>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-line"></div>
        <div class="signature-label">Başvuru Sahibi İmzası / Antragsteller Unterschrift</div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>{{ $settings['organization_name'] }}</strong></p>
        @if($settings['organization_address'])
            <p>{{ $settings['organization_address'] }}</p>
        @endif
        @if($settings['organization_phone'])
            <p>Tel: {{ $settings['organization_phone'] }}</p>
        @endif
        @if($settings['organization_email'])
            <p>E-Mail: {{ $settings['organization_email'] }}</p>
        @endif
        <p>Bu belge {{ now()->format('d.m.Y H:i') }} tarihinde oluşturulmuştur.</p>
    </div>
</body>
</html>
