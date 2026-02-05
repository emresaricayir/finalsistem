@php
    $orgName = \App\Models\Settings::get('organization_name', 'Cami Derneği');
    $orgAddress = \App\Models\Settings::get('organization_address');
    $logoSetting = \App\Models\Settings::get('logo');
    $logoExists = $logoSetting && file_exists(public_path('storage/' . $logoSetting));
    $logoUrl = $logoExists ? asset('storage/' . $logoSetting) : null;
    $time = \App\Models\Settings::get('election_time', '13:00');

    // PDF İmza Bölümü İsimleri
    $secretaryName = \App\Models\Settings::get('pdf_secretary_name', '');
    $accountantName = \App\Models\Settings::get('pdf_accountant_name', '');
    $vicePresidentName = \App\Models\Settings::get('pdf_vice_president_name', '');
    $presidentName = \App\Models\Settings::get('pdf_president_name', '');

    // Verein Bilgileri
    $foundingYear = \App\Models\Settings::get('founding_year', '1987');
    $courtName = \App\Models\Settings::get('court_name', 'Hannover');
    $associationRegister = \App\Models\Settings::get('association_register', 'VR-110340');
    $taxNumber = \App\Models\Settings::get('tax_number', 'Hannover-Land II');
@endphp
<!DOCTYPE html>
<html lang="{{ $language }}">
<head>
    <meta charset="utf-8">
    <title></title>
    <style>
        @media print { .no-print { display: none !important; } .page-break { page-break-after: always; } }
        body { font-family: Arial, Helvetica, 'DejaVu Sans', sans-serif; font-size: 14px; color: #000; margin: 0; line-height: 1.6; }
        .toolbar { position: sticky; top: 0; background: #fff; border-bottom: 1px solid #e5e7eb; padding: 10px; display: flex; gap: 8px; align-items: center; }
        .btn { padding: 8px 12px; border-radius: 8px; border: 1px solid #d1d5db; background: #f9fafb; cursor: pointer; }
        .btn.primary { background: #2563eb; color: #fff; border-color: #2563eb; }
        .container { max-width: 800px; margin: 0 auto; padding: 16px; }
        .letter { padding: 12px 6px; }
        .header { text-align: center; }
        .header img { height: 40px; }
        .org-subtitle { margin-top: 8px; font-size: 11px; font-weight: 600; color: #666; }
        .org-line { margin-top: 6px; font-weight: 700; }
        .hr { border-top: 1px solid #222; margin: 6px 0 16px 0; }
        .title { text-align: center; font-weight: 700; text-decoration: underline; margin: 8px 0 16px 0; font-size: 18px; }
        .date { text-align: right; font-weight: 700; }
        .salute { margin: 18px 0 14px 0; }
        .agenda-title { font-weight: 700; text-decoration: underline; margin: 12px 0 8px 0; }
        ol { margin: 0; padding-left: 18px; }
        ol li { margin-bottom: 6px; line-height: 1.6; }
        .sign-row { display: flex; justify-content: space-between; align-items: flex-end; margin-top: 28px; }
        .sign-left { text-align: left; }
        .sign-right { text-align: right; }
        .sign-right img { height: 60px; }
        .sign-title { font-size: 14px; margin-top: 8px; font-weight: 600; }
    </style>
</head>
<body>
<div class="toolbar no-print">
    <button class="btn primary" onclick="window.print()">Yazdır</button>
    <form method="GET" class="no-print" style="display:inline-flex; gap:8px; align-items:center;">
        <input type="hidden" name="language" value="{{ $language }}">
        <label>Sayfa başına:</label>
        <select name="per_page" onchange="this.form.submit()" class="btn">
            @foreach([100,200,300,400,500] as $pp)
                <option value="{{ $pp }}" {{ request('per_page', $members->perPage()) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
            @endforeach
        </select>
    </form>
    <div class="no-print" style="margin-left:auto; font-size:12px; color:#374151;">Toplam {{ $members->total() }} üye | Sayfa {{ $members->currentPage() }}/{{ $members->lastPage() }}</div>
    @if($members->hasPages())
        <div class="no-print" style="display:flex; gap:6px;">
            @if($members->onFirstPage())
                <span class="btn" style="opacity:.5;">Önceki</span>
            @else
                <a href="{{ $members->previousPageUrl() }}" class="btn">Önceki</a>
            @endif
            @if($members->hasMorePages())
                <a href="{{ $members->nextPageUrl() }}" class="btn">Sonraki</a>
            @else
                <span class="btn" style="opacity:.5;">Sonraki</span>
            @endif
        </div>
    @endif
</div>

<div class="container">
@foreach($members as $idx => $member)
    <div class="letter">
        <div class="header">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="logo">
            @endif
            <div class="org-line">{{ \App\Models\Settings::get('organization_subtitle', 'DITIB Türkisch Islamische Gemeinde zu .... e.V.') }}</div>
            @if($orgAddress)
                <div style="font-size: 11px;">{{ $orgAddress }}</div>
            @endif
            <div style="font-size: 11px; margin-top: 8px; font-weight: 600;">
                Gründ.Jahr: {{ $foundingYear }}, AG.: {{ $courtName }}, {{ $associationRegister }}, FA.: {{ $taxNumber }}
            </div>
        </div>
        <div class="hr"></div>

        <div class="title">{{ $language === 'de' ? $election->title_de : $election->title_tr }}</div>
        <div class="date">{{ $language === 'de' ? 'Datum' : 'Tarih' }}: {{ now()->format('d.m.Y') }}</div>

        <div style="margin-top: 40px;"></div>


        <div style="margin-top: 60px;"></div>

        <div class="content" style="font-size: 15px; line-height: 1.7;">{!! $content !!}</div>

        <div class="sign-row">
            <div class="sign-left">
                @if(!empty($election->president_signature) && file_exists(public_path('storage/elections/' . $election->president_signature)))
                    <img src="{{ asset('storage/elections/' . $election->president_signature) }}" alt="Başkan İmzası" style="height:100px;">
                @elseif(!empty($election->signature_image) && file_exists(public_path('storage/elections/' . $election->signature_image)))
                    <img src="{{ asset('storage/elections/' . $election->signature_image) }}" alt="İmza" style="height:100px;">
                @else
                    @php($presidentSignature = \App\Models\Settings::get('pdf_president_signature'))
                    @if($presidentSignature && file_exists(public_path('storage/' . $presidentSignature)))
                        <img src="{{ asset('storage/' . $presidentSignature) }}" alt="Başkan İmzası" style="height:100px;">
                    @else
                        <div style="margin-bottom: 100px;">
                            <!-- İmza için boşluk -->
                        </div>
                    @endif
                @endif
                <div class="sign-title">{{ $presidentName }}</div>
                <div class="sign-title">{{ $language === 'de' ? 'Vorstandsvorsitzender' : 'Dernek Başkanı' }}</div>
            </div>
            <div class="sign-right">
                @if(!empty($election->secretary_signature) && file_exists(public_path('storage/elections/' . $election->secretary_signature)))
                    <img src="{{ asset('storage/elections/' . $election->secretary_signature) }}" alt="Sekreter İmzası" style="height:100px;">
                @else
                    @php($secretarySignature = \App\Models\Settings::get('pdf_secretary_signature'))
                    @if($secretarySignature && file_exists(public_path('storage/' . $secretarySignature)))
                        <img src="{{ asset('storage/' . $secretarySignature) }}" alt="Sekreter İmzası" style="height:100px;">
                    @else
                        <div style="margin-bottom: 100px;">
                            <!-- İmza için boşluk -->
                        </div>
                    @endif
                @endif
                <div class="sign-title" style="text-align: left;">{{ $secretaryName }}</div>
                <div class="sign-title" style="text-align: left;">{{ $language === 'de' ? 'Sekretär' : 'Sekreter' }}</div>
            </div>
        </div>

        @if($idx < ($members->count()-1))
            <div class="page-break"></div>
        @endif
    </div>
@endforeach
</div>
</body>
</html>
