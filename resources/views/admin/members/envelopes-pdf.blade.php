<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarf Yazdır</title>
    <style>
        @page { margin: 0; }
        body { margin: 0; font-family: DejaVu Sans, sans-serif; }
        .envelope {
            width: 220mm;
            height: 110mm;
            position: relative;
            page-break-after: always;
        }
        .sender {
            position: absolute;
            top: 5mm;
            left: 10mm;
            width: 90mm;
            font-size: 10pt;
            line-height: 1.3;
            color: #111;
        }
        .receiver {
            position: absolute;
            top: 45mm;
            left: 90mm;
            width: 110mm;
            font-size: 14pt;
            line-height: 1.4;
            color: #000;
        }
        .org-name { font-weight: bold; font-size: 11pt; }
        .muted { color: #444; font-size: 9pt; }
    </style>
    </head>
<body>
@foreach($members as $member)
    <div class="envelope">
        <div class="sender">
            <div class="org-name">{{ $settings['organization_name'] }}</div>
            @if(!empty($settings['organization_address']))
                @php
                    $orgAddress = trim($settings['organization_address']);

                    // 5 haneli posta kodu ara (virgül olsun ya da olmasın)
                    if (preg_match('/^(.+?)\s+(\d{5})\s+(.+)$/', $orgAddress, $matches)) {
                        $streetLine = trim($matches[1]);
                        $postalCode = $matches[2];
                        $city = trim($matches[3]);
                    } else {
                        $streetLine = $orgAddress;
                        $postalCode = '';
                        $city = '';
                    }
                @endphp

                <div>{{ $streetLine }}</div>
                @if($postalCode && $city)
                    <div>{{ $postalCode }} {{ $city }}</div>
                @endif
            @endif
        </div>

        <div class="receiver">
            <div style="font-weight:bold;">{{ $member->full_name }}</div>
            @if(!empty($member->address))
                @php
                    // Üye adresini parse et - 5 haneli posta kodu ara
                    $address = trim($member->address);

                    // 5 haneli posta kodu ara (virgül olsun ya da olmasın)
                    if (preg_match('/^(.+?)\s+(\d{5})\s+(.+)$/', $address, $matches)) {
                        $streetLine = trim($matches[1]);
                        $postalCode = $matches[2];
                        $city = trim($matches[3]);
                    } else {
                        $streetLine = $address;
                        $postalCode = '';
                        $city = '';
                    }
                @endphp

                <div>{{ $streetLine }}</div>
                @if($postalCode && $city)
                    <div>{{ $postalCode }} {{ $city }}</div>
                @endif
            @else
                <div class="muted">Adres bilgisi yok</div>
            @endif
        </div>
    </div>
@endforeach
</body>
</html>


