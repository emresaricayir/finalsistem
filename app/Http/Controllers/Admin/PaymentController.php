<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Member;
use App\Models\Due;
use App\Models\Settings;
use App\Models\DonationCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Middleware will be handled in individual methods
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['member', 'dues', 'due', 'recordedBy']);

        // Zorunlu ay filtresi (varsayılan: mevcut ay) - ÖDEME TARİHİNE GÖRE DEĞİL, ÖDENEN AYA (due_date) GÖRE
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);

        $query->where(function($q) use ($year, $month) {
            // Yeni sistem: birden fazla aidat (dues) ilişkisi
            $q->whereHas('dues', function($dq) use ($year, $month) {
                $dq->whereYear('due_date', $year)
                   ->whereMonth('due_date', $month);
            })
            // Eski sistem: tekil due ilişkisi
            ->orWhereHas('due', function($dq) use ($year, $month) {
                $dq->whereYear('due_date', $year)
                   ->whereMonth('due_date', $month);
            });
        });

        // Yalnızca AKTIF üyelerin ödemelerini dahil et
        $query->whereHas('member', function($q) {
            $q->where('status', 'active');
        });

        // Filtreleme
        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        // Arama parametresi (search veya member_search) - Türkçe karakter desteği ile
        if ($request->filled('search') || $request->filled('member_search')) {
            $searchTerm = trim($request->get('search') ?: $request->get('member_search'));
            $normalizedTerm = $this->normalizeTurkishChars(mb_strtolower($searchTerm));
            $normalizeSQL = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(%s), 'ş', 's'), 'ı', 'i'), 'ğ', 'g'), 'ü', 'u'), 'ö', 'o'), 'ç', 'c'), 'İ', 'i')";

            $query->where(function($outer) use ($normalizedTerm, $normalizeSQL, $searchTerm) {
                $outer->whereHas('member', function($q) use ($normalizedTerm, $normalizeSQL) {
                    $q->whereRaw(sprintf($normalizeSQL, 'name') . ' LIKE ?', ["%{$normalizedTerm}%"])
                      ->orWhereRaw(sprintf($normalizeSQL, 'surname') . ' LIKE ?', ["%{$normalizedTerm}%"])
                      ->orWhereRaw(sprintf($normalizeSQL, 'email') . ' LIKE ?', ["%{$normalizedTerm}%"])
                      ->orWhereRaw(sprintf($normalizeSQL, 'member_no') . ' LIKE ?', ["%{$normalizedTerm}%"]);
                })
                ->orWhere('payments.receipt_no', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('payment_method')) {
            $query->where('payments.payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // Join ile members tablosunu bağlayıp soyad ve ad göre sıralama
        $query->join('members', 'payments.member_id', '=', 'members.id')
              ->where('members.status', 'active')
              ->orderBy('members.surname', 'asc')
              ->orderBy('members.name', 'asc')
              ->select('payments.*');

        // Tüm ödemeleri getir (sayfalama yok)
        $payments = $query->get();

        // İstatistikler için aynı filtreleri uygula
        $statsQuery = Payment::with(['member', 'dues', 'due', 'recordedBy']);

        // Yalnızca AKTIF üyelerin ödemelerini dahil et (istatistikler)
        $statsQuery->whereHas('member', function($q) {
            $q->where('status', 'active');
        });

        $statsQuery->where(function($q) use ($year, $month) {
            $q->whereHas('dues', function($dq) use ($year, $month) {
                $dq->whereYear('due_date', $year)
                   ->whereMonth('due_date', $month);
            })
            ->orWhereHas('due', function($dq) use ($year, $month) {
                $dq->whereYear('due_date', $year)
                   ->whereMonth('due_date', $month);
            });
        });

        // Aynı filtreleri uygula
        if ($request->filled('member_id')) {
            $statsQuery->where('member_id', $request->member_id);
        }
        if ($request->filled('search') || $request->filled('member_search')) {
            $searchTerm = trim($request->get('search') ?: $request->get('member_search'));
            $normalizedTerm = $this->normalizeTurkishChars(mb_strtolower($searchTerm));
            $normalizeSQL = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(%s), 'ş', 's'), 'ı', 'i'), 'ğ', 'g'), 'ü', 'u'), 'ö', 'o'), 'ç', 'c'), 'İ', 'i')";

            $statsQuery->where(function($outer) use ($normalizedTerm, $normalizeSQL, $searchTerm) {
                $outer->whereHas('member', function($q) use ($normalizedTerm, $normalizeSQL) {
                    $q->whereRaw(sprintf($normalizeSQL, 'name') . ' LIKE ?', ["%{$normalizedTerm}%"])
                      ->orWhereRaw(sprintf($normalizeSQL, 'surname') . ' LIKE ?', ["%{$normalizedTerm}%"])
                      ->orWhereRaw(sprintf($normalizeSQL, 'email') . ' LIKE ?', ["%{$normalizedTerm}%"])
                      ->orWhereRaw(sprintf($normalizeSQL, 'member_no') . ' LIKE ?', ["%{$normalizedTerm}%"]);
                })
                ->orWhere('payments.receipt_no', 'LIKE', "%{$searchTerm}%");
            });
        }
        if ($request->filled('payment_method')) {
            $statsQuery->where('payments.payment_method', $request->payment_method);
        }
        if ($request->filled('date_from')) {
            $statsQuery->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $statsQuery->whereDate('payment_date', '<=', $request->date_to);
        }

        // İstatistikleri hesapla
        $totalPayments = $statsQuery->count();
        $totalAmount = $statsQuery->sum('amount');
        $uniqueMembers = $statsQuery->distinct('member_id')->count('member_id');

        // Ödeme yöntemlerine göre dağılım
        $paymentMethods = $statsQuery->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        $stats = [
            'total_payments' => $totalPayments,
            'total_amount' => $totalAmount,
            'unique_members' => $uniqueMembers,
            'payment_methods' => $paymentMethods,
        ];

        // Üye listesi (toplu makbuz için) - soyad ve ad göre sıralı
        $members = Member::orderBy('surname')->orderBy('name')->get();

        return view('admin.payments.index', compact('payments', 'members', 'stats'));
    }

    /**
     * Show monthly payments management page
     */
    public function monthlyPayments(Request $request)
    {
        // Get year and month separately (like payments/index)
        $selectedYear = (int) $request->get('year', now()->year);
        $selectedMonth = (int) $request->get('month', now()->month);
        $paymentMethodFilter = $request->get('payment_method'); // Ödeme yöntemi filtresi
        $statusFilter = $request->get('status'); // Aidat durumu filtresi
        
        // Create month date from year and month
        $monthDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1);

        // Get all dues for the selected month (including paid ones for reference)
        // Join ile members tablosunu bağlayıp soyad ve ad göre sıralama
        $allDues = Due::join('members', 'dues.member_id', '=', 'members.id')
            ->where('members.status', 'active')
            ->whereYear('dues.due_date', $monthDate->year)
            ->whereMonth('dues.due_date', $monthDate->month)
            ->orderBy('members.surname', 'asc')
            ->orderBy('members.name', 'asc')
            ->select('dues.*')
            ->with(['member', 'payments'])
            ->get();

        // Apply payment method filter if specified
        if ($paymentMethodFilter) {
            $allDues = $allDues->filter(function($due) use ($paymentMethodFilter) {
                // Eğer aidat ödenmişse, gerçek ödeme yöntemini kontrol et
                if ($due->status === 'paid' && $due->payments->count() > 0) {
                    // Ödenmiş aidatlar için gerçek ödeme yöntemini kontrol et
                    return $due->payments->contains('payment_method', $paymentMethodFilter);
                } else {
                    // Ödenmemiş aidatlar için üyenin varsayılan ödeme yöntemini kontrol et
                    return $due->member->payment_method === $paymentMethodFilter;
                }
            });
        }

        // Apply status filter if specified
        if ($statusFilter) {
            $allDues = $allDues->where('status', $statusFilter);
        }

        // Separate unpaid dues for processing
        $dues = $allDues->where('status', '!=', 'paid');

        // Group by status
        $pendingDues = $dues->where('status', 'pending');
        $overdueDues = $dues->where('status', 'overdue');

        // Filtre seçenekleri
        $paymentMethods = [
            'cash' => 'Nakit',
            'bank_transfer' => 'Banka Transferi',
            'lastschrift_monthly' => 'Lastschrift (Aylık)',
            'lastschrift_semi_annual' => 'Lastschrift (6 Aylık)',
            'lastschrift_annual' => 'Lastschrift (Yıllık)'
        ];

        $statusOptions = [
            'pending' => 'Bekleyen',
            'overdue' => 'Gecikmiş'
        ];

        // Türkçe ay isimleri
        $turkishMonths = [
            1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
            5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
            9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
        ];

        return view('admin.payments.monthly', compact(
            'dues', 'allDues', 'pendingDues', 'overdueDues',
            'selectedYear', 'selectedMonth', 'monthDate', 'paymentMethodFilter', 'statusFilter',
            'paymentMethods', 'statusOptions', 'turkishMonths'
        ));
    }

    /**
     * Normalize Turkish characters for search
     * Converts: ş→s, ı→i, ğ→g, ü→u, ö→o, ç→c, İ→i
     */
    private function normalizeTurkishChars($string)
    {
        $turkish = ['ş', 'Ş', 'ı', 'İ', 'ğ', 'Ğ', 'ü', 'Ü', 'ö', 'Ö', 'ç', 'Ç'];
        $english = ['s', 's', 'i', 'i', 'g', 'g', 'u', 'u', 'o', 'o', 'c', 'c'];

        return str_replace($turkish, $english, $string);
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(Request $request)
    {
        $memberId = $request->get('member_id');
        $member = null;
        $dues = collect();

        if ($memberId) {
            $member = Member::find($memberId);
            if ($member) {
                $dues = Due::where('member_id', $memberId)
                    ->where('status', '!=', 'paid')
                    ->orderBy('due_date', 'asc')
                    ->get();
            }
        }

        $members = Member::orderBy('surname')->get();

        return view('admin.payments.create', compact('member', 'members', 'dues'));
    }

    /**
     * Get unpaid dues for a member (AJAX)
     */
    public function getUnpaidDues($member)
    {
        $dues = Due::where('member_id', $member)
            ->where('status', '!=', 'paid')
            ->orderBy('due_date', 'asc')
            ->get();

        // Aidat dönemlerine göre gruplandır
        $groupedDues = $dues->groupBy(function($due) {
            return $due->year . '-' . str_pad($due->month, 2, '0', STR_PAD_LEFT);
        });

        // Her dönem için sadece bir aidat göster (en son oluşturulan)
        $filteredDues = collect();
        foreach ($groupedDues as $period => $periodDues) {
            $latestDue = $periodDues->sortByDesc('created_at')->first();
            $filteredDues->push($latestDue);
        }

        // Tarihe göre sırala
        $filteredDues = $filteredDues->sortBy('due_date');

        return response()->json($filteredDues->values());
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'selected_due_ids' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,lastschrift_monthly,lastschrift_semi_annual,lastschrift_annual',
            'payment_date' => 'required|date',
            'receipt_no' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Parse selected due IDs
        $dueIds = array_filter(explode(',', $validated['selected_due_ids']));

        if (empty($dueIds)) {
            return back()->withErrors(['selected_due_ids' => 'En az bir aidat seçmelisiniz.'])->withInput();
        }

        // Verify dues exist and belong to the member
        $dues = Due::whereIn('id', $dueIds)
            ->where('member_id', $validated['member_id'])
            ->where('status', '!=', 'paid')
            ->get();

        if ($dues->count() !== count($dueIds)) {
            return back()->withErrors(['selected_due_ids' => 'Seçilen aidatlar geçersiz.'])->withInput();
        }

        // Check for duplicate payments - aynı üye için aynı ayın aidatı zaten ödenmiş mi?
        foreach ($dues as $due) {
            // 1. Bu aidat zaten ödenmiş mi?
            if (Payment::isDueAlreadyPaid($due->id)) {
                return back()->withErrors(['selected_due_ids' => "Aidat ID {$due->id} ({$due->year}-{$due->month}) zaten ödenmiş."])->withInput();
            }

            // 2. Bu üye için aynı ay/yıl için başka bir ödeme var mı?
            if (Payment::hasMemberPaidForMonth($validated['member_id'], $due->year, $due->month)) {
                return back()->withErrors(['selected_due_ids' => "Bu üye için {$due->year} yılı {$due->month}. ayı zaten ödenmiş."])->withInput();
            }

            // 3. Aidat durumu kontrolü
            if ($due->status === 'paid') {
                return back()->withErrors(['selected_due_ids' => "Aidat ID {$due->id} durumu zaten 'ödenmiş' olarak işaretlenmiş."])->withInput();
            }
        }

        // Create separate payment record for each due
        $createdPayments = [];
        $processedCount = 0;
        $skippedCount = 0;

        \DB::transaction(function () use ($dues, $validated, &$createdPayments, &$processedCount, &$skippedCount) {
            foreach ($dues as $due) {
                // Check if due is already paid
                if ($due->status === 'paid') {
                    $skippedCount++;
                    continue;
                }

                // Create individual payment for each due
                $payment = Payment::create([
                    'member_id' => $validated['member_id'],
                    'amount' => $due->amount, // Individual due amount
                    'payment_method' => $validated['payment_method'],
                    'payment_date' => $validated['payment_date'],
                    'receipt_no' => $validated['receipt_no'],
                    'description' => $validated['description'],
                    'recorded_by' => auth()->id(),
                ]);

                // Link payment to the specific due
                $payment->dues()->attach($due->id, ['amount' => $due->amount]);

                // Update due status
                $due->update([
                    'status' => 'paid',
                    'paid_date' => $due->due_date
                ]);

                $createdPayments[] = $payment;
                $processedCount++;
                
                // Log access (DSGVO - Veri erişim kaydı)
                \App\Models\AccessLog::create([
                    'member_id' => $validated['member_id'],
                    'user_id' => auth()->id(),
                    'action' => 'payment_create',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'details' => [
                        'payment_id' => $payment->id,
                        'amount' => $payment->amount,
                        'due_ids' => [$due->id],
                        'payment_date' => $payment->payment_date->format('Y-m-d'),
                    ],
                ]);
            }
        });

        // Generate detailed success message
        if ($processedCount > 0 && $skippedCount == 0) {
            $message = "✅ {$processedCount} adet ödeme başarıyla işlendi.";
        } elseif ($processedCount > 0 && $skippedCount > 0) {
            $message = "✅ {$processedCount} adet ödeme işlendi, ⚠️ {$skippedCount} adet zaten ödenmiş (atlandı).";
        } else {
            $message = "⚠️ Tüm seçilen aidatlar zaten ödenmiş durumda.";
        }

        return redirect()->route('admin.payments.create')->with('success', $message);
    }

    /**
     * Check if member has payments
     */
    public function checkMemberPayments($memberId)
    {
        $member = Member::find($memberId);
        if (!$member) {
            return response()->json(['hasPayments' => false]);
        }

        $hasPayments = Payment::where('member_id', $memberId)->exists();

        return response()->json(['hasPayments' => $hasPayments]);
    }

    /**
     * Generate bulk receipt for payments
     */
    public function generateBulkReceipt(Request $request)
    {
        $memberId = $request->get('member_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        if (!$memberId) {
            return redirect()->route('admin.payments.index')->with('error', 'Üye ID gerekli.');
        }

        $member = Member::find($memberId);
        if (!$member) {
            return redirect()->route('admin.payments.index')->with('error', 'Üye bulunamadı.');
        }

        // Get payments for the member (optionally filtered by date range)
        $paymentsQuery = Payment::where('member_id', $memberId)
            ->with(['dues', 'due'])
            ->orderBy('payment_date', 'desc');

        if ($dateFrom) {
            $paymentsQuery->whereDate('payment_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $paymentsQuery->whereDate('payment_date', '<=', $dateTo);
        }

        $payments = $paymentsQuery->get();

        if ($payments->isEmpty()) {
            return redirect()->route('admin.payments.index')->with('error', 'Bu üye için seçilen tarih aralığında ödeme bulunamadı.');
        }

        $totalAmount = $payments->sum('amount');
        $amountInWords = $this->numberToWords($totalAmount);
        $settings = Settings::first();

        // Kayıt altına al: hangi üye için, hangi tarih aralığında, ne kadar tutarda belge üretildi
        DonationCertificate::create([
            'member_id'    => $member->id,
            'date_from'    => $dateFrom ?: null,
            'date_to'      => $dateTo ?: null,
            'total_amount' => $totalAmount,
            'created_by'   => Auth::id(),
        ]);

        return view('admin.payments.bulk-receipt-html', compact('member', 'payments', 'settings', 'totalAmount', 'amountInWords', 'dateFrom', 'dateTo'));
    }

    /**
     * Daha önce bu üye için (ve isteğe bağlı tarih aralığında) belge oluşturulmuş mu kontrol et
     */
    public function checkExistingCertificate(Request $request)
    {
        $memberId = $request->get('member_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        if (!$memberId) {
            return response()->json(['hasCertificate' => false]);
        }

        $query = DonationCertificate::where('member_id', $memberId);

        // Tarih aralığı verilmişse, bu aralıkla çakışan herhangi bir belge var mı diye kaba bir kontrol yapalım
        if ($dateFrom) {
            $query->where(function ($q) use ($dateFrom) {
                $q->whereNull('date_from')
                    ->orWhere('date_from', '<=', $dateFrom);
            });
        }

        if ($dateTo) {
            $query->where(function ($q) use ($dateTo) {
                $q->whereNull('date_to')
                    ->orWhere('date_to', '>=', $dateTo);
            });
        }

        $existing = $query->orderBy('created_at', 'desc')->first();

        if (!$existing) {
            return response()->json(['hasCertificate' => false]);
        }

        return response()->json([
            'hasCertificate' => true,
            'certificate' => [
                'id'           => $existing->id,
                'date_from'    => $existing->date_from,
                'date_to'      => $existing->date_to,
                'total_amount' => $existing->total_amount,
                'created_at'   => optional($existing->created_at)->toDateTimeString(),
            ],
        ]);
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load(['member', 'dues', 'due', 'recordedBy']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Convert number to words in German
     */
    private function numberToWords($number)
    {
        $intPart = (int)$number;

        if ($intPart == 0) return 'null';

        // German number words
        $ones = ['', 'ein', 'zwei', 'drei', 'vier', 'fünf', 'sechs', 'sieben', 'acht', 'neun'];
        $teens = ['zehn', 'elf', 'zwölf', 'dreizehn', 'vierzehn', 'fünfzehn',
                  'sechzehn', 'siebzehn', 'achtzehn', 'neunzehn'];
        $tens = ['', '', 'zwanzig', 'dreißig', 'vierzig', 'fünfzig',
                 'sechzig', 'siebzig', 'achtzig', 'neunzig'];

        if ($intPart >= 1000000) {
            // Millionen
            $millions = (int)($intPart / 1000000);
            $result = $this->convertThreeDigits($millions, $ones, $teens, $tens);
            $result .= ($millions == 1) ? ' Million' : ' Millionen';
            $intPart %= 1000000;
            if ($intPart > 0) {
                $result .= ' ' . $this->numberToWords($intPart);
            }
            return $result;
        }

        if ($intPart >= 1000) {
            // Tausende
            $thousands = (int)($intPart / 1000);
            $result = '';
            if ($thousands == 1) {
                $result = 'eintausend';
            } else {
                $result = $this->convertThreeDigits($thousands, $ones, $teens, $tens) . 'tausend';
            }
            $intPart %= 1000;
            if ($intPart > 0) {
                $result .= $this->convertThreeDigits($intPart, $ones, $teens, $tens);
            }
            return $result;
        }

        return $this->convertThreeDigits($intPart, $ones, $teens, $tens);
    }

    /**
     * Convert numbers 0-999 to German words
     */
    private function convertThreeDigits($number, $ones, $teens, $tens)
    {
        $result = '';

        // Hundreds
        if ($number >= 100) {
            $hundreds = (int)($number / 100);
            if ($hundreds == 1) {
                $result .= 'einhundert';
            } else {
                $result .= $ones[$hundreds] . 'hundert';
            }
            $number %= 100;
        }

        // Tens and ones
        if ($number >= 20) {
            $onesDigit = $number % 10;
            $tensDigit = (int)($number / 10);

            if ($onesDigit > 0) {
                // German reverses the order: "einundzwanzig" (one and twenty)
                $result .= $ones[$onesDigit] . 'und' . $tens[$tensDigit];
            } else {
                $result .= $tens[$tensDigit];
            }
        } elseif ($number >= 10) {
            // Teens (10-19)
            $result .= $teens[$number - 10];
        } elseif ($number > 0) {
            // Ones (1-9)
            $result .= $ones[$number];
        }

        return $result;
    }

    /**
     * Process monthly payments
     */
    public function processMonthlyPayments(Request $request)
    {
        $validated = $request->validate([
            'selected_dues' => 'required|array',
            'selected_dues.*' => 'exists:dues,id',
            'payment_date' => 'required|date',
        ]);

        $dues = Due::with('member')->whereIn('id', $validated['selected_dues'])->get();
        $duesByMember = $dues->groupBy('member_id');
        $processedCount = 0;
        $skippedCount = 0;

        foreach ($duesByMember as $memberId => $memberDues) {
            $member = $memberDues->first()->member;

            // Check if any of the dues are already paid
            $alreadyPaidDues = $memberDues->where('status', 'paid');
            if ($alreadyPaidDues->count() > 0) {
                $skippedCount++;
                continue; // Skip this member if any due is already paid
            }

            // Double check with Payment model - multiple checks
            foreach ($memberDues as $due) {
                // 1. Bu aidat zaten ödenmiş mi?
                if (Payment::isDueAlreadyPaid($due->id)) {
                    $skippedCount++;
                    continue 2; // Skip this member if any due is already paid
                }

                // 2. Bu üye için aynı ay/yıl için başka bir ödeme var mı?
                if (Payment::hasMemberPaidForMonth($memberId, $due->year, $due->month)) {
                    $skippedCount++;
                    continue 2; // Skip this member if any due is already paid
                }

                // 3. Aidat durumu kontrolü
                if ($due->status === 'paid') {
                    $skippedCount++;
                    continue 2; // Skip this member if any due is already paid
                }
            }

            $totalAmount = $memberDues->sum('amount');

            // Create payment record
            $payment = Payment::create([
                'member_id' => $memberId,
                'amount' => $totalAmount,
                'payment_method' => $member->payment_method ?? 'bank_transfer',
                'payment_date' => $validated['payment_date'],
                'recorded_by' => auth()->id(),
            ]);

            // Link payment to dues and update their status
            foreach ($memberDues as $due) {
                $payment->dues()->attach($due->id, ['amount' => $due->amount]);
                $due->update([
                    'status' => 'paid',
                    'paid_date' => $due->due_date // ✅ Aidatın ait olduğu ayın tarihi
                ]);
            }

            $processedCount++;
        }

        $message = "{$processedCount} üyenin ödemesi başarıyla işlendi.";
        if ($skippedCount > 0) {
            $message .= " {$skippedCount} üye atlandı (zaten ödenmiş aidatlar var).";
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        try {
            // Get related dues before deleting payment
            $relatedDues = $payment->dues;

            \Log::info('Payment deletion - Found ' . $relatedDues->count() . ' related dues');

            // Update each due to its appropriate status based on due date
            foreach ($relatedDues as $due) {
                $newStatus = 'pending'; // Default to pending

                // If due date has passed, set to overdue
                if ($due->due_date < now()) {
                    $newStatus = 'overdue';
                }

                \Log::info("Updating due ID {$due->id} from '{$due->status}' to '{$newStatus}'");

                $due->update([
                    'status' => $newStatus,
                    'paid_date' => null
                ]);
            }

            // Detach dues relationship
            $payment->dues()->detach();

            // Log access (DSGVO - Veri erişim kaydı) - Silmeden önce logla
            \App\Models\AccessLog::create([
                'member_id' => $payment->member_id,
                'user_id' => auth()->id(),
                'action' => 'payment_delete',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'details' => [
                    'payment_id' => $payment->id,
                    'amount' => $payment->amount,
                    'payment_date' => $payment->payment_date ? $payment->payment_date->format('Y-m-d') : null,
                    'related_due_ids' => $relatedDues->pluck('id')->toArray(),
                ],
            ]);

            // Delete the payment
            $payment->delete();


            return redirect()->back()
                ->with('success', 'Ödeme başarıyla silindi ve aidatlar eski durumlarına döndürüldü.');
        } catch (\Exception $e) {
            \Log::error('Payment deletion failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Ödeme silinirken bir hata oluştu.');
        }
    }

    /**
     * Bulk delete payments
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,id',
        ]);

        try {
            $payments = Payment::whereIn('id', $validated['payment_ids'])->get();
            $deletedCount = 0;

            foreach ($payments as $payment) {
                // Get related dues before deleting payment
                $relatedDues = $payment->dues;

                // Update each due to its appropriate status based on due date
                foreach ($relatedDues as $due) {
                    $newStatus = 'pending'; // Default to pending

                    // If due date has passed, set to overdue
                    if ($due->due_date < now()) {
                        $newStatus = 'overdue';
                    }

                    $due->update([
                        'status' => $newStatus,
                        'paid_date' => null
                    ]);
                }

                // Detach dues relationship
                $payment->dues()->detach();

                // Delete the payment
                $payment->delete();
                $deletedCount++;
            }

            return redirect()->route('admin.payments.index')
                ->with('success', "{$deletedCount} ödeme başarıyla silindi ve aidatlar eski durumlarına döndürüldü.");
        } catch (\Exception $e) {
            \Log::error('Bulk payment deletion failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Ödemeler silinirken bir hata oluştu.');
        }
    }
}
