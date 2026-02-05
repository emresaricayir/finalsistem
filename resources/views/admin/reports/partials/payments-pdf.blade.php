<html>
<head>
    <meta charset="utf-8">
    <style>
        /* Dompdf, DejaVu Sans gömülü gelir; harici font dosyasına gerek yok */
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
        .brand { font-size: 14px; font-weight: bold; }
        .report-date { font-size: 9px; color: #666; text-align: right; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 6px; }
        .sub { color: #555; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #ddd; padding: 4px 6px; }
        th { background: #f3f4f6; text-align: left; font-weight: 600; }
        .number-col { width: 3%; }
        .member-col { width: 15%; }
        .amount-col { width: 8%; }
        .month-col { width: 4%; }
        .right { text-align: right; }
        .center { text-align: center; }
        .muted { color: #666; }
        .wrap { white-space: nowrap; }
        .paid { font-weight: 800; font-size: 12px; font-family: Arial, sans-serif; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">
            {{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }}
        </div>
        <div class="report-date">
            Rapor Tarihi: {{ \Carbon\Carbon::now()->format('d.m.Y H:i') }}
        </div>
    </div>
    <div class="title">
        Aidat Ödeme Raporu
        @if($paymentMethod)
            @php
                $paymentMethodNames = [
                    'cash' => 'Nakit',
                    'bank_transfer' => 'Banka Havalesi',
                    'lastschrift' => 'Lastschrift',
                    'sepa' => 'SEPA',
                    'credit_card' => 'Kredi Kartı'
                ];
                $methodName = $paymentMethodNames[$paymentMethod] ?? ucfirst($paymentMethod);
            @endphp
            - {{ $methodName }}
        @endif
    </div>
    <div class="sub">Tarih Aralığı: {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}</div>

    @php $perPage = 20; $rows = collect($data['monthly_payments'] ?? []); $groups = $rows->chunk($perPage); $rowNo = 1; @endphp
    @foreach($groups as $gIndex => $group)
    <table>
        <thead>
            <tr>
                <th class="center number-col">#</th>
                <th class="member-col">Üye</th>
                <th class="right amount-col">Aylık Aidat</th>
                @foreach($data['months'] as $month)
                    <th class="center month-col">{{ $month['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($group as $row)
                <tr>
                    <td class="center number-col">{{ $rowNo++ }}</td>
                    <td class="member-col">
                        <strong>{{ $row['member']->surname }}, {{ $row['member']->name }}</strong>
                    </td>
                    <td class="right wrap amount-col">{{ number_format($row['member']->monthly_dues, 2) }} €</td>
                    @foreach($data['months'] as $month)
                        @php $cell = $row['monthly_data'][$month['key']] ?? null; @endphp
                        <td class="center month-col">{!! $cell ? '<span class="paid">X</span>' : '-' !!}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 2 + count($data['months']) }}" class="center muted">Seçilen tarih aralığında ödeme bulunamadı.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
    @endforeach

    <br>
    <table>
        <thead>
            <tr>
                <th>Toplam İşlem</th>
                <th>Toplam Tutar</th>
                <th>Ortalama Ödeme</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="center">{{ number_format($data['summary']['total_count']) }}</td>
                <td class="right">{{ number_format($data['summary']['total_amount'], 2) }} €</td>
                <td class="right">{{ number_format($data['summary']['average_payment'], 2) }} €</td>
            </tr>
        </tbody>
    </table>
</body>
</html>

