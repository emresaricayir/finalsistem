<!DOCTYPE html>
<html lang="tr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Filtrelenmiş Üyeler Raporu</title>
    <style>
        @charset "UTF-8";
        * {
            font-family: DejaVu Sans, sans-serif;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            line-height: 1.4;
            margin: 0;
            padding: 15px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
            margin: 0 0 8px 0;
        }

        .header p {
            font-size: 10px;
            color: #666;
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background-color: #f8fafc;
            color: #374151;
            font-weight: bold;
            padding: 6px 4px;
            text-align: left;
            border: 1px solid #d1d5db;
            font-size: 8px;
        }

        td {
            padding: 5px 4px;
            border: 1px solid #d1d5db;
            font-size: 8px;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .sira-no {
            text-align: center;
            width: 30px;
        }

        .uye {
            min-width: 120px;
            font-weight: 500;
        }

        .iletisim {
            min-width: 100px;
        }

        .uyelik-tarihi {
            text-align: center;
            min-width: 70px;
        }

        .aylik-aidat {
            text-align: right;
            font-weight: 500;
            color: #059669;
            min-width: 60px;
        }

        .durum {
            text-align: center;
            min-width: 70px;
        }

        .aidat-durumu {
            text-align: center;
            min-width: 80px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 7px;
            color: #666;
            border-top: 1px solid #d1d5db;
            padding-top: 8px;
        }

        .summary {
            background-color: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 9px;
        }

        .summary h3 {
            font-size: 11px;
            font-weight: bold;
            color: #0369a1;
            margin: 0 0 5px 0;
        }

        .summary p {
            margin: 2px 0;
            font-size: 9px;
        }

        .status-active {
            color: #059669;
            font-weight: 500;
        }

        .status-inactive {
            color: #6b7280;
            font-weight: 500;
        }

        .status-suspended {
            color: #dc2626;
            font-weight: 500;
        }

        .overdue {
            color: #dc2626;
            font-weight: 500;
        }

        .no-overdue {
            color: #059669;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ \App\Models\Settings::get('organization_name', 'Cami') }} FİLTRELENMİŞ ÜYELER RAPORU</h1>
        <p><strong>Rapor Tarihi:</strong> {{ now()->format('d.m.Y H:i') }}</p>
        <p><strong>Toplam Üye Sayısı:</strong> {{ $members->count() }}</p>
        @if(isset($filters) && count($filters) > 0)
            <div style="margin-top: 8px; padding: 8px; background-color: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 4px; text-align: left;">
                <p style="margin: 0 0 4px 0; font-size: 9px; font-weight: bold; color: #0369a1;">Uygulanan Filtreler:</p>
                <p style="margin: 0; font-size: 8px; color: #0c4a6e;">
                    @foreach($filters as $filter)
                        <span style="display: inline-block; margin-right: 8px; margin-bottom: 2px;">• {{ $filter }}</span>
                    @endforeach
                </p>
            </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th class="sira-no">#</th>
                <th class="uye">Üye</th>
                <th class="iletisim">İletişim</th>
                <th class="uyelik-tarihi">Üyelik Tarihi</th>
                <th class="aylik-aidat">Aylık Aidat</th>
                <th class="durum">Durum</th>
                <th class="aidat-durumu">Aidat Durumu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $index => $member)
            <tr>
                <td class="sira-no">{{ $index + 1 }}</td>
                <td class="uye">
                    <strong>{{ $member->surname }}, {{ $member->name }}</strong><br>
                    <span style="font-size: 7px; color: #666;">{{ $member->member_no }}</span>
                </td>
                <td class="iletisim">
                    @if($member->email)
                        <div style="font-size: 7px;">{{ Str::limit($member->email, 25) }}</div>
                    @endif
                    @if($member->phone)
                        <div style="font-size: 7px; color: #666;">{{ $member->phone }}</div>
                    @else
                        <div style="font-size: 7px; color: #999;">Belirtilmemiş</div>
                    @endif
                </td>
                <td class="uyelik-tarihi">
                    {{ $member->membership_date->format('d.m.Y') }}
                </td>
                <td class="aylik-aidat">
                    {{ number_format($member->monthly_dues, 2) }} €
                </td>
                <td class="durum">
                    @if($member->status === 'active')
                        <span class="status-active">Aktif</span>
                    @elseif($member->status === 'inactive')
                        <span class="status-inactive">Pasif</span>
                    @else
                        <span class="status-suspended">Askıya Alınmış</span>
                    @endif
                </td>
                <td class="aidat-durumu">
                    @php
                        $overdueCount = $member->overdue_count;
                    @endphp
                    @if($overdueCount > 0)
                        <span class="overdue">{{ $overdueCount }} gecikmiş</span>
                        @php
                            $mostRecentUnpaid = $member->most_recent_unpaid_due;
                        @endphp
                        @if($mostRecentUnpaid)
                            <div style="font-size: 7px; color: #666;">Son: {{ $mostRecentUnpaid->month }}/{{ $mostRecentUnpaid->year }}</div>
                        @endif
                    @else
                        <span class="no-overdue">Gecikme yok</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Bu rapor {{ now()->format('d.m.Y H:i') }} tarihinde otomatik olarak oluşturulmuştur.</p>
        <p>Cami Üyelik Sistemi - Filtrelenmiş Üyeler Raporu</p>
    </div>
</body>
</html>
