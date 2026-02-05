<!DOCTYPE html>
<html lang="{{ $language === 'de' ? 'de' : 'tr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ $election->title }} - {{ $language === 'de' ? 'Wahleinladung' : 'Kongreye Davet' }}</title>
    <style>
        @page { margin: 1.5cm; size: A4; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #000; }
        .header { text-align: center; }
        .header img { height: 40px; }
        .org-subtitle { margin-top: 8px; font-size: 10px; font-weight: 600; color: #666; }
        .org-line { margin-top: 6px; font-size: 12px; font-weight: 700; }
        .hr { border-top: 1px solid #222; margin: 6px 0 18px 0; }
        .title { text-align: center; font-weight: 700; text-decoration: underline; margin: 35px 0 18px 0; }
        .date { text-align: right; font-weight: 700; }
        .content { margin: 24px 0; }
        .sign-row { display: flex; justify-content: space-between; align-items: flex-end; margin-top: 28px; }
        .sign-left { text-align: left; }
        .sign-right { text-align: right; }
        .sign-right img { height: 50px; }
        .sign-title { font-size: 11px; margin-top: 6px; }
    </style>
</head>
<body>
    <div class="header">
        @if(\App\Models\Settings::hasLogo())
            <img src="{{ public_path('storage/' . \App\Models\Settings::get('logo')) }}" alt="logo">
        @endif
        @php($orgSubtitle = \App\Models\Settings::get('organization_subtitle'))
        @if($orgSubtitle)
            <div class="org-subtitle">{{ $orgSubtitle }}</div>
        @endif
        <div class="org-line">{{ \App\Models\Settings::get('organization_name', 'DITIB Türkisch Islamische Gemeinde zu ......e.V.') }}</div>
    </div>
    <div class="hr"></div>

    <div class="title">{{ $language === 'de' ? $election->title_de : $election->title_tr }}</div>
    <div class="date">{{ $language === 'de' ? 'Datum' : 'Tarih' }}: {{ now()->format('d.m.Y') }}</div>

    <div class="content">{!! $content !!}</div>

    @php
        $presidentName = \App\Models\Settings::get('pdf_president_name', '');
        $secretaryName = \App\Models\Settings::get('pdf_secretary_name', '');
    @endphp
    <div class="sign-row">
        <div class="sign-left">
            @if($election->hasSignature())
                <img src="{{ public_path('storage/elections/' . $election->signature_image) }}" alt="imza">
            @endif
            <div class="sign-title">{{ $language === 'de' ? 'Vorsitzender' : 'Dernek Başkanı' }}</div>
            <div class="sign-title">{{ $presidentName }}</div>
        </div>
        <div class="sign-right">
            <div style="height:50px;"></div>
            <div class="sign-title">{{ $language === 'de' ? 'Sekretär' : 'Sekreter' }}</div>
            <div class="sign-title">{{ $secretaryName }}</div>
        </div>
    </div>
</body>
</html>
