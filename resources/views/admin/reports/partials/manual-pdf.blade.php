<html>
<head>
    <meta charset="utf-8">
    <style>
        /* Dompdf, DejaVu Sans gömülü gelir; harici font dosyasına gerek yok */
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 3px 4px; }
        th { background: #f3f4f6; text-align: left; font-weight: 600; }
        tbody tr:nth-child(even) { background: #f7f7f7; }
        tbody tr:nth-child(odd) { background: #ffffff; }
        .right { text-align: right; }
        .center { text-align: center; }
        .muted { color: #666; }
        .wrap { white-space: nowrap; }
        .nowrap { white-space: nowrap; }
        .title { font-size: 14px; font-weight: bold; margin-bottom: 6px; }
        .sub { color: #555; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="title">Elden Takip Şablonu</div>
    <div class="sub">Tarih Aralığı: {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}</div>

    @php $perPage = 26; $groups = $members instanceof \Illuminate\Support\Collection ? $members->chunk($perPage) : collect([$members]); @endphp
    @foreach($groups as $gIndex => $group)
        <table>
            <thead>
            <tr>
                <th class="center" style="width:18px">#</th>
                <th class="nowrap" style="width:160px">Üye</th>
                <th class="right" style="width:55px">Aidat (€)</th>
                @foreach($months as $m)
                    @php $short = mb_substr($m['label'], 0, strpos($m['label'],' ')) ?: $m['label']; @endphp
                    <th class="center" style="width:42px">{{ $short }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($group as $member)
                <tr>
                    <td class="center">{{ ($gIndex * $perPage) + $loop->iteration }}</td>
                    <td class="nowrap">{{ $member->surname }} {{ $member->name }}</td>
                    <td class="right">{{ number_format($member->monthly_dues ?? 0, 2) }} €</td>
                    @foreach($months as $m)
                        <td class="center">&nbsp;</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
</body>
</html>


