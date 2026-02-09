{{-- Admin Paneli Üye Belgesi --}}
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Mitgliedschaftsantrag - {{ $member->member_no }}</title>

    <style>
        /* ====== Base ====== */
        :root{
            --brand:#0f766e;
            --brand-600:#0d5a52;
            --mint:#10b981;
            --text:#1f2937;
            --muted:#6b7280;
            --bg:#f3f6fa;
            --card:#ffffff;
        }

        html,body{
            height:100%;
        }

                 body{
             font-family: 'Segoe UI', Tahoma, sans-serif;
             margin:0;
             padding:8px;
             background:var(--bg);
             color:var(--text);
             font-size:11.5px;
             -webkit-print-color-adjust: exact !important;
             print-color-adjust: exact !important;
         }

         .application-container{
             max-width:880px;
             margin:0 auto;
             background:var(--card);
             padding:16px;
             border-radius:12px;
             box-shadow:0 8px 18px rgba(0,0,0,.07);
         }

                 /* ====== Header ====== */
         .header{
             border-bottom:2px solid var(--brand);
             padding-bottom:8px;
             margin-bottom:12px;
             text-align:center;
         }

         .header-top{
             display:flex;
             align-items:center;
             justify-content:center;
             gap:10px;
             margin-bottom:4px;
         }

         .logo{
             width:50px;height:50px;object-fit:contain;flex:0 0 50px;
         }

         .organization-name{
             font-weight:800;
             font-size:16px;
             color:var(--brand);
             letter-spacing:.1px;
         }

         .application-title{
             font-size:11px;
             color:#4b5563;
         }

                          /* ====== Sections ====== */
         .section{
             margin-bottom:10px;
             padding:8px;
             border-radius:6px;
             border-left:2px solid transparent;
         }

         /* Bölüm Renkleri */
         .section-personal{
             background:#fef3e2;
             border-left-color:#f59e0b;
         }

         .section-contact{
             background:#e0f2fe;
             border-left-color:#0284c7;
         }

         .section-membership{
             background:#f0fdf4;
             border-left-color:#16a34a;
         }

         .section-bank{
             background:#f3e8ff;
             border-left-color:#9333ea;
         }

                 .section-title{
             font-size:12px;
             font-weight:700;
             color:var(--brand);
             border-left:3px solid var(--brand);
             padding-left:6px;
             margin:0 0 8px;
             line-height:1.2;
         }

        .two-col{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:12px;
            margin-bottom:8px;
        }

                 .info-grid{
             display:grid;
             grid-template-columns: 1fr 1.7fr;
             gap:0px 10px;
         }

         .info-row{
             display:contents;
         }

         .info-row:not(:last-child) .info-label,
         .info-row:not(:last-child) .info-value {
             border-bottom: 1px solid rgba(0,0,0,0.08);
             padding-bottom: 5px;
             margin-bottom: 5px;
         }

         .info-label{
             font-weight:600;
             color:#4b5563;
             font-size:10.5px;
             padding-top: 5px;
         }

         .info-value{
             color:#111827;
             font-size:10.5px;
             padding-top: 5px;
         }

                 /* ====== Digital Signature ====== */
         .digital-signature{
             background:#f8fdfb;
             border:1px solid var(--mint);
             border-radius:6px;
             padding:8px;
         }
         .declaration-text p{
             margin:3px 0;
             font-size:9.5px;
             line-height:1.3;
         }
        .declaration-text strong{ color:#047857; }
        .declaration-text em{ color:#6b7280; }

        .signature-image{ text-align:center; margin-top:10px; }
        .digital-signature img{
            max-width:180px; max-height:50px;
            border:1px solid #059669;
            background:#fff; border-radius:6px; padding:6px;
        }
        .signature-date-digital{
            font-size:11px; color:#047857; font-weight:700; margin-top:6px;
        }

                 /* ====== Approval Signatures ====== */
         .signature-section{
             background:#f0fdfa;
             border:1px solid var(--brand);
             border-radius:6px;
             padding:12px;
             margin-top:10px;
             page-break-inside: avoid;
         }
         .signature-title{
             font-size:10.5px; font-weight:800; color:var(--brand);
             text-align:center; margin-bottom:3px;
         }
         .signature-subtitle{
             font-size:8.5px; color:#6b7280; text-align:center; margin-bottom:8px;
         }
         .signature-grid{
             display:grid;
             grid-template-columns: repeat(4, 1fr);
             gap:12px;
         }
         .signature-item{ text-align:center; }
         .signature-name{
             font-size:8.5px; color:var(--brand); line-height:1.25;
         }
         .signature-box{
             width:100%; height:28px; background:#fff;
             border:1px dashed var(--brand); border-radius:4px; margin:5px 0;
         }
         .signature-date{ font-size:7.5px; color:#6b7280; }

                 /* ====== Footer ====== */
         .footer{
             margin-top:10px; padding:8px;
             border-top:1px solid var(--brand);
             text-align:center; font-size:8.5px; color:#4b5563;
             background:#f8fafc; border-radius:4px;
         }
        .footer strong{ color:var(--brand); }

        /* ====== Buttons ====== */
        .print-buttons{
            position:fixed; top:14px; right:14px;
            display:flex; gap:8px; z-index:1000;
        }
        .print-btn{
            padding:9px 16px; border:none; border-radius:8px;
            font-weight:700; cursor:pointer; font-size:12.5px;
            transition:transform .08s ease, background .2s ease, box-shadow .2s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
            background:var(--brand); color:#fff;
        }
        .print-btn:hover{ background:var(--brand-600); transform:translateY(-1px); }

                 /* ====== Print Optimizations ====== */
         @page{
             size: A4 portrait;
             margin: 0.8cm;
         }
                             @media print{
              body{ background:#fff; padding:0; margin:0; }
              .application-container{
                  box-shadow:none;
                  border-radius:0;
                  padding:8px;
                  margin:0;
                  max-width:none;
              }
              .print-buttons{ display:none; }
              .two-col{ gap:8px; }
              .section{ break-inside: avoid; margin-bottom:6px; padding:6px; }
              .header{ margin-bottom:8px; padding-bottom:6px; }
              .footer{ margin-top:6px; padding:4px; }
              .signature-section{ margin-top:8px; padding:10px; }
              .signature-box{ height:55px !important; margin:6px 0 !important; }

              /* Print renkleri - hafif tonda */
              .section-personal{ background:#fefbf3 !important; }
              .section-contact{ background:#f7fcff !important; }
              .section-membership{ background:#f8fffe !important; }
              .section-bank{ background:#fdfaff !important; }

              /* Print çizgileri */
              .info-row:not(:last-child) .info-label,
              .info-row:not(:last-child) .info-value {
                  border-bottom: 0.5px solid rgba(0,0,0,0.12) !important;
              }
          }

        /* ====== Small screens ====== */
        @media (max-width: 720px){
            .two-col{ grid-template-columns:1fr; }
            .info-grid{ grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- Floating Buttons -->
    <div class="print-buttons">
        <button class="print-btn" onclick="window.print()">🖨️ Drucken</button>
        <a href="{{ route('admin.dashboard') }}" class="print-btn" style="background:#0ea5e9">🏠 Startseite</a>
    </div>

    <div class="application-container">
        <!-- Header -->
        <div class="header">
            <div class="header-top" style="flex-direction: column; align-items: center; gap: 8px;">
                @if(\App\Models\Settings::hasLogo())
                    <img src="{{ asset('storage/' . \App\Models\Settings::get('logo')) }}" class="logo" alt="Logo">
                @endif
                <div style="text-align: center;">
                    <div class="organization-name">{{ $settings['organization_subtitle'] ?? $settings['organization_name'] }}</div>
                    <div class="application-title">Mitgliedschaftsantrag</div>
                </div>
            </div>
        </div>

                 <!-- Kişisel + İletişim (Yan Yana) -->
         <div class="two-col" style="margin-bottom:6px;">
             <!-- Kişisel Bilgiler -->
             <div class="section section-personal" style="margin-bottom:0;">
                 <div class="section-title">Persönliche Daten</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Name</div>
                        <div class="info-value">{{ $member->name }} {{ $member->surname }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Geburtsdatum</div>
                        <div class="info-value">{{ $member->birth_date ? $member->birth_date->format('d.m.Y') : 'Nicht angegeben' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Geburtsort</div>
                        <div class="info-value">{{ $member->birth_place ?? 'Nicht angegeben' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Staatsangehörigkeit</div>
                        <div class="info-value">{{ $member->nationality ?? 'Nicht angegeben' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Beruf</div>
                        <div class="info-value">{{ $member->occupation ?? 'Nicht angegeben' }}</div>
                    </div>
                </div>
            </div>

                         <!-- İletişim Bilgileri -->
             <div class="section section-contact" style="margin-bottom:0;">
                 <div class="section-title">Kontaktdaten</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">E-Mail</div>
                        <div class="info-value">{{ $member->email }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Telefon</div>
                        <div class="info-value">{{ $member->phone ?? 'Nicht angegeben' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Adresse</div>
                        <div class="info-value">{{ $member->address ?? 'Nicht angegeben' }}</div>
                    </div>
                </div>
            </div>
        </div>

                 <!-- Üyelik + Banka Bilgileri (Yan Yana) -->
         <div class="two-col">
             <!-- Üyelik Bilgileri -->
             <div class="section section-membership" style="margin-bottom:0;">
                 <div class="section-title">Mitgliedschaftsdaten</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Antragsdatum</div>
                        <div class="info-value">
                            @php $appDate = $member->application_date ?? $member->membership_date; @endphp
                            {{ $appDate ? $appDate->format('d.m.Y') : 'Nicht angegeben' }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Monatsbeitrag</div>
                        <div class="info-value">{{ number_format($member->monthly_dues, 2) }} €</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Zahlungsmethode</div>
                        <div class="info-value">
                            @switch($member->payment_method)
                                @case('cash') Bar @break
                                @case('bank_transfer') Banküberweisung @break
                                @case('direct_debit') Lastschrift @break
                                @case('standing_order') Dauerauftrag @break
                                @case('lastschrift_monthly') Lastschrift (Monatlich) @break
                                @case('lastschrift_semi_annual') Lastschrift (Halbjährlich) @break
                                @case('lastschrift_annual') Lastschrift (Jährlich) @break
                                @default Nicht angegeben
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>

                         <!-- Banka Bilgileri - Sadece Lastschrift için göster -->
             @if(in_array($member->payment_method, ['lastschrift_monthly', 'lastschrift_semi_annual', 'lastschrift_annual', 'direct_debit']))
             <div class="section section-bank" style="margin-bottom:0;">
                 <div class="section-title">Bankdaten</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Kontoinhaber</div>
                        <div class="info-value">{{ $member->account_holder ?? 'Nicht angegeben' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Bankname</div>
                        <div class="info-value">{{ $member->bank_name ?? 'Nicht angegeben' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">IBAN</div>
                        <div class="info-value">{{ $member->iban ?? 'Nicht angegeben' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">BIC</div>
                        <div class="info-value">{{ $member->bic ?? 'Nicht angegeben' }}</div>
                    </div>
                    @if($member->mandate_number)
                    <div class="info-row">
                        <div class="info-label">Mandatsreferenz</div>
                        <div class="info-value">{{ $member->mandate_number }}</div>
                    </div>
                    @endif
                    @if($member->payment_due_date)
                    <div class="info-row">
                        <div class="info-label">Zahlungstermin</div>
                        <div class="info-value">{{ $member->payment_due_date->format('d.m.Y') }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- SEPA Onay -->
        @if($member->sepa_agreement)
        <div class="section">
            <div class="section-title">SEPA-Lastschriftmandat Zustimmung</div>
            <div class="digital-signature">
                <div class="declaration-text">
                    <p><strong>SEPA-Lastschriftmandat:</strong> Ich habe den obigen SEPA-Lastschriftmandat Text gelesen, verstanden und akzeptiere ihn. Ich erteile die Ermächtigung zum Einzug von Forderungen.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Beyan ve Dijital İmza -->
        @if($member->signature)
        <div class="section">
            <div class="section-title">Erklärung und Digitale Unterschrift</div>
            <div class="digital-signature">
                <div class="declaration-text">
                    <p>Ich habe die Satzung Ihrer Gemeinde gelesen und erkläre hiermit mein Einverständnis zu den darin befindlichen Beschlüssen des Vorstandes und verpflichte mich den Mitgliedsbeitrag zu zahlen und beantrage somit die Mitgliedschaft. Des Weiteren versichere ich, dass ich weder im In- noch im Ausland vorbestraft bin.</p>
                </div>
                <div class="signature-image">
                    <img src="{{ $member->signature }}" alt="Digitale Unterschrift" />
                    <div class="signature-date-digital">{{ $member->signature_date ? $member->signature_date->format('d.m.Y H:i') : 'Nicht angegeben' }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Onay İmzaları - Sadece Admin Panelinde Göster -->
        @if(auth()->check() && auth()->user()->hasAnyRole(['super_admin', 'admin', 'accountant']))
        <div class="signature-section">
            <div class="signature-title">
                Ihre Mitgliedschaft wurde durch Beschluss unseres Vorstands genehmigt
                @php
                    $approvalDate = $member->approved_at ?? $member->membership_date;
                @endphp
                @if($approvalDate)
                    <span style="font-size:10px; font-weight:600; color:#6b7280; display:block; margin-top:4px;">
                        Genehmigungsdatum: {{ $approvalDate->format('d.m.Y') }}
                    </span>
                @endif
            </div>
            
            @if($member->privacy_consent && $member->privacy_consent_date)
            <div style="margin-top:15px; padding:10px; background-color:#f0f9ff; border-left:3px solid #0d9488; border-radius:4px;">
                <div style="font-size:11px; line-height:1.5; color:#1f2937;">
                    <strong style="color:#0d9488;">🔒 Datenverarbeitungszustimmung:</strong><br>
                    <span style="margin-top:5px; display:block;">
                        Die Zustimmung zur Verarbeitung personenbezogener Daten wurde erteilt. Zustimmungsdatum: {{ $member->privacy_consent_date->format('d.m.Y') }}
                    </span>
                </div>
            </div>
            @endif
            
            <div class="signature-title" style="margin-top:15px;">Bestätigungsunterschriften</div>

            <div class="signature-grid">
                <div class="signature-item">
                    <div class="signature-name">
                        Sekretär<br>
                        <strong>{{ $settings['pdf_secretary_name'] ?? 'Sekretär Name' }}</strong>
                    </div>
                    <div class="signature-box"></div>
                    <div class="signature-date">Datum und Unterschrift</div>
                </div>
                <div class="signature-item">
                    <div class="signature-name">
                        Kassenwart<br>
                        <strong>{{ $settings['pdf_accountant_name'] ?? 'Kassenwart Name' }}</strong>
                    </div>
                    <div class="signature-box"></div>
                    <div class="signature-date">Datum und Unterschrift</div>
                </div>
                <div class="signature-item">
                    <div class="signature-name">
                        Stellvertretender Vorsitzender<br>
                        <strong>{{ $settings['pdf_vice_president_name'] ?? 'Stellvertretender Vorsitzender Name' }}</strong>
                    </div>
                    <div class="signature-box"></div>
                    <div class="signature-date">Datum und Unterschrift</div>
                </div>
                <div class="signature-item">
                    <div class="signature-name">
                        Vereinsvorsitzender<br>
                        <strong>{{ $settings['pdf_president_name'] ?? 'Vereinsvorsitzender Name' }}</strong>
                    </div>
                    <div class="signature-box"></div>
                    <div class="signature-date">Datum und Unterschrift</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div style="margin-bottom:5px;"><strong>{{ $settings['organization_name'] }}</strong></div>
            <div>
                @if($settings['organization_address'])
                    <strong>Anschrift:</strong> {{ $settings['organization_address'] }}
                @endif
                @if($settings['organization_phone'])
                    | <strong>Tel:</strong> {{ $settings['organization_phone'] }}
                @endif
                @if($settings['organization_email'])
                    | <strong>E-Mail:</strong> {{ $settings['organization_email'] }}
                @endif
            </div>
        </div>
    </div>

    <script>
        // Load html2pdf if missing
        window.addEventListener('load', function(){
            if (typeof html2pdf === 'undefined'){
                const s = document.createElement('script');
                s.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
                s.referrerPolicy = 'no-referrer';
                document.head.appendChild(s);
            }
        });
    </script>
</body>
</html>
