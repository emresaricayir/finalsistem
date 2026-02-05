<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrayerTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Carbon\Carbon;

class PrayerTimeController extends Controller
{
    /**
     * Display a listing of prayer times grouped by month
     */
    public function index()
    {
        $prayerTimes = PrayerTime::orderBy('date', 'asc')
            ->orderBy('city', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m');
            });

        // Group by month name for display
        $groupedByMonth = [];
        $months = [
            '01' => 'OCAK', '02' => 'ŞUBAT', '03' => 'MART', '04' => 'NİSAN',
            '05' => 'MAYIS', '06' => 'HAZİRAN', '07' => 'TEMMUZ', '08' => 'AĞUSTOS',
            '09' => 'EYLÜL', '10' => 'EKİM', '11' => 'KASIM', '12' => 'ARALIK'
        ];

        foreach ($prayerTimes as $monthKey => $times) {
            $yearMonth = explode('-', $monthKey);
            $monthName = $months[$yearMonth[1]] ?? $yearMonth[1];
            $groupedByMonth[$monthKey] = [
                'name' => $monthName . ' ' . $yearMonth[0],
                'count' => $times->count(),
                'data' => $times
            ];
        }

        return view('admin.prayer-times.index', compact('groupedByMonth'));
    }

    /**
     * Import prayer times from Excel/CSV file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
            'city' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $city = $request->input('city');
        $region = $request->input('region', 'Niedersachsen');
        $country = $request->input('country', 'ALMANYA');

        $extension = strtolower($file->getClientOriginalExtension());
        
        try {
            if (in_array($extension, ['xlsx', 'xls'])) {
                $result = $this->importFromExcel($file, $city, $region, $country);
            } elseif (in_array($extension, ['csv', 'txt'])) {
                $result = $this->importFromCsv($file, $city, $region, $country);
            } else {
                return redirect()->back()
                    ->with('error', 'Desteklenmeyen dosya formatı. Lütfen Excel (.xlsx, .xls) veya CSV (.csv, .txt) dosyası yükleyin.');
            }

            if ($result['imported'] > 0) {
                $message = "Başarıyla {$result['imported']} kayıt içe aktarıldı!";
                if ($result['skipped'] > 0) {
                    $message .= " ({$result['skipped']} kayıt atlandı. Toplam satır: {$result['total_rows']})";
                }
                
                return redirect()->back()
                    ->with('success', $message)
                    ->with('import_stats', $result);
            } else {
                return redirect()->back()
                    ->with('error', 'Hiçbir kayıt içe aktarılamadı. Lütfen dosya formatını kontrol edin.')
                    ->with('import_stats', $result);
            }
        } catch (\Exception $e) {
            Log::error('Prayer time import error: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Dosya içe aktarılırken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Import from Excel file
     */
    private function importFromExcel($file, $city, $region, $country)
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        
        // Get highest row
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
        
        // awqatsalah.com formatı: İlk satır kolon başlıkları olabilir veya direkt veri olabilir
        // Önce header satırını bulalım (Tarih, İmsak, Güneş vb. içeren satır)
        $headerRowIndex = 1;
        $headerRow = [];
        
        // İlk 5 satırı kontrol et, header satırını bul
        for ($row = 1; $row <= min(5, $highestRow); $row++) {
            $rowData = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                $value = $cell->getFormattedValue();
                if (empty($value) && $cell->getValue() !== null) {
                    $value = $cell->getValue();
                }
                $rowData[] = $value ?? '';
            }
            
            // Bu satır header mı kontrol et (Tarih, İmsak gibi kelimeler içeriyor mu?)
            $rowText = implode(' ', array_map('strtolower', array_filter($rowData, 'is_string')));
            if (preg_match('/\b(tarih|imsak|güneş|gunes|öğle|ogle|ikindi|akşam|aksam|yatsı|yatsi)\b/iu', $rowText)) {
                $headerRow = $rowData;
                $headerRowIndex = $row;
                break;
            }
        }
        
        // Header bulunamadıysa ilk satırı header olarak kullan
        if (empty($headerRow)) {
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cell = $worksheet->getCellByColumnAndRow($col, 1);
                $value = $cell->getFormattedValue();
                if (empty($value) && $cell->getValue() !== null) {
                    $value = $cell->getValue();
                }
                $headerRow[] = $value ?? '';
            }
            $headerRowIndex = 1;
        }
        
        // Read data rows (starting from row after header)
        $rows = [$headerRow]; // First row is header
        
        for ($row = $headerRowIndex + 1; $row <= $highestRow; $row++) {
            $rowData = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                $value = $cell->getValue();
                
                // If cell has a formula, get calculated value
                if ($cell->getDataType() === DataType::TYPE_FORMULA) {
                    $value = $cell->getCalculatedValue();
                }
                
                // For date/time cells, keep numeric value for parsing
                if ($cell->getDataType() === DataType::TYPE_NUMERIC) {
                    $style = $worksheet->getStyleByColumnAndRow($col, $row);
                    $formatCode = $style->getNumberFormat()->getFormatCode();
                    if (preg_match('/[dy]/i', $formatCode) || preg_match('/[hm]/i', $formatCode)) {
                        // Keep as numeric for date/time parsing
                        $value = $cell->getValue();
                    }
                }
                
                $rowData[] = $value;
            }
            $rows[] = $rowData;
        }

        return $this->processRows($rows, $city, $region, $country, true, $headerRowIndex);
    }

    /**
     * Import from CSV file
     */
    private function importFromCsv($file, $city, $region, $country)
    {
        $content = file_get_contents($file->getPathname());
        
        // Try to detect encoding
        $encoding = mb_detect_encoding($content, ['UTF-8', 'Windows-1252', 'ISO-8859-1'], true);
        if ($encoding !== 'UTF-8') {
            $content = mb_convert_encoding($content, 'UTF-8', $encoding);
        }

        $rows = [];
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $content);
        rewind($handle);

        // Skip first 2 rows (header might be there)
        $skipRows = 2;
        $rowNum = 0;
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if ($rowNum < $skipRows) {
                $rowNum++;
                continue;
            }
            if (!empty(array_filter($row))) { // Skip empty rows
                $rows[] = $row;
            }
            $rowNum++;
        }
        fclose($handle);

        return $this->processRows($rows, $city, $region, $country, false);
    }

    /**
     * Process rows and import prayer times
     */
    private function processRows($rows, $city, $region, $country, $isExcel = true, $headerRowIndex = 1)
    {
        if (empty($rows)) {
            return [
                'imported' => 0,
                'skipped' => 0,
                'total_rows' => 0,
                'errors' => ['Dosya boş veya okunamadı.']
            ];
        }

        // Map columns from header row
        $headerRow = $rows[0];
        $columnMap = $this->mapColumns($headerRow);
        
        // Debug: Log column mapping
        Log::info('Column mapping', [
            'header_row' => $headerRow,
            'column_map' => $columnMap,
            'header_row_count' => count($headerRow)
        ]);
        
        // Check if required columns are mapped
        $requiredColumns = ['date', 'imsak', 'gunes', 'ogle', 'ikindi', 'aksam', 'yatsi'];
        $missingColumns = array_diff($requiredColumns, array_keys($columnMap));
        if (!empty($missingColumns)) {
            // Try to show which headers were found
            $foundHeaders = [];
            foreach ($headerRow as $idx => $header) {
                if (!empty($header)) {
                    $foundHeaders[] = "Index $idx: " . json_encode($header);
                }
            }
            
            return [
                'imported' => 0,
                'skipped' => 0,
                'total_rows' => count($rows) - 1,
                'errors' => [
                    'Kolon eşleştirme hatası. Bulunamayan kolonlar: ' . implode(', ', $missingColumns) . '. ' .
                    'Header satırı: ' . json_encode($headerRow, JSON_UNESCAPED_UNICODE) . '. ' .
                    'Bulunan header\'lar: ' . implode(' | ', $foundHeaders)
                ]
            ];
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];
        $totalRows = count($rows) - 1; // Exclude header
        
        // Excel formatında header satırı dinamik olarak bulunuyor
        // CSV formatında ilk 2 satır atlanmış, 1. satır header, 2. satırdan itibaren veri
        $rowOffset = $isExcel ? ($headerRowIndex + 1) : 2; // Excel'de gerçek satır numarası için offset

        // Process each row
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            try {
                $data = $this->parseRow($row, $columnMap, $isExcel, $city, $region, $country);
                
                if ($data === null) {
                    $skipped++;
                    if (count($errors) < 10) {
                        // Get first few values for debugging
                        $rowPreview = array_slice($row, 0, 5);
                        $errors[] = "Satır " . ($i + $rowOffset) . ": Geçersiz veri. Örnek değerler: " . json_encode($rowPreview);
                    }
                    continue;
                }

                // Use updateOrCreate to handle duplicates (date + city)
                PrayerTime::updateOrCreate(
                    [
                        'date' => $data['date'],
                        'city' => $data['city']
                    ],
                    $data
                );

                $imported++;
                Log::info('Prayer time imported', [
                    'date' => $data['date'],
                    'city' => $data['city']
                ]);
            } catch (\Exception $e) {
                $skipped++;
                $errorMsg = "Satır " . ($i + $rowOffset) . ": " . $e->getMessage();
                if (count($errors) < 10) {
                    $errors[] = $errorMsg;
                }
                Log::warning('Prayer time import row error', [
                    'row' => $i + $rowOffset,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'total_rows' => $totalRows,
            'errors' => $errors
        ];
    }

    /**
     * Map column headers to field names
     */
    private function mapColumns($headerRow)
    {
        $map = [];
        $columnMappings = [
            'date' => ['miladi tarih', 'tarih', 'date', 'datum', 'miladi'],
            'hijri_date' => ['hicri tarih', 'hijri date', 'hicri', 'hicri tarih'],
            'day_name' => ['gün', 'day', 'day name', 'tag'],
            'imsak' => ['imsak', 'fajr', 'sabah'],
            'gunes' => ['güneş', 'gunes', 'guneş', 'günes', 'sunrise', 'sun', 'doğuş', 'dogus'],
            'ogle' => ['öğle', 'ogle', 'dhuhr', 'zuhr', 'noon'],
            'ikindi' => ['ikindi', 'asr', 'afternoon'],
            'aksam' => ['akşam', 'aksam', 'maghrib', 'sunset', 'batış'],
            'yatsi' => ['yatsı', 'yatsi', 'isha', 'night', 'gece'],
            'city' => ['şehir', 'sehir', 'city', 'stadt'],
            'region' => ['bölge', 'bolge', 'region'],
            'country' => ['ülke', 'ulke', 'country', 'land']
        ];

        foreach ($headerRow as $index => $header) {
            if (empty($header) && $header !== '0') continue;
            
            // Convert to string and trim
            $header = trim((string)$header);
            if (empty($header)) continue;
            
            // Normalize Turkish characters first, then lowercase
            // This handles Turkish locale issue where İ->ı (not i) when lowercased
            // Apply normalization multiple times to ensure all Turkish chars are replaced
            $normalizedHeader = $this->normalizeTurkishChars($header);
            $normalizedHeader = $this->normalizeTurkishChars($normalizedHeader); // Double pass
            $normalizedHeader = mb_strtolower($normalizedHeader, 'UTF-8');
            
            // Fix encoding issues (try multiple encodings)
            if (!mb_check_encoding($normalizedHeader, 'UTF-8')) {
                $normalizedHeader = mb_convert_encoding($normalizedHeader, 'UTF-8', 'Windows-1252');
                $normalizedHeader = $this->normalizeTurkishChars($normalizedHeader);
                $normalizedHeader = mb_strtolower($normalizedHeader, 'UTF-8');
            }
            
            // Debug: Log the normalization process
            Log::debug('Header normalization', [
                'original' => $header,
                'normalized' => $normalizedHeader,
                'index' => $index
            ]);
            
            // Special handling for "Güneş" column - check if header contains Turkish characters
            $headerLower = mb_strtolower($header, 'UTF-8');
            if (mb_strpos($headerLower, 'güneş') !== false || 
                mb_strpos($headerLower, 'gunes') !== false ||
                mb_strpos($headerLower, 'guneş') !== false ||
                mb_strpos($headerLower, 'günes') !== false ||
                mb_strpos($headerLower, 'sunrise') !== false ||
                mb_strpos($headerLower, 'sun') !== false) {
                $map['gunes'] = $index;
                Log::info('Gunes column matched (special)', [
                    'header' => $header,
                    'normalized_header' => $normalizedHeader,
                    'index' => $index
                ]);
                continue; // Skip to next header
            }
            
            // Try to match this header with all field mappings
            foreach ($columnMappings as $field => $aliases) {
                // Skip if already matched
                if (isset($map[$field])) continue;
                
                foreach ($aliases as $alias) {
                    // Normalize alias
                    $normalizedAlias = $this->normalizeTurkishChars($alias);
                    $normalizedAlias = $this->normalizeTurkishChars($normalizedAlias); // Double pass
                    $normalizedAlias = mb_strtolower($normalizedAlias, 'UTF-8');
                    
                    // Also normalize the original header text directly for comparison
                    $headerNormalized = $this->normalizeTurkishChars($header);
                    $headerNormalized = $this->normalizeTurkishChars($headerNormalized); // Double pass
                    $headerNormalized = mb_strtolower($headerNormalized, 'UTF-8');
                    
                    // Try exact match with normalized header
                    if ($normalizedHeader === $normalizedAlias || $headerNormalized === $normalizedAlias) {
                        $map[$field] = $index;
                        Log::info('Column matched', [
                            'field' => $field,
                            'header' => $header,
                            'normalized_header' => $normalizedHeader,
                            'header_normalized' => $headerNormalized,
                            'alias' => $alias,
                            'normalized_alias' => $normalizedAlias,
                            'index' => $index
                        ]);
                        break 2;
                    }
                    
                    // Try contains match (bidirectional) - more lenient
                    $headerLen = mb_strlen($normalizedHeader, 'UTF-8');
                    $aliasLen = mb_strlen($normalizedAlias, 'UTF-8');
                    
                    // If one is substring of the other (with at least 3 chars match)
                    if ($headerLen >= 3 && $aliasLen >= 3) {
                        if (mb_strpos($normalizedHeader, $normalizedAlias) !== false || 
                            mb_strpos($normalizedAlias, $normalizedHeader) !== false ||
                            mb_strpos($headerNormalized, $normalizedAlias) !== false ||
                            mb_strpos($normalizedAlias, $headerNormalized) !== false) {
                            $map[$field] = $index;
                            Log::info('Column contains match', [
                                'field' => $field,
                                'header' => $header,
                                'normalized_header' => $normalizedHeader,
                                'header_normalized' => $headerNormalized,
                                'alias' => $alias,
                                'normalized_alias' => $normalizedAlias,
                                'index' => $index
                            ]);
                            break 2;
                        }
                    }
                    
                    // Try similarity match - remove all non-alphabetic characters and compare
                    $headerAlpha = preg_replace('/[^a-z]/', '', $normalizedHeader);
                    $aliasAlpha = preg_replace('/[^a-z]/', '', $normalizedAlias);
                    if ($headerAlpha === $aliasAlpha && mb_strlen($headerAlpha, 'UTF-8') >= 3) {
                        $map[$field] = $index;
                        Log::info('Column alpha match', [
                            'field' => $field,
                            'header' => $header,
                            'normalized_header' => $normalizedHeader,
                            'header_alpha' => $headerAlpha,
                            'alias' => $alias,
                            'normalized_alias' => $normalizedAlias,
                            'alias_alpha' => $aliasAlpha,
                            'index' => $index
                        ]);
                        break 2;
                    }
                }
            }
            
            // Debug: Log unmatched headers
            if (!in_array($index, $map)) {
                Log::debug('Column not matched', [
                    'header' => $header,
                    'normalized_header' => $normalizedHeader,
                    'index' => $index,
                    'header_bytes' => bin2hex($header),
                    'normalized_bytes' => bin2hex($normalizedHeader)
                ]);
            }
        }

        return $map;
    }

    /**
     * Parse a single row into prayer time data
     */
    private function parseRow($row, $columnMap, $isExcel, $defaultCity, $defaultRegion, $defaultCountry)
    {
        $getValue = function($field, $default = null) use ($row, $columnMap, $isExcel) {
            if (!isset($columnMap[$field])) {
                return $default;
            }
            $value = $row[$columnMap[$field]] ?? null;
            if ($value === null || $value === '') {
                return $default;
            }
            
            // Excel'den gelen numeric değerleri koru (tarih/saat için)
            if ($isExcel && is_numeric($value)) {
                return $value;
            }
            
            return trim((string)$value);
        };

        // Parse date
        $dateValue = $getValue('date');
        if (empty($dateValue) && $dateValue !== 0 && $dateValue !== '0') {
            return null;
        }
        $date = $this->parseDate($dateValue, $isExcel);
        if ($date === null) {
            Log::debug('Date parse failed', ['value' => $dateValue, 'type' => gettype($dateValue)]);
            return null;
        }

        // Extract day name from date string if available
        $dayName = null;
        if (is_string($dateValue)) {
            $dayName = $this->extractDayName($dateValue);
        }
        if (empty($dayName)) {
            try {
                $dayName = Carbon::parse($date)->locale('tr')->dayName;
            } catch (\Exception $e) {
                $dayName = Carbon::now()->locale('tr')->dayName;
            }
        }

        // Parse times
        $imsak = $this->parseTime($getValue('imsak'), $isExcel);
        $gunes = $this->parseTime($getValue('gunes'), $isExcel);
        $ogle = $this->parseTime($getValue('ogle'), $isExcel);
        $ikindi = $this->parseTime($getValue('ikindi'), $isExcel);
        $aksam = $this->parseTime($getValue('aksam'), $isExcel);
        $yatsi = $this->parseTime($getValue('yatsi'), $isExcel);

        if ($imsak === null || $gunes === null || $ogle === null || 
            $ikindi === null || $aksam === null || $yatsi === null) {
            Log::debug('Time parse failed', [
                'imsak' => $getValue('imsak'),
                'gunes' => $getValue('gunes'),
                'ogle' => $getValue('ogle'),
                'ikindi' => $getValue('ikindi'),
                'aksam' => $getValue('aksam'),
                'yatsi' => $getValue('yatsi'),
            ]);
            return null;
        }

        return [
            'date' => $date,
            'hijri_date' => $getValue('hijri_date'),
            'day_name' => $dayName,
            'imsak' => $imsak,
            'gunes' => $gunes,
            'ogle' => $ogle,
            'ikindi' => $ikindi,
            'aksam' => $aksam,
            'yatsi' => $yatsi,
            'city' => $getValue('city', $defaultCity),
            'region' => $getValue('region', $defaultRegion),
            'country' => $getValue('country', $defaultCountry),
        ];
    }

    /**
     * Parse date from various formats
     */
    private function parseDate($value, $isExcel = false)
    {
        try {
            // Excel serial date number (numeric value > 1 typically means date)
            if ($isExcel && is_numeric($value)) {
                // Excel dates are typically > 1 (serial number starting from 1900-01-01)
                // Times are typically 0-1 (fraction of day)
                if ($value > 1) {
                    try {
                        $date = Date::excelToDateTimeObject($value);
                        return $date->format('Y-m-d');
                    } catch (\Exception $e) {
                        Log::warning('Excel date parse error', ['value' => $value, 'error' => $e->getMessage()]);
                    }
                }
            }

            // Convert to string and clean
            $value = (string)$value;
            
            // Fix encoding issues
            $value = mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
            
            // Remove day names (Pazar, Pazartesi, etc.)
            $dayNames = ['pazar', 'pazartesi', 'salı', 'salı', 'çarşamba', 'perşembe', 'cuma', 'cumartesi'];
            foreach ($dayNames as $dayName) {
                $value = preg_replace('/\b' . preg_quote($dayName, '/') . '\b/iu', '', $value);
            }
            $value = trim($value);

            // Turkish month names mapping
            $months = [
                'ocak' => '01', 'şubat' => '02', 'subat' => '02', 'şub' => '02', 'sub' => '02',
                'mart' => '03', 'nisan' => '04', 'mayıs' => '05', 'mayis' => '05',
                'haziran' => '06', 'temmuz' => '07', 'ağustos' => '08', 'agustos' => '08',
                'eylül' => '09', 'eylul' => '09', 'ekim' => '10', 'kasım' => '11', 'kasim' => '11',
                'aralık' => '12', 'aralik' => '12'
            ];

            // Try format: "01 Ocak 2026" or "01.01.2026" or "2026-01-01"
            // Format 1: "01 Ocak 2026"
            foreach ($months as $monthName => $monthNum) {
                $pattern = '/(\d{1,2})\s*' . preg_quote($monthName, '/') . '\s*(\d{4})/iu';
                if (preg_match($pattern, $value, $matches)) {
                    $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                    $year = $matches[2];
                    return sprintf('%s-%s-%s', $year, $monthNum, $day);
                }
            }

            // Format 2: "01.01.2026" or "1.1.2026"
            if (preg_match('/(\d{1,2})\.(\d{1,2})\.(\d{4})/', $value, $matches)) {
                $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                $year = $matches[3];
                return sprintf('%s-%s-%s', $year, $month, $day);
            }

            // Format 3: "2026-01-01"
            if (preg_match('/(\d{4})-(\d{1,2})-(\d{1,2})/', $value, $matches)) {
                $year = $matches[1];
                $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                $day = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
                return sprintf('%s-%s-%s', $year, $month, $day);
            }

            // Try Carbon parse as last resort
            try {
                $carbon = Carbon::parse($value);
                return $carbon->format('Y-m-d');
            } catch (\Exception $e) {
                Log::warning('Date parse error', ['value' => $value, 'error' => $e->getMessage()]);
                return null;
            }
        } catch (\Exception $e) {
            Log::warning('Date parse exception', ['value' => $value, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Parse time from various formats
     */
    private function parseTime($value, $isExcel = false)
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            // Excel time format (0-1 decimal or time serial number)
            if ($isExcel && is_numeric($value)) {
                // Excel times can be 0-1 (fraction of day) or sometimes combined with date
                // If value is between 0 and 1, it's pure time
                if ($value >= 0 && $value < 1) {
                    try {
                        $time = Date::excelToDateTimeObject($value);
                        return $time->format('H:i:s');
                    } catch (\Exception $e) {
                        Log::warning('Excel time parse error', ['value' => $value, 'error' => $e->getMessage()]);
                    }
                }
                // If value > 1, it might be date+time combined, extract time part
                elseif ($value > 1) {
                    try {
                        $datetime = Date::excelToDateTimeObject($value);
                        return $datetime->format('H:i:s');
                    } catch (\Exception $e) {
                        Log::warning('Excel datetime parse error', ['value' => $value, 'error' => $e->getMessage()]);
                    }
                }
            }

            // Convert to string
            $value = (string)$value;
            $value = trim($value);

            // Format: "06:19" or "06:19:00"
            if (preg_match('/(\d{1,2}):(\d{2})(?::(\d{2}))?/', $value, $matches)) {
                $hour = (int)$matches[1];
                $minute = (int)$matches[2];
                $second = isset($matches[3]) ? (int)$matches[3] : 0;

                // Validate time range
                if ($hour >= 0 && $hour <= 23 && $minute >= 0 && $minute <= 59 && $second >= 0 && $second <= 59) {
                    return sprintf('%02d:%02d:%02d', $hour, $minute, $second);
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::warning('Time parse error', ['value' => $value, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Extract day name from date string
     */
    private function extractDayName($value)
    {
        $dayNames = [
            'pazar' => 'Pazar',
            'pazartesi' => 'Pazartesi',
            'salı' => 'Salı',
            'salı' => 'Salı',
            'çarşamba' => 'Çarşamba',
            'perşembe' => 'Perşembe',
            'cuma' => 'Cuma',
            'cumartesi' => 'Cumartesi'
        ];

        $valueLower = mb_strtolower($value, 'UTF-8');
        foreach ($dayNames as $key => $name) {
            if (mb_strpos($valueLower, $key) !== false) {
                return $name;
            }
        }

        return null;
    }

    /**
     * Normalize Turkish characters for better matching
     */
    private function normalizeTurkishChars($text)
    {
        // Convert to string
        $text = (string)$text;
        
        // Ensure UTF-8 encoding
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'Windows-1252');
        }
        
        // Direct replacement of all Turkish characters to ASCII equivalents
        // Using str_replace with all Turkish character variations
        $text = str_replace(
            ['İ', 'ı', 'I', 'Ş', 'ş', 'Ğ', 'ğ', 'Ü', 'ü', 'Ö', 'ö', 'Ç', 'ç'],
            ['i', 'i', 'i', 's', 's', 'g', 'g', 'u', 'u', 'o', 'o', 'c', 'c'],
            $text
        );
        
        return $text;
    }

    /**
     * Delete all prayer times
     */
    public function deleteAll(Request $request)
    {
        $count = PrayerTime::count();
        PrayerTime::truncate();

        Log::info('All prayer times deleted', ['count' => $count]);

        return redirect()->back()
            ->with('success', "Tüm namaz vakitleri silindi. ({$count} kayıt)");
    }
}
