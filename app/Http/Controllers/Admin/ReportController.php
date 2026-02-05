<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Due;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Event\AfterSheet;

class ReportController extends Controller
{
    public function __construct()
    {
        // Middleware tanımını kaldırıp her method'da kontrol yapalım
    }

    public function index()
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'accountant'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        // Genel istatistikler
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'active')->count();
        $inactiveMembers = Member::where('status', 'inactive')->count();

        // Bu ay verileri - Aidat bazlı
        $thisMonth = now();
        $thisMonthPayments = Payment::whereHas('dues', function($query) use ($thisMonth) {
            $query->where('month', $thisMonth->month)
                  ->where('year', $thisMonth->year);
        })->sum('amount');

        // Bu yıl verileri - Aidat bazlı
        $thisYearPayments = Payment::whereHas('dues', function($query) use ($thisMonth) {
            $query->where('year', $thisMonth->year);
        })->sum('amount');

        // Geçen yıl verileri (karşılaştırma için) - Aidat bazlı
        $lastYearPayments = Payment::whereHas('dues', function($query) use ($thisMonth) {
            $query->where('year', $thisMonth->year - 1);
        })->sum('amount');

        // Aidat durumu
        $paidDues = Due::where('status', 'paid')->count();
        $pendingDues = Due::where('status', 'pending')->count();
        $overdueDues = Due::overdue()->count();

        // Gecikmiş aidat tutarı hesapla
        $overdueAmount = Due::overdue()->sum('amount');

        // Gecikmiş aidatı olan üye sayısı
        $membersWithOverdueDues = Member::whereHas('dues', function($query) {
            $query->overdue();
        })->count();

        // Aylık gelir trendi (sadece bu yıl) - Aidat bazlı
        // Hangi ayın aidatı ödenmiş, ona göre hesapla
        $monthlyRevenue = [];
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $monthNames = [
            1 => 'Oca', 2 => 'Şub', 3 => 'Mar', 4 => 'Nis',
            5 => 'May', 6 => 'Haz', 7 => 'Tem', 8 => 'Ağu',
            9 => 'Eyl', 10 => 'Eki', 11 => 'Kas', 12 => 'Ara'
        ];

        // Bu yılın Ocak ayından şu ana kadar
        for ($month = 1; $month <= $currentMonth; $month++) {
            // Bu ayın aidatlarına yapılan ödemeler
            $revenue = Payment::whereHas('dues', function($query) use ($currentYear, $month) {
                $query->where('month', $month)
                      ->where('year', $currentYear);
            })->sum('amount');

            $monthlyRevenue[] = [
                'month' => $monthNames[$month] . ' ' . $currentYear,
                'revenue' => $revenue
            ];
        }

        // Üye artış trendi (sadece bu yıl)
        $memberGrowth = [];

        // Bu yılın Ocak ayından şu ana kadar
        for ($month = 1; $month <= $currentMonth; $month++) {
            $count = Member::whereMonth('membership_date', $month)
                ->whereYear('membership_date', $currentYear)
                ->count();

            $memberGrowth[] = [
                'month' => $monthNames[$month] . ' ' . $currentYear,
                'count' => $count
            ];
        }


        // Yaş grupları analizi
        $ageGroups = [
            '0-18' => 0,
            '19-30' => 0,
            '31-45' => 0,
            '46-60' => 0,
            '61+' => 0
        ];

        $members = Member::whereNotNull('birth_date')->get();

        // Debug: Toplam üye sayısını logla
        \Log::info('Yaş grupları analizi - Toplam üye sayısı: ' . $members->count());

        foreach ($members as $member) {
            // Debug: Doğum tarihi formatını kontrol et
            \Log::info('Member ID: ' . $member->id . ', Birth Date: ' . $member->birth_date . ', Type: ' . gettype($member->birth_date));

            $age = null;
            if ($member->birth_date) {
                try {
                    // Carbon ile doğum tarihini parse et
                    $birthDate = \Carbon\Carbon::parse($member->birth_date);
                    $age = $birthDate->diffInYears(now());
                    \Log::info('Member ID: ' . $member->id . ', Calculated Age: ' . $age);
                } catch (\Exception $e) {
                    \Log::error('Birth date parse error for member ' . $member->id . ': ' . $e->getMessage());
                }
            }

            if ($age !== null && $age >= 0) {
                if ($age >= 0 && $age <= 18) {
                    $ageGroups['0-18']++;
                } elseif ($age >= 19 && $age <= 30) {
                    $ageGroups['19-30']++;
                } elseif ($age >= 31 && $age <= 45) {
                    $ageGroups['31-45']++;
                } elseif ($age >= 46 && $age <= 60) {
                    $ageGroups['46-60']++;
                } elseif ($age >= 61) {
                    $ageGroups['61+']++;
                }
            }
        }

        // Debug: Yaş grupları sonuçlarını logla
        \Log::info('Yaş grupları sonuçları: ', $ageGroups);

        // Debug bilgilerini view'a gönder
        $debugInfo = [
            'total_members' => Member::count(),
            'members_with_birth_date' => Member::whereNotNull('birth_date')->count(),
            'age_groups' => $ageGroups
        ];

        // Memleket analizi
        $birthPlaces = Member::whereNotNull('birth_place')
            ->where('birth_place', '!=', '')
            ->where('birth_place', '!=', 'Bilinmiyor')
            ->selectRaw('birth_place, COUNT(*) as count')
            ->groupBy('birth_place')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact(
            'totalMembers',
            'activeMembers',
            'inactiveMembers',
            'thisMonthPayments',
            'thisYearPayments',
            'lastYearPayments',
            'paidDues',
            'pendingDues',
            'overdueDues',
            'overdueAmount',
            'membersWithOverdueDues',
            'monthlyRevenue',
            'memberGrowth',
            'ageGroups',
            'birthPlaces',
            'debugInfo'
        ));
    }

    public function detailed(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'accountant'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfYear()->format('Y-m-d'));
        $type = $request->get('type', 'payments');
        $paymentMethod = $request->get('payment_method');

        // Elden takip için tarih aralığına ihtiyaç yok: mevcut yıl aralığını zorunlu uygula
        if ($type === 'manual') {
            $startDate = now()->startOfYear()->format('Y-m-d');
            $endDate = now()->endOfYear()->format('Y-m-d');
        }

        $data = [];

        switch ($type) {
            case 'payments':
                $data = $this->getPaymentReport($startDate, $endDate, $paymentMethod);
                break;
            case 'members':
                $addressFilter = $request->get('address_filter');
                $phoneFilter = $request->get('phone_filter');
                $data = $this->getMemberReport($startDate, $endDate, $addressFilter, $phoneFilter);
                break;
            case 'manual':
                $data = ['months' => $this->getMonthRange($startDate, $endDate)];
                break;
        }

        return view('admin.reports.detailed', compact('data', 'startDate', 'endDate', 'type', 'paymentMethod'));
    }

    private function getPaymentReport($startDate, $endDate, $paymentMethod = null)
    {
        // Raporda, seçilen aralıktaki AY/YIL dönemlerine ait aidatları çekeceğiz
        // Ödeme tarihi değil, aidat dönemi (year/month) baz alınacak

        $startYear = Carbon::parse($startDate)->year;
        $endYear = Carbon::parse($endDate)->year;
        $startMonth = Carbon::parse($startDate)->month;
        $endMonth = Carbon::parse($endDate)->month;

        // Ödeme yöntemi filtresine göre üyeleri çek
        if ($paymentMethod) {
            // Belirli ödeme yöntemi seçilmişse: Bu ödeme yöntemini kullanan üyeleri çek
            $allMembers = Member::select('id', 'name', 'surname', 'member_no', 'monthly_dues', 'payment_method')
                ->where('payment_method', $paymentMethod)
                ->orderBy('surname', 'asc')
                ->orderBy('name', 'asc')
                ->get();
        } else {
            // Tümü seçilmişse: Tüm üyeleri çek
            $allMembers = Member::select('id', 'name', 'surname', 'member_no', 'monthly_dues', 'payment_method')
                ->orderBy('surname', 'asc')
                ->orderBy('name', 'asc')
                ->get();
        }

        // Seçilen dönemdeki ödenen aidatları çek
        $paidDuesQuery = Due::select('id', 'member_id', 'year', 'month', 'amount', 'status')
            ->where(function($q) use ($startYear, $endYear, $startMonth, $endMonth) {
                if ($startYear == $endYear) {
                    $q->where('year', $startYear)
                      ->whereBetween('month', [$startMonth, $endMonth]);
                } else {
                    $q->where(function($subQ) use ($startYear, $startMonth) {
                        $subQ->where('year', $startYear)
                             ->where('month', '>=', $startMonth);
                    })
                    ->orWhere(function($subQ) use ($endYear, $endMonth) {
                        $subQ->where('year', $endYear)
                             ->where('month', '<=', $endMonth);
                    })
                    ->orWhere(function($subQ) use ($startYear, $endYear) {
                        $subQ->where('year', '>', $startYear)
                             ->where('year', '<', $endYear);
                    });
                }
            })
            ->where('status', 'paid');

        // Ödeme yöntemi filtresi uygula
        if ($paymentMethod) {
            $paidDuesQuery->whereHas('paymentDues', function($query) use ($paymentMethod) {
                $query->where('payment_method', $paymentMethod);
            });
        }

        $paidDues = $paidDuesQuery->get();

        // Aylık ödemeleri grupla - aidat dönemine göre
        $monthlyPayments = [];

        foreach ($allMembers as $member) {
            // Bu üyenin ödenen aidatları
            $memberPaidDues = $paidDues->where('member_id', $member->id);

            $monthlyPayments[$member->id] = [
                'member' => $member,
                'payments' => $memberPaidDues->flatMap->paymentDues->unique('id'), // Bu üyenin ödemeleri
                'monthly_data' => []
            ];

            // Her ay için ödeme durumunu kontrol et
            for ($year = $startYear; $year <= $endYear; $year++) {
                $monthStart = ($year == $startYear) ? $startMonth : 1;
                $monthEnd = ($year == $endYear) ? $endMonth : 12;

                for ($month = $monthStart; $month <= $monthEnd; $month++) {
                    $monthKey = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);

                    // Bu ay için ödenen aidat var mı kontrol et
                    $paidDue = $memberPaidDues->where('year', $year)->where('month', $month)->first();

                    $monthlyPayments[$member->id]['monthly_data'][$monthKey] = $paidDue ? [ 'paid' => true, 'mark' => 'X', 'due' => $paidDue ] : null;
                }
            }
        }

        // Summary: Ödenen aidatların toplamını hesapla
        $totalAmount = $paidDues->sum('amount');
        $totalCount = $paidDues->count();

        // Üyeleri soyisim-isim sırasına göre sırala (Türkçe karakter desteği ile)
        $monthlyPayments = collect($monthlyPayments)
            ->sort(function ($a, $b) {
                $memberA = $a['member'];
                $memberB = $b['member'];

                $surnameA = $memberA->surname ?? '';
                $surnameB = $memberB->surname ?? '';
                $nameA = $memberA->name ?? '';
                $nameB = $memberB->name ?? '';

                // Türkçe karakter sıralaması için Collator kullan
                if (class_exists('Collator')) {
                    $collator = new \Collator('tr_TR');
                    $surnameCompare = $collator->compare($surnameA, $surnameB);
                    if ($surnameCompare !== 0) {
                        return $surnameCompare;
                    }
                    return $collator->compare($nameA, $nameB);
                } else {
                    // Fallback: Türkçe karakterleri manuel dönüştür
                    $turkishChars = ['ç', 'ğ', 'ı', 'ö', 'ş', 'ü', 'Ç', 'Ğ', 'I', 'İ', 'Ö', 'Ş', 'Ü'];
                    $englishChars = ['c', 'g', 'i', 'o', 's', 'u', 'C', 'G', 'I', 'I', 'O', 'S', 'U'];

                    $surnameA = str_replace($turkishChars, $englishChars, mb_strtolower($surnameA));
                    $surnameB = str_replace($turkishChars, $englishChars, mb_strtolower($surnameB));
                    $nameA = str_replace($turkishChars, $englishChars, mb_strtolower($nameA));
                    $nameB = str_replace($turkishChars, $englishChars, mb_strtolower($nameB));

                    $surnameCompare = strcmp($surnameA, $surnameB);
                    if ($surnameCompare !== 0) {
                        return $surnameCompare;
                    }
                    return strcmp($nameA, $nameB);
                }
            })
            ->values()
            ->all();

        // PERFORMANS: Ödeme yöntemi analizi kaldırıldı
        $paymentMethodsData = [];

        $summary = [
            'total_amount' => $totalAmount,
            'total_count' => $totalCount,
            'average_payment' => $totalCount ? $totalAmount / $totalCount : 0,
            'payment_methods' => collect($paymentMethodsData)
        ];

        return [
            'payments' => collect(), // PERFORMANS: Gereksiz ödeme listesi kaldırıldı
            'monthly_payments' => $monthlyPayments,
            'summary' => $summary,
            'months' => $this->getMonthRange($startDate, $endDate)
        ];
    }

    private function getMonthRange($startDate, $endDate)
    {
        $months = [];
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $monthNames = [
            1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
            5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
            9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
        ];

        while ($start->lte($end)) {
            $months[] = [
                'key' => $start->format('Y-m'),
                'label' => $monthNames[$start->month],
                'year' => $start->year,
                'month' => $start->month
            ];
            $start->addMonth();
        }

        return $months;
    }

    private function getMemberReport($startDate, $endDate, $addressFilter = null, $phoneFilter = null)
    {
        $query = Member::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        // Adres filtresi uygula
        if ($addressFilter) {
            if ($addressFilter === 'has') {
                $query->whereNotNull('address')
                      ->where('address', '!=', '')
                      ->where('address', '!=', ' ');
            } elseif ($addressFilter === 'missing') {
                $query->where(function($q){
                    $q->whereNull('address')
                      ->orWhere('address', '=', '')
                      ->orWhere('address', '=', ' ');
                });
            }
        }

        // Telefon filtresi uygula
        if ($phoneFilter) {
            if ($phoneFilter === 'has') {
                $query->whereNotNull('phone')
                      ->where('phone', '!=', '')
                      ->where('phone', '!=', ' ');
            } elseif ($phoneFilter === 'missing') {
                $query->where(function($q){
                    $q->whereNull('phone')
                      ->orWhere('phone', '=', '')
                      ->orWhere('phone', '=', ' ');
                });
            }
        }

        $members = $query->orderBy('created_at', 'desc')->get();

        $summary = [
            'total_new_members' => $members->count(),
            'active_members' => $members->where('status', 'active')->count(),
            'inactive_members' => $members->where('status', 'inactive')->count(),
            'average_monthly_dues' => $members->avg('monthly_dues'),
            'total_monthly_revenue' => $members->sum('monthly_dues')
        ];

        return [
            'members' => $members,
            'summary' => $summary
        ];
    }


    public function export(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'accountant'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }
        $type = $request->get('type', 'payments');
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfYear()->format('Y-m-d'));
        $paymentMethod = $request->get('payment_method');
        $addressFilter = $request->get('address_filter');
        $phoneFilter = $request->get('phone_filter');

        switch ($type) {
            case 'payments':
                return $this->exportPayments($startDate, $endDate, $paymentMethod);
            case 'members':
                return $this->exportMembers($startDate, $endDate, $addressFilter, $phoneFilter);
            default:
                return back()->with('error', 'Geçersiz rapor türü.');
        }
    }

    public function exportPdf(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'accountant'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $type = $request->get('type', 'payments');
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfYear()->format('Y-m-d'));
        $paymentMethod = $request->get('payment_method');
        $addressFilter = $request->get('address_filter');
        $phoneFilter = $request->get('phone_filter');

        // Büyük PDF'lerde bellek ve süre ayarları
        @ini_set('memory_limit', '512M');
        @set_time_limit(120);

        if ($type === 'payments') {
            $data = $this->getPaymentReport($startDate, $endDate, $paymentMethod);
            $html = view('admin.reports.partials.payments-pdf', compact('data','startDate','endDate','paymentMethod'))->render();
            $filename = 'odeme_raporu_' . $startDate . '_' . $endDate . '.pdf';
        } elseif ($type === 'members') {
            // Üye raporu PDF
            $query = Member::orderBy('surname', 'asc')
                ->orderBy('name', 'asc');

            // Adres filtresi uygula
            if ($addressFilter) {
                if ($addressFilter === 'has') {
                    $query->whereNotNull('address')
                          ->where('address', '!=', '')
                          ->where('address', '!=', ' ');
                } elseif ($addressFilter === 'missing') {
                    $query->where(function($q){
                        $q->whereNull('address')
                          ->orWhere('address', '=', '')
                          ->orWhere('address', '=', ' ');
                    });
                }
            }

            // Telefon filtresi uygula
            if ($phoneFilter) {
                if ($phoneFilter === 'has') {
                    $query->whereNotNull('phone')
                          ->where('phone', '!=', '')
                          ->where('phone', '!=', ' ');
                } elseif ($phoneFilter === 'missing') {
                    $query->where(function($q){
                        $q->whereNull('phone')
                          ->orWhere('phone', '=', '')
                          ->orWhere('phone', '=', ' ');
                    });
                }
            }

            $members = $query->get();
            $html = view('admin.reports.partials.members-pdf', compact('members', 'startDate', 'endDate'))->render();
            $filename = 'uyeler_raporu_' . $startDate . '_' . $endDate . '.pdf';
        } elseif ($type === 'manual') {
            // Elden takip: tüm üyeler için boş tablo şablonu
            $membersQuery = Member::where('status','active');
            if ($paymentMethod) {
                $membersQuery->where('payment_method', $paymentMethod);
            }
            $members = $membersQuery->orderBy('surname', 'asc')->orderBy('name', 'asc')->get(['id','name','surname','member_no','monthly_dues']);
            // Elden takipte tarih aralığı gereksiz: Mevcut yıl 12 ay
            $start = now()->startOfYear()->format('Y-m-d');
            $end = now()->endOfYear()->format('Y-m-d');
            $months = $this->getMonthRange($start, $end);
            $startDate = $start; $endDate = $end;
            // Ödenen ayları işaretlemek için due tablosundan yıl bazlı çekim
            $year = now()->year;
            $paidDues = Due::whereIn('member_id', $members->pluck('id'))
                ->where('year', $year)
                ->where('status', 'paid')
                ->get(['member_id','month']);
            $paidMap = [];
            foreach ($paidDues as $d) {
                $paidMap[$d->member_id][$d->month] = true;
            }
            $html = view('admin.reports.partials.manual-pdf', compact('members','months','startDate','endDate','paidMap'))->render();
            $filename = 'elden_takip_' . $startDate . '_' . $endDate . '.pdf';
        } else {
            return back()->with('error', 'Seçilen rapor türü için PDF desteklenmiyor.');
        }

        // PDF sayfa yönelimi - üye raporu için landscape, diğerleri için landscape
        $orientation = 'landscape';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)
            ->setPaper('a4', $orientation)
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);
        return $pdf->download($filename);
    }

    private function exportPayments($startDate, $endDate, $paymentMethod = null)
    {
        $data = $this->getPaymentReport($startDate, $endDate, $paymentMethod);

        $filename = "aylik_odeme_raporu_" . $startDate . "_" . $endDate;
        if ($paymentMethod) {
            $filename .= "_" . $paymentMethod;
        }
        $filename .= ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data, $startDate, $endDate, $paymentMethod) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM ekle
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Başlık bilgileri
            $title = 'AYLIK AIDAT RAPORU';
            if ($paymentMethod) {
                $paymentMethodNames = [
                    'cash' => 'Nakit',
                    'bank_transfer' => 'Banka Havalesi',
                    'lastschrift' => 'Lastschrift',
                    'sepa' => 'SEPA',
                    'credit_card' => 'Kredi Kartı'
                ];
                $methodName = $paymentMethodNames[$paymentMethod] ?? ucfirst($paymentMethod);
                $title .= ' - ' . $methodName;
            }
            fputcsv($file, [$title], ';');
            fputcsv($file, ['Tarih Aralığı: ' . \Carbon\Carbon::parse($startDate)->format('d.m.Y') . ' - ' . \Carbon\Carbon::parse($endDate)->format('d.m.Y')], ';');
            fputcsv($file, ['Rapor Tarihi: ' . now()->format('d.m.Y H:i')], ';');
            fputcsv($file, [''], ';'); // Boş satır

            // Başlık satırı
            $headerRow = ['Ad', 'Soyad', 'Üye No', 'Aylık Aidat'];
            foreach ($data['months'] as $month) {
                $headerRow[] = $month['label'];
            }
            fputcsv($file, $headerRow, ';');

            // Üye verileri
            if (isset($data['monthly_payments'])) {
                foreach ($data['monthly_payments'] as $memberId => $memberData) {
                    $member = $memberData['member'];
                    $row = [
                        $member->name,
                        $member->surname,
                        $member->member_no,
                        number_format($member->monthly_dues, 2) . ' €'
                    ];

                    // Her ay için ödeme durumu
                    foreach ($data['months'] as $month) {
                        if (isset($memberData['monthly_data'][$month['key']]) && $memberData['monthly_data'][$month['key']]) {
                            $row[] = 'X';
                        } else {
                            $row[] = '';
                        }
                    }

                    fputcsv($file, $row, ';');
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportXlsx(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'accountant'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $type = $request->get('type', 'payments');
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfYear()->format('Y-m-d'));
        $paymentMethod = $request->get('payment_method');
        $addressFilter = $request->get('address_filter');
        $phoneFilter = $request->get('phone_filter');

        switch ($type) {
            case 'payments':
                return $this->exportPaymentsXlsx($startDate, $endDate, $paymentMethod);
            case 'members':
                return $this->exportMembersXlsx($startDate, $endDate, $addressFilter, $phoneFilter);
            default:
                return back()->with('error', 'Geçersiz rapor türü.');
        }
    }

    private function exportPaymentsXlsx($startDate, $endDate, $paymentMethod = null)
    {
        $data = $this->getPaymentReport($startDate, $endDate, $paymentMethod);

        $filename = "aylik_odeme_raporu_" . $startDate . "_" . $endDate;
        if ($paymentMethod) {
            $filename .= "_" . $paymentMethod;
        }
        $filename .= ".xlsx";

        // Başlık satırı
        $headerRow = ['Ad', 'Soyad', 'Üye No', 'Aylık Aidat'];
        foreach ($data['months'] as $month) {
            $headerRow[] = $month['label'];
        }

        // Veri satırları
        $rows = [];
        if (isset($data['monthly_payments'])) {
            foreach ($data['monthly_payments'] as $memberData) {
                $member = $memberData['member'];
                $row = [
                    $member->name,
                    $member->surname,
                    $member->member_no,
                    number_format($member->monthly_dues, 2) . ' €'
                ];

                // Her ay için ödeme durumu
                foreach ($data['months'] as $month) {
                    if (isset($memberData['monthly_data'][$month['key']]) && $memberData['monthly_data'][$month['key']]) {
                        $row[] = 'X';
                    } else {
                        $row[] = '';
                    }
                }
                $rows[] = $row;
            }
        }

        return Excel::download(new class($headerRow, $rows, $startDate, $endDate, $paymentMethod) implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithEvents {
            private $headerRow;
            private $rows;
            private $startDate;
            private $endDate;
            private $paymentMethod;

            public function __construct($headerRow, $rows, $startDate, $endDate, $paymentMethod)
            {
                $this->headerRow = $headerRow;
                $this->rows = $rows;
                $this->startDate = $startDate;
                $this->endDate = $endDate;
                $this->paymentMethod = $paymentMethod;
            }

            public function array(): array
            {
                return $this->rows;
            }

            public function headings(): array
            {
                return $this->headerRow;
            }

            public function styles(Worksheet $sheet)
            {
                $styles = [];

                // Başlık satırı
                $styles[1] = [
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2E86AB']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ];

                // Veri satırları için zebra striping ve çerçeveler
                $totalRows = count($this->rows) + 1; // +1 for header
                for ($row = 2; $row <= $totalRows; $row++) {
                    $fillColor = ($row % 2 == 0) ? 'F8F9FA' : 'FFFFFF';

                    $styles[$row] = [
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $fillColor]
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => '000000']
                            ]
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER
                        ]
                    ];
                }

                return $styles;
            }

            public function columnWidths(): array
            {
                $widths = ['A' => 18, 'B' => 18, 'C' => 15, 'D' => 18];
                $column = 'E';
                for ($i = 0; $i < count($this->headerRow) - 4; $i++) {
                    $widths[$column] = 15;
                    $column++;
                }

                // Son sütun için ekstra genişlik
                $totalColumns = count($this->headerRow);
                $lastColumn = $this->getColumnLetter($totalColumns - 1);
                if ($lastColumn) {
                    $widths[$lastColumn] = 15;
                }

                return $widths;
            }

            private function getColumnLetter($index)
            {
                $columnLetter = '';
                while ($index >= 0) {
                    $columnLetter = chr(($index % 26) + ord('A')) . $columnLetter;
                    $index = intval($index / 26) - 1;
                }
                return $columnLetter;
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function(AfterSheet $event) {
                        $sheet = $event->sheet->getDelegate();
                        $highestRow = $sheet->getHighestRow();
                        $highestColumn = $sheet->getHighestColumn();

                        // Tüm hücrelere çerçeve ekle (son sütun dahil)
                        $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => ['rgb' => '000000']
                                ]
                            ]
                        ]);
                    }
                ];
            }

        }, $filename);
    }

    private function exportMembersXlsx($startDate, $endDate, $addressFilter = null, $phoneFilter = null)
    {
        // Tüm üyeleri soyadına göre sırala
        $query = Member::orderBy('surname', 'asc')
            ->orderBy('name', 'asc');

        // Adres filtresi uygula
        if ($addressFilter) {
            if ($addressFilter === 'has') {
                $query->whereNotNull('address')
                      ->where('address', '!=', '')
                      ->where('address', '!=', ' ')
                      ->where('address', 'NOT LIKE', '%Bilinmiyor%');
            } elseif ($addressFilter === 'missing') {
                $query->where(function($q){
                    $q->whereNull('address')
                      ->orWhere('address', '=', '')
                      ->orWhere('address', '=', ' ');
                });
            }
        }

        // Telefon filtresi uygula
        if ($phoneFilter) {
            if ($phoneFilter === 'has') {
                $query->whereNotNull('phone')
                      ->where('phone', '!=', '')
                      ->where('phone', '!=', ' ');
            } elseif ($phoneFilter === 'missing') {
                $query->where(function($q){
                    $q->whereNull('phone')
                      ->orWhere('phone', '=', '')
                      ->orWhere('phone', '=', ' ');
                });
            }
        }

        $members = $query->get();

        $filename = "uyeler_raporu_" . $startDate . "_" . $endDate . ".xlsx";

        $headers = [
            'ad_soyad',
            'email',
            'telefon',
            'doğum_tarihi',
            'doğum_yeri',
            'uyruk',
            'meslek',
            'adres',
            'uyelik_tarihi',
            'aylık_aidat',
            'ödeme_yöntemi',
            'durum',
            'notlar'
        ];

        $rows = [];
        foreach ($members as $member) {
            $rows[] = [
                $member->surname . ' ' . $member->name,
                $member->email ?? '',
                $member->phone ?? '',
                $member->birth_date ? $member->birth_date->format('d.m.Y') : '',
                $member->birth_place && $member->birth_place !== 'Bilinmiyor' ? $member->birth_place : '',
                $member->nationality && $member->nationality !== 'Bilinmiyor' ? $member->nationality : '',
                $member->occupation && $member->occupation !== 'Serbest' ? $member->occupation : '',
                $member->address && $member->address !== 'Bilinmiyor' ? $member->address : '',
                $member->membership_date ? $member->membership_date->format('d.m.Y') : '',
                number_format($member->monthly_dues, 2) . ' €',
                $member->payment_method ? $this->getPaymentMethodText($member->payment_method) : 'Belirtilmemiş',
                $member->status_text,
                $member->notes ?? ''
            ];
        }

        return Excel::download(new class($headers, $rows) implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithEvents {
            private $headers;
            private $rows;

            public function __construct($headers, $rows)
            {
                $this->headers = $headers;
                $this->rows = $rows;
            }

            public function array(): array
            {
                return $this->rows;
            }

            public function headings(): array
            {
                return $this->headers;
            }

            public function styles(Worksheet $sheet)
            {
                $styles = [];

                // Başlık satırı
                $styles[1] = [
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2E86AB']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ];

                // Veri satırları için zebra striping ve çerçeveler
                $totalRows = count($this->rows) + 1; // +1 for header
                for ($row = 2; $row <= $totalRows; $row++) {
                    $fillColor = ($row % 2 == 0) ? 'F8F9FA' : 'FFFFFF';

                    $styles[$row] = [
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $fillColor]
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => '000000']
                            ]
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER
                        ]
                    ];
                }

                return $styles;
            }

            public function columnWidths(): array
            {
                return [
                    'A' => 25, 'B' => 25, 'C' => 15, 'D' => 15, 'E' => 15,
                    'F' => 15, 'G' => 20, 'H' => 30, 'I' => 15, 'J' => 15,
                    'K' => 20, 'L' => 15, 'M' => 30
                ];
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function(AfterSheet $event) {
                        $sheet = $event->sheet->getDelegate();
                        $highestRow = $sheet->getHighestRow();
                        $highestColumn = $sheet->getHighestColumn();

                        // Tüm hücrelere çerçeve ekle (son sütun dahil)
                        $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => ['rgb' => '000000']
                                ]
                            ]
                        ]);
                    }
                ];
            }
        }, $filename);
    }


    private function exportMembers($startDate, $endDate, $addressFilter = null, $phoneFilter = null)
    {
        // Tüm üyeleri soyadına göre sırala
        $query = Member::orderBy('surname', 'asc')
            ->orderBy('name', 'asc');

        // Adres filtresi uygula
        if ($addressFilter) {
            if ($addressFilter === 'has') {
                $query->whereNotNull('address')
                      ->where('address', '!=', '')
                      ->where('address', '!=', ' ')
                      ->where('address', 'NOT LIKE', '%Bilinmiyor%');
            } elseif ($addressFilter === 'missing') {
                $query->where(function($q){
                    $q->whereNull('address')
                      ->orWhere('address', '=', '')
                      ->orWhere('address', '=', ' ');
                });
            }
        }

        // Telefon filtresi uygula
        if ($phoneFilter) {
            if ($phoneFilter === 'has') {
                $query->whereNotNull('phone')
                      ->where('phone', '!=', '')
                      ->where('phone', '!=', ' ');
            } elseif ($phoneFilter === 'missing') {
                $query->where(function($q){
                    $q->whereNull('phone')
                      ->orWhere('phone', '=', '')
                      ->orWhere('phone', '=', ' ');
                });
            }
        }

        $members = $query->get();

        $filename = "uyeler_raporu_" . $startDate . "_" . $endDate . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($members) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM ekle
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Başlıklar - soyad ad formatında
            fputcsv($file, [
                'ad_soyad',
                'email',
                'telefon',
                'doğum_tarihi',
                'doğum_yeri',
                'uyruk',
                'meslek',
                'adres',
                'uyelik_tarihi',
                'aylık_aidat',
                'ödeme_yöntemi',
                'durum',
                'notlar'
            ], ';');

            foreach ($members as $member) {
                fputcsv($file, [
                    $member->surname . ' ' . $member->name,
                    $member->email ?? '',
                    $member->phone ?? '',
                    $member->birth_date ? $member->birth_date->format('d.m.Y') : '',
                    $member->birth_place && $member->birth_place !== 'Bilinmiyor' ? $member->birth_place : '',
                    $member->nationality && $member->nationality !== 'Bilinmiyor' ? $member->nationality : '',
                    $member->occupation && $member->occupation !== 'Serbest' ? $member->occupation : '',
                    $member->address && $member->address !== 'Bilinmiyor' ? $member->address : '',
                    $member->membership_date ? $member->membership_date->format('d.m.Y') : '',
                    number_format($member->monthly_dues, 2) . ' €',
                    $member->payment_method ? $this->getPaymentMethodText($member->payment_method) : 'Belirtilmemiş',
                    $member->status_text,
                    $member->notes ?? ''
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    private function getPaymentMethodText($method)
    {
        return match($method) {
            'cash' => 'Nakit',
            'bank_transfer' => 'Banka Transferi',
            'lastschrift' => 'Lastschrift (SEPA)',
            'credit_card' => 'Kredi Kartı',
            'direct_debit' => 'Otomatik Ödeme',
            'standing_order' => 'Düzenli Transfer',
            'other' => 'Diğer',
            default => $method
        };
    }
}
