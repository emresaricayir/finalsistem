<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitgliedsbescheinigung - {{ $member->name }} {{ $member->surname }}</title>
    <style>
        @page {
            margin: 0;
            size: A4;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            body {
                margin: 0 !important;
                padding: 10px !important;
                background: white !important;
                font-size: 12px !important;
                line-height: 1.3 !important;
            }

            .certificate-container {
                max-width: 100% !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
                padding: 15px !important;
                height: auto !important;
                max-height: 100vh !important;
                overflow: hidden !important;
            }

            .header {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                padding: 20px 15px !important;
                margin-bottom: 15px !important;
            }

            .logo-container {
                margin-bottom: 10px !important;
                padding: 8px !important;
            }

            .logo {
                max-height: 60px !important;
                max-width: 60px !important;
            }

            .organization-name {
                font-size: 18px !important;
                margin-bottom: 5px !important;
            }

            .organization-subtitle {
                font-size: 12px !important;
            }

            .certificate-body {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                padding: 15px !important;
            }

            .certificate-title {
                font-size: 16px !important;
                margin-bottom: 15px !important;
                padding-bottom: 8px !important;
            }

            .certificate-text {
                font-size: 11px !important;
                line-height: 1.4 !important;
                margin-bottom: 10px !important;
            }

            .certificate-text-german {
                font-size: 10px !important;
                line-height: 1.3 !important;
                margin-bottom: 15px !important;
            }

            .member-info {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                padding: 15px !important;
                margin: 10px 0 !important;
            }

            .member-name {
                font-size: 16px !important;
                margin-bottom: 10px !important;
                padding-bottom: 8px !important;
            }

            .member-birth-info {
                margin-bottom: 8px !important;
                padding: 8px !important;
            }

            .birth-detail {
                margin-bottom: 5px !important;
            }

            .birth-label {
                font-size: 10px !important;
            }

            .birth-value {
                font-size: 10px !important;
                padding: 3px 6px !important;
            }

            .member-details {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 8px !important;
            }

            .member-detail {
                margin-bottom: 5px !important;
            }

            .member-detail-label {
                font-size: 9px !important;
                margin-bottom: 3px !important;
            }

            .member-detail-value {
                font-size: 10px !important;
                padding: 4px 6px !important;
            }

            .certificate-footer {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                margin-top: 15px !important;
                padding-top: 10px !important;
            }

            .president-info {
                padding: 10px !important;
                margin-bottom: 10px !important;
            }

            .president-name {
                font-size: 12px !important;
                margin-bottom: 3px !important;
            }

            .president-title {
                font-size: 10px !important;
                margin-bottom: 8px !important;
            }

            .certificate-date {
                font-size: 10px !important;
                padding: 5px 8px !important;
            }

            .footer {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                padding: 10px !important;
                margin-top: 10px !important;
            }

            .footer-title {
                font-size: 12px !important;
                margin-bottom: 5px !important;
            }

            .footer-content {
                font-size: 9px !important;
                line-height: 1.3 !important;
            }
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 30px;
            background: #f1f5f9;
            color: #1e293b;
            line-height: 1.5;
        }

        @media print {
            body {
                padding: 15px;
                background: white;
            }
        }

        .certificate-container {
            background: white;
            max-width: 850px;
            margin: 0 auto;
            padding: 0;
            border: 3px solid #0d9488;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }

        .certificate-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #0d9488, #14b8a6, #0d9488);
        }

        .header {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            color: white;
            text-align: center;
            padding: 40px 50px;
            border-bottom: 4px solid #0f766e;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #ffffff, transparent);
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            order: 1;
            margin-bottom: 15px;
        }

        .logo {
            max-height: 100px;
            max-width: 100px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        .logo-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .header-text {
            order: 2;
        }

        .organization-name {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: 1.5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .organization-subtitle {
            font-size: 15px;
            opacity: 0.95;
            margin-bottom: 0;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .certificate-body {
            padding: 50px;
            text-align: center;
            background: white;
            position: relative;
        }

        .certificate-body::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 2px solid #e2e8f0;
            border-radius: 4px;
            pointer-events: none;
        }

        .certificate-title {
            font-size: 24px;
            font-weight: 700;
            color: #0d9488;
            margin-bottom: 35px;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 3px solid #0d9488;
            padding-bottom: 15px;
            display: inline-block;
            position: relative;
        }

        .certificate-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #14b8a6;
        }

        .certificate-text {
            font-size: 17px;
            line-height: 1.8;
            color: #1e293b;
            margin-bottom: 25px;
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
            font-weight: 600;
            text-align: justify;
        }

        .certificate-text-german {
            font-size: 16px;
            line-height: 1.7;
            color: #475569;
            margin-bottom: 35px;
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
            font-style: italic;
            font-weight: 500;
            text-align: justify;
        }

        .member-info {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 3px solid #0d9488;
            border-radius: 12px;
            padding: 40px;
            margin: 40px 0;
            box-shadow: 0 8px 25px rgba(13, 148, 136, 0.15);
            position: relative;
        }

        .member-info::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, #0d9488, #14b8a6, #0d9488);
            border-radius: 12px;
            z-index: -1;
        }

        .member-name {
            font-size: 22px;
            font-weight: 700;
            color: #0d9488;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 2px solid #14b8a6;
            padding-bottom: 15px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .member-birth-info {
            margin-bottom: 25px;
            padding: 20px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .birth-detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .birth-detail:last-child {
            margin-bottom: 0;
        }

        .birth-label {
            font-size: 14px;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .birth-value {
            font-size: 15px;
            color: #1e293b;
            font-weight: 700;
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .member-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .member-detail {
            text-align: left;
        }

        .member-detail-label {
            font-size: 13px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .member-detail-value {
            font-size: 18px;
            color: #0d9488;
            font-weight: 700;
            padding: 12px 16px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border: 2px solid #14b8a6;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .certificate-footer {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 3px solid #0d9488;
            position: relative;
        }

        .certificate-footer::before {
            content: '';
            position: absolute;
            top: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #0d9488, #14b8a6, #0d9488);
        }

        .president-info {
            text-align: center;
            min-width: 350px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 25px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .certificate-date {
            font-size: 13px;
            color: #475569;
            font-weight: 600;
            margin-top: 15px;
            background: #0d9488;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
        }

        .president-name {
            font-size: 18px;
            font-weight: 700;
            color: #0d9488;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .president-title {
            font-size: 13px;
            color: #475569;
            margin-bottom: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .signature-line {
            border-top: 3px solid #0d9488;
            width: 200px;
            margin: 0 auto 10px;
            position: relative;
        }

        .signature-line::after {
            content: '';
            position: absolute;
            top: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 2px;
            background: #14b8a6;
        }

        .footer {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            border-top: 4px solid #0f766e;
            padding: 25px 50px;
            text-align: center;
            color: white;
        }

        .footer-section {
            text-align: center;
        }

        .footer-title {
            font-size: 15px;
            font-weight: 700;
            color: white;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .footer-content {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.7;
            font-weight: 500;
        }


    </style>
</head>
<body>
    <div class="certificate-container">

        <!-- Header -->
        <div class="header">
            <div class="logo-container">
                @if($settings['logo'])
                    <!-- Debug: Logo path: {{ $settings['logo'] }} -->
                    <img src="{{ url('storage/' . $settings['logo']) }}" alt="Logo" class="logo" onerror="console.log('Logo yüklenemedi: ' + this.src); this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="logo-placeholder" style="display: none;">
                        <i class="fas fa-building" style="font-size: 40px; color: white; opacity: 0.7;"></i>
                        <div style="font-size: 12px; color: white; opacity: 0.7; margin-top: 5px;">LOGO KONNTE NICHT GELADEN WERDEN</div>
                    </div>
                @else
                    <div class="logo-placeholder">
                        <i class="fas fa-building" style="font-size: 40px; color: white; opacity: 0.7;"></i>
                        <div style="font-size: 12px; color: white; opacity: 0.7; margin-top: 5px;">KEIN LOGO</div>
                    </div>
                @endif
            </div>
            <div class="header-text">
                <div class="organization-name">{{ $settings['organization_subtitle'] ?? $settings['organization_name'] }}</div>
                <div class="organization-subtitle">{{ $settings['certificate_title'] ?? 'Offizielle Mitgliedsbescheinigung' }}</div>
            </div>
        </div>

                <!-- Body -->
        <div class="certificate-body">
            <div class="certificate-title">MITGLIEDSBESCHEINIGUNG</div>

            <div class="certificate-text">
                Die unten aufgeführte Person ist Mitglied unseres Vereins <strong>{{ $settings['organization_subtitle'] ?? $settings['organization_name'] }}</strong>.
            </div>

            <div class="member-info">
                <div class="member-name">{{ $member->name }} {{ $member->surname }}</div>

                @if($member->birth_date || $member->birth_place)
                <div class="member-birth-info">
                    @if($member->birth_date)
                    <div class="birth-detail">
                        <span class="birth-label">Geburtsdatum:</span>
                        <span class="birth-value">{{ $member->birth_date->format('d.m.Y') }}</span>
                    </div>
                    @endif
                    @if($member->birth_place)
                    <div class="birth-detail">
                        <span class="birth-label">Geburtsort:</span>
                        <span class="birth-value">{{ $member->birth_place }}</span>
                    </div>
                    @endif
                </div>
                @endif

                <div class="member-details">
                    <div class="member-detail">
                        <div class="member-detail-label">Mitgliedsnummer</div>
                        <div class="member-detail-value">{{ $member->member_no }}</div>
                    </div>
                    <div class="member-detail">
                        <div class="member-detail-label">Beitrittsdatum</div>
                        <div class="member-detail-value">{{ $member->membership_date->format('d.m.Y') }}</div>
                    </div>
                </div>
            </div>

            <div class="certificate-footer">
                <div class="president-info">
                    <div class="president-name">{{ $settings['pdf_president_name'] ?? $settings['president_name'] ?? 'Vorsitzender Name' }}</div>
                    <div class="president-title">{{ $settings['president_title'] ?? 'Vorsitzender' }}</div>
                    <div class="signature-line"></div>
                    <div class="certificate-date">
                        Ausstellungsdatum: {{ now()->format('d.m.Y') }}
                    </div>
                </div>
            </div>
        </div>

                <!-- Footer -->
        <div class="footer">
            <div class="footer-section">
                <div class="footer-title">Kontaktdaten</div>
                <div class="footer-content">
                    @if($settings['organization_address'])
                        <strong>Anschrift:</strong> {{ $settings['organization_address'] }}<br>
                    @endif
                    @if($settings['organization_phone'])
                        <strong>Telefon:</strong> {{ $settings['organization_phone'] }}<br>
                    @endif
                    @if($settings['organization_email'])
                        <strong>E-Mail:</strong> {{ $settings['organization_email'] }}
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Print Button -->
    <div style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
        <button onclick="window.print()" style="
            background: #0d9488;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
            transition: all 0.3s ease;
        " onmouseover="this.style.background='#0f766e'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#0d9488'; this.style.transform='translateY(0)'">
            <i class="fas fa-print" style="margin-right: 8px;"></i>
            Drucken
        </button>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            .print-button {
                display: none !important;
            }
        }
    </style>

    <script>
        // Print button functionality
        function printCertificate() {
            window.print();
        }

        // Hide print button when printing
        window.addEventListener('beforeprint', function() {
            document.querySelector('button[onclick="window.print()"]').style.display = 'none';
        });

        window.addEventListener('afterprint', function() {
            document.querySelector('button[onclick="window.print()"]').style.display = 'block';
        });
    </script>
</body>
</html>
