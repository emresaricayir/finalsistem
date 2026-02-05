<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Üye Etiketleri</title>
    <style>
        @page {
            margin: 0;
            size: A4;
        }

        .page-break {
            page-break-before: always;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }

        .label-table {
            border-collapse: collapse;
            margin-left: 4.5mm;   /* sol boşluk */
            margin-top: 12.7mm;   /* üst boşluk */
        }

        .label {
            width: 63.5mm;
            height: 38.1mm;
            padding: 2mm;
            box-sizing: border-box;
            text-align: left;
            vertical-align: middle;
            border: 1px solid #000;
        }

        .label-header {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 1mm;
        }

        .label-address {
            font-size: 12px;
        }

        td {
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>
    @php
        $totalMembers = $members->count();
        $labelsPerPage = 24; // 3x8 = 24 etiket per sayfa
        $totalPages = ceil($totalMembers / $labelsPerPage);
    @endphp

    <!-- Sayfa Bilgisi -->
    @if($totalPages > 1)
        <div style="position: fixed; top: 5mm; right: 5mm; font-size: 10px; color: #666; z-index: 1000;">
            Toplam {{ $totalMembers }} üye - {{ $totalPages }} sayfa
        </div>
    @endif

    @for($page = 0; $page < $totalPages; $page++)
        <table class="label-table" style="{{ $page > 0 ? 'page-break-before: always;' : '' }}">
            @if($totalPages > 1)
                <div style="position: absolute; bottom: 5mm; left: 5mm; font-size: 10px; color: #666;">
                    Sayfa {{ $page + 1 }} / {{ $totalPages }}
                </div>
            @endif
            @for($row = 0; $row < 8; $row++)
                <tr>
                    @for($col = 0; $col < 3; $col++)
                        @php
                            $index = $page * $labelsPerPage + ($row * 3 + $col);
                            $member = $members[$index] ?? null;
                        @endphp

                        @if($member)
                            <td class="label">
                                <div class="label-header">
                                    {{ $member->name }} {{ $member->surname }}
                                </div>
                                <div class="label-address">
                                    @if($member->address)
                                        {{ $member->address }}
                                    @else
                                        Adres belirtilmemiş
                                    @endif
                                </div>
                            </td>
                        @else
                            <td class="label" style="border: 1px dashed #ddd; color: #ccc;">
                                <div class="label-header">Boş</div>
                                <div class="label-address">Etiket</div>
                            </td>
                        @endif
                    @endfor
                </tr>
            @endfor
        </table>
    @endfor
</body>
</html>
