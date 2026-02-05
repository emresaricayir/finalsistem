<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spendenbescheinigung - {{ $member->full_name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', 'Georgia', serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
        }

        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 1px solid #ddd;
            border-radius: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
        }

        .header {
            background: white;
            color: #333;
            padding: 15px;
            position: relative;
            border-bottom: 2px solid #e0e0e0;
            text-align: center;
        }

        .org-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 2px;
            letter-spacing: 1px;
            color: #333;
        }

        .org-subtitle {
            font-size: 14px;
            color: #666;
            font-style: italic;
        }

        .org-address {
            font-size: 12px;
            color: #666;
            margin-bottom: 40px;
        }

        .logo {
            width: 120px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            background: white;
            color: #333;
            margin: 0 auto 8px auto;
        }

        .document-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #333;
        }

        .legal-reference {
            text-align: left;
            font-size: 13px;
            margin-bottom: 7px;
            line-height: 1.4;
            color: #666;
            font-style: italic;
        }

        .content {
            padding: 15px;
            background: white;
        }

        .section-title {
            font-weight: 600;
            margin-bottom: 7px;
            font-size: 12px;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .donor-section {
            margin-bottom: 15px;
        }

        .donor-box {
            border: 1px solid #ddd;
            border-radius: 0;
            padding: 12px;
            min-height: 30px;
            background: #fafafa;
        }

        .donor-name {
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 16px;
            color: #2d3748;
        }

        .donor-address {
            font-size: 14px;
            color: #718096;
        }

        .donation-section {
            margin-bottom: 15px;
        }

        .donation-amount {
            border: 1px solid #ddd;
            border-radius: 0;
            padding: 15px;
            background: #f5f5f5;
            color: #333;
        }

        .amount-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            align-items: center;
        }

        .amount-row .label {
            font-weight: 500;
            font-size: 12px;
            opacity: 0.9;
        }

        .amount-row .value {
            font-weight: bold;
            font-size: 12px;
        }


        .tax-exemption-section {
            margin-bottom: 15px;
        }

        .exemption-box {
            margin-bottom: 15px;
            font-size: 12px;
            line-height: 1.4;
            padding: 15px;
            background: #fafafa;
            border-radius: 0;
            border-left: 3px solid #999;
        }

        .purpose-statement {
            margin-top: 5px;
            font-size: 12px;
            font-weight: bold;
            padding: 15px;
            background: #f0f0f0;
            border-radius: 0;
            color: #333;
            border: 1px solid #ddd;
        }

        .signature-section {
            margin: 5px 0;
            padding: 12px;
            background: #fafafa;
            border-radius: 0;
            border: 1px solid #ddd;
        }

        .signature-line {
            margin-bottom: 5px;
            font-size: 12px;
            color: #4a5568;
        }

        .legal-notice {
            margin-top: 5px;
            font-size: 10px;
            line-height: 1.2;
            padding: 15px;
            background: #fafafa;
            border-radius: 0;
            border-left: 3px solid #999;
        }

        .notice-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #666;
        }

        .summary-section {
            background: #f8f9fa;
            border: 1px solid #000;
            padding: 20px;
            margin-bottom: 10px;
        }

        .summary-section h3 {
            margin: 0 0 15px 0;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .spenden-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .spenden-item {
            font-size: 12px;
            line-height: 1.4;
        }

        .payments-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .payments-table th {
            background: #4a5568;
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .payments-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        .payments-table tr:hover {
            background-color: #f7fafc;
        }

        .payment-method {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .payment-method.cash {
            background: #c6f6d5;
            color: #22543d;
        }

        .payment-method.bank_transfer {
            background: #bee3f8;
            color: #2a4365;
        }

        .footer {
            background: #f5f5f5;
            color: #333;
            padding: 20px;
            border-top: 2px solid #ddd;
            font-size: 11px;
        }

        .footer-simple {
            text-align: center;
        }

        .footer-title {
            font-weight: 600;
            margin-bottom: 4px;
            font-size: 12px;
            color: #333;
        }

        .footer-address {
            margin-bottom: 4px;
            color: #666;
        }

        .footer-contact {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .footer-contact div {
            margin-bottom: 2px;
            color: #666;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #666;
            color: white;
            border: 1px solid #999;
            padding: 12px 20px;
            border-radius: 0;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
            z-index: 1000;
        }

        .print-button:hover {
            background: #555;
            border-color: #666;
        }

        @media print {
            .print-button, .edit-button {
                display: none;
            }
            
            /* Sayfa kenar boşluklarını en aza indirme */
            @page {
                margin: 1cm;
            }

            body {
                background: white;
                padding: 0;
                /* Yazı tipini biraz küçülterek sığdırma şansını artırma */
                font-size: 12pt; 
            }

            .receipt-container {
                box-shadow: none;
                border-radius: 0;
                border: none !important;
                background: white !important;
                max-width: 100%;
            }
            
            /* İçerik bölümlerinin sayfa ortasında bölünmesini engelleme */
            .donor-section, 
            .donation-section, 
            .tax-exemption-section, 
            .signature-section {
                page-break-inside: avoid;
            }
            
            /* Başlık ve içerik dolgusunu azaltma */
            .content, .header, .footer {
                padding: 8px 10px; 
            }
            
            /* Yasal uyarı ve alt bilgi yazı tipini daha da küçültme */
            .legal-notice, .footer {
                font-size: 10pt;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Drucken
    </button>

    <button class="edit-button" onclick="toggleEdit()" style="position: fixed; top: 20px; right: 140px; background: #28a745; color: white; border: 1px solid #1e7e34; padding: 12px 20px; border-radius: 0; cursor: pointer; font-size: 14px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.2s ease; z-index: 1000;">
        <i class="fas fa-edit"></i> <span id="edit-text">Bearbeiten</span>
    </button>

    <div class="receipt-container" contenteditable="true">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                @if(\App\Models\Settings::hasLogo())
                    <img src="{{ \App\Models\Settings::getLogoUrl() }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                @else
                    DITIB<br>.....................................
                @endif
            </div>


            @if(\App\Models\Settings::get('organization_subtitle'))
            <div class="org-name">{{ \App\Models\Settings::get('organization_subtitle') }}</div>
        @endif

            <div class="org-address">
                {{ \App\Models\Settings::get('organization_address') }}<br>
                <span style="font-size: 11px; color: #666;">
                    Gründ.Jahr: {{ \App\Models\Settings::get('founding_year', '2021') }},
                    AG.: {{ \App\Models\Settings::get('court_name', 'Amtsgericht Stadt') }},
                    {{ \App\Models\Settings::get('association_register', 'VR 1234') }},
                    FA.: {{ \App\Models\Settings::get('tax_number', 'Finanzamt Stadt') }},
                    
                </span>
            </div>



            <div class="document-title">Bestätigung über Geldzuwendung/Mitgliedsbeitrag </div>
            <div class="legal-reference">
                im Sinne des § 10b des Einkommensteuergesetzes an eine der in § 5 Abs. 1 Nr. 9 des Körperschaftsteuer-gesetzes bezeichneten Körperschaften, Personenvereinigungen oder Vermögensmassen.
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Spender Information -->
            <div class="donor-section">
                <div class="section-title">Name und Anschrift des Zuwendenden:</div>
                <div class="donor-box">
                    <div class="donor-name">{{ $member->full_name }}</div>
                    @if($member->address)
                    <div class="donor-address">{{ $member->address }}</div>
                    @endif
                </div>
            </div>

            <!-- Spendenangaben -->
            <div class="donation-section">
                <div class="donation-amount">
                    <div class="amount-row">
                        <span class="label">Wert der Zuwendung - in Ziffern -</span>
                        <span class="value">{{ number_format($totalAmount, 0) }} €</span>
                    </div>
                    <div class="amount-row">
                        <span class="label">- in Buchstaben -</span>
                        <span class="value">{{ ucfirst($amountInWords) }} Euro</span>
                    </div>
                    <div class="amount-row">
                        <span class="label">Jahr der Zuwendung:</span>
                        <span class="value">{{ $payments->last()->payment_date->format('Y') }}</span>
                    </div>
                </div>
            </div>


            <!-- Tax Exemption Section -->
            <div class="tax-exemption-section">
                <div class="exemption-box">
                    <input type="checkbox" checked>
                    <span>Wir sind wegen Förderung <strong>religiöse Zwecke</strong> nach dem letzten uns zugegangenen Freistellungsbescheid des Finanzamtes <strong>{{ \App\Models\Settings::get('tax_number') ?: 'ayarlar kısmından güncelleyin' }}, vom {{ \App\Models\Settings::get('tax_office_date') ? \Carbon\Carbon::parse(\App\Models\Settings::get('tax_office_date'))->format('d.m.Y') : '11.11.2021' }}</strong> nach § 5 Abs. 1 Nr. 9 des Körperschaftsteuergesetzes von der Körperschaftsteuer befreit.</span>
                </div>
                <div class="purpose-statement">
                    Es wird bestätigt, dass die Zuwendung nur zur Förderung <strong>religiöse Zwecke</strong> verwendet wird.
                </div>
            </div>

            <!-- Signature Section -->
            <div class="signature-section">
                <div class="signature-line">
                    <span> {{ \App\Models\Settings::getCityFromAddress() }}, {{ now()->format('d.m.Y') }}</span>
                </div>
                <div class="signature-line">
                    <span>(Ort, Datum und Unterschrift des Zuwendungsempfängers)</span>
                </div>
            </div>

            <!-- Legal Notice -->
            <div class="legal-notice">
                <div class="notice-title">Hinweis:</div>
                <div class="notice-text">
                    Wer vorsätzlich oder grob fahrlässig eine unrichtige Zuwendungsbestätigung erstellt oder veranlasst, dass Zuwendungen nicht zu den in der Zuwendungsbestätigung angegebenen steuerbegünstigten Zwecken verwendet werden, haftet für die entgangene Steuer (§ 10b Abs. 4 EStG, § 9 Abs. 3 KStG, § 9 Nr. 5 GewStG).
                    <br><br>
                    Diese Bestätigung wird nicht als Nachweis für die steuerliche Berücksichtigung der Zuwendung anerkannt, wenn das Datum des Freistellungsbescheides länger als 5 Jahre bzw. das Datum der Feststellung der Einhaltung der satzungsmäßigen Voraussetzungen nach § 60a Abs. 1 AO länger als 3 Jahre seit Ausstellung des Bescheides zurückliegt (§ 63 Abs. 5 AO).
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-simple">
                <div class="footer-title">{{ \App\Models\Settings::get('organization_subtitle') }}</div>
                <div style="display: flex; justify-content: center; align-items: center; gap: 20px; flex-wrap: wrap;">
                    <div class="footer-address">{{ \App\Models\Settings::get('organization_address') }}</div>
                    <div class="footer-contact" style="display: flex; gap: 15px;">
                        @if(\App\Models\Settings::get('organization_phone'))
                            <div>Tel: {{ \App\Models\Settings::get('organization_phone') }}</div>
                        @endif
                        @if(\App\Models\Settings::get('organization_email'))
                            <div>E-Mail: {{ \App\Models\Settings::get('organization_email') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let isEditing = true; // Başlangıçta düzenleme açık

        function toggleEdit() {
            const container = document.querySelector('.receipt-container');
            const editText = document.getElementById('edit-text');
            const editButton = document.querySelector('.edit-button');

            isEditing = !isEditing;

            if (isEditing) {
                container.setAttribute('contenteditable', 'true');
                container.style.border = '2px dashed #28a745';
                container.style.backgroundColor = '#f8fff8';
                editText.textContent = 'Sperren';
                editButton.style.background = '#dc3545';
                editButton.style.borderColor = '#c82333';
            } else {
                container.setAttribute('contenteditable', 'false');
                container.style.border = '1px solid #ddd';
                container.style.backgroundColor = 'white';
                editText.textContent = 'Bearbeiten';
                editButton.style.background = '#28a745';
                editButton.style.borderColor = '#1e7e34';
            }
        }

        // Sayfa yüklendiğinde düzenleme modunu ayarla
        window.onload = function() {
            const container = document.querySelector('.receipt-container');
            container.style.border = '2px dashed #28a745';
            container.style.backgroundColor = '#f8fff8';
        }

        // Print butonuna basıldığında düzenleme modunu kapat
        const printButton = document.querySelector('.print-button');
        const originalPrint = printButton.onclick;
        printButton.onclick = function() {
            // Baskı moduna geçmeden hemen önce düzenleme modunu kapat
            if (isEditing) {
                toggleEdit();
            }
            setTimeout(() => {
                window.print();
            }, 100);
        }
    </script>
</body>
</html>