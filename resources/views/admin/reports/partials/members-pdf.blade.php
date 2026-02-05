<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üyeler Raporu</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            margin: 0 0 10px 0;
        }

        .header p {
            font-size: 12px;
            color: #666;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #f8fafc;
            color: #374151;
            font-weight: bold;
            padding: 8px 6px;
            text-align: left;
            border: 1px solid #d1d5db;
            font-size: 9px;
        }

        td {
            padding: 6px;
            border: 1px solid #d1d5db;
            font-size: 9px;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tr:hover {
            background-color: #f3f4f6;
        }

        .sira-no {
            text-align: center;
            width: 40px;
        }

        .ad-soyad {
            font-weight: 500;
            min-width: 120px;
        }

        .aidat {
            text-align: right;
            font-weight: 500;
            color: #059669;
        }

        .tarih {
            text-align: center;
            min-width: 80px;
        }

        .adres {
            min-width: 150px;
            max-width: 200px;
            word-wrap: break-word;
        }

        .telefon {
            text-align: center;
            min-width: 100px;
        }

        .meslek {
            min-width: 100px;
        }

        .odeme-yontemi {
            text-align: center;
            min-width: 80px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #d1d5db;
            padding-top: 10px;
        }

        .summary {
            background-color: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .summary h3 {
            font-size: 12px;
            font-weight: bold;
            color: #0369a1;
            margin: 0 0 10px 0;
        }

        .summary p {
            margin: 3px 0;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ \App\Models\Settings::get('organization_name', 'Cami') }} ÜYE BİLGİLERİ</h1>
        <p><strong>Rapor Tarihi:</strong> {{ now()->format('d.m.Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="sira-no">Sıra No</th>
                <th class="ad-soyad">Soyad, Ad</th>
                <th class="aidat">Üyelik Aidatı</th>
                <th class="tarih">Doğum Tarihi</th>
                <th class="adres">Adresi</th>
                <th class="telefon">Telefonu</th>
                <th class="meslek">Mesleği</th>
                <th class="odeme-yontemi">Ödeme Yöntemi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $index => $member)
            <tr>
                <td class="sira-no">{{ $index + 1 }}</td>
                <td class="ad-soyad">{{ $member->surname }} {{ $member->name }}</td>
                <td class="aidat">{{ number_format($member->monthly_dues, 2) }} €</td>
                <td class="tarih">{{ $member->birth_date ? $member->birth_date->format('d.m.Y') : '-' }}</td>
                <td class="adres">{{ $member->address && $member->address !== 'Bilinmiyor' ? $member->address : '-' }}</td>
                <td class="telefon">{{ $member->phone ?? '-' }}</td>
                <td class="meslek">{{ $member->occupation && $member->occupation !== 'Serbest' ? $member->occupation : '-' }}</td>
                <td class="odeme-yontemi">
                    @if($member->payment_method)
                        @switch($member->payment_method)
                            @case('cash')
                                Nakit
                                @break
                            @case('bank_transfer')
                                Banka Transferi
                                @break
                            @case('credit_card')
                                Kredi Kartı
                                @break
                            @case('direct_debit')
                                Otomatik Ödeme
                                @break
                            @case('standing_order')
                                Düzenli Transfer
                                @break
                            @default
                                {{ ucfirst($member->payment_method) }}
                        @endswitch
                    @else
                        Belirtilmemiş
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Bu rapor {{ now()->format('d.m.Y H:i') }} tarihinde otomatik olarak oluşturulmuştur.</p>
        <p>Cami Üyelik Sistemi - Detaylı Üyeler Raporu</p>
    </div>
</body>
</html>
