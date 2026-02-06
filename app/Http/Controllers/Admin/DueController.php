<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Due;
use App\Models\Member;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DueController extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'accountant'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $query = Due::with('member');

        // Filtreler
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }
        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        $dues = $query->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(50);

        $members = Member::where('status', 'active')->orderBy('surname')->get();
        $years = Due::distinct()->pluck('year')->sort();

        return view('admin.dues.index', compact('dues', 'members', 'years'));
    }

    /**
     * Display the specified resource
     */
    public function show(Due $due)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'accountant'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $due->load('member', 'paymentDues.payment');

        return view('admin.dues.show', compact('due'));
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy(Due $due)
    {
        if (!auth()->user()->hasAnyRole(['super_admin'])) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        // Log access (DSGVO - Veri erişim kaydı) - Silmeden önce logla
        \App\Models\AccessLog::create([
            'member_id' => $due->member_id,
            'user_id' => auth()->id(),
            'action' => 'due_delete',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details' => [
                'due_id' => $due->id,
                'amount' => $due->amount,
                'year' => $due->year,
                'month' => $due->month,
                'status' => $due->status,
            ],
        ]);

        $due->delete();

        return redirect()->route('admin.dues.index')
            ->with('success', 'Aidat başarıyla silindi.');
    }

    /**
     * Gecikmiş aidatları göster
     */
    public function overdue(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'accountant'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        // Filtreler
        $paymentMethod = $request->get('payment_method');
        $year = $request->get('year');
        $month = $request->get('month');

        // 🛡️ GÜVENLİK: Referans tarih mantığına göre gecikmiş aidatları al
        $referenceDate = \App\Services\DuesValidationService::getReferenceDate();

        // Gecikmiş aidatları al - join ile members tablosunu bağlayıp soyada göre sıralama
        $query = Due::join('members', 'dues.member_id', '=', 'members.id')
            ->where('members.status', 'active')
            ->where('dues.status', 'overdue')
            ->where('dues.due_date', '<', now())
            ->where(function($q) use ($referenceDate) {
                // Üyelik tarihi 01.01.2025'ten önce olanlar için sadece 2025 ve sonrası aidatlar
                $q->where(function($subQ) use ($referenceDate) {
                    $subQ->where('members.membership_date', '<', $referenceDate)
                         ->where('dues.year', '>=', 2025);
                })
                // Üyelik tarihi 01.01.2025'ten sonra olanlar için üyelik tarihinden sonraki aidatlar
                ->orWhere(function($subQ) use ($referenceDate) {
                    $subQ->where('members.membership_date', '>=', $referenceDate)
                         ->where('dues.due_date', '>=', \DB::raw('members.membership_date'));
                });
            });

        // Ödeme yöntemi filtresi
        if ($paymentMethod) {
            $query->where('members.payment_method', $paymentMethod);
        }

        // Yıl filtresi
        if ($year) {
            $query->where('dues.year', $year);
        }

        // Ay filtresi
        if ($month) {
            $query->where('dues.month', $month);
        }

        // Tüm gecikmiş aidatları al ve üyelere göre grupla
        $allOverdueDues = $query->orderBy('dues.year', 'asc')
            ->orderBy('dues.month', 'asc')
            ->select('dues.*')
            ->with('member')
            ->get();

        // Üyelere göre grupla
        $groupedByMember = $allOverdueDues->groupBy('member_id')->map(function ($dues, $memberId) {
            $member = $dues->first()->member;
            return [
                'member' => $member,
                'dues' => $dues->sortBy('due_date'),
                'total_amount' => $dues->sum('amount'),
                'dues_count' => $dues->count(),
                'oldest_due_date' => $dues->min('due_date'),
            ];
        });

        // Türkçe karakterlere uygun sıralama - PHP seviyesinde
        $groupedByMember = $groupedByMember->sortBy(function($item) {
            $surname = mb_strtolower($item['member']->surname ?? '', 'UTF-8');
            // Türkçe karakterleri normalize et (ı->i, ğ->g, ç->c, ş->s, ö->o, ü->u)
            $turkishToEnglish = [
                'ç' => 'c', 'ğ' => 'g', 'ı' => 'i', 'ö' => 'o',
                'ş' => 's', 'ü' => 'u',
                'Ç' => 'C', 'Ğ' => 'G', 'İ' => 'I', 'Ö' => 'O',
                'Ş' => 'S', 'Ü' => 'U'
            ];
            // Normalize edilmiş soyad ile sırala
            $normalized = strtr($surname, $turkishToEnglish);
            return $normalized;
        }, SORT_REGULAR, false)->values();

        // Paginate edilmiş üye listesi oluştur
        $perPage = 50;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $paginatedMembers = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupedByMember->slice($offset, $perPage),
            $groupedByMember->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $overdueDues = $paginatedMembers;

        // Filtrelenmiş istatistikler - 01.01.2025 mantığına göre
        $filteredQuery = Due::join('members', 'dues.member_id', '=', 'members.id')
            ->where('members.status', 'active')
            ->where('dues.status', 'overdue')
            ->where('dues.due_date', '<', now())
            ->where(function($q) use ($referenceDate) {
                // Üyelik tarihi 01.01.2025'ten önce olanlar için sadece 2025 ve sonrası aidatlar
                $q->where(function($subQ) use ($referenceDate) {
                    $subQ->where('members.membership_date', '<', $referenceDate)
                         ->where('dues.year', '>=', 2025);
                })
                // Üyelik tarihi 01.01.2025'ten sonra olanlar için üyelik tarihinden sonraki aidatlar
                ->orWhere(function($subQ) use ($referenceDate) {
                    $subQ->where('members.membership_date', '>=', $referenceDate)
                         ->where('dues.due_date', '>=', \DB::raw('members.membership_date'));
                });
            });

        if ($paymentMethod) {
            $filteredQuery->where('members.payment_method', $paymentMethod);
        }
        if ($year) {
            $filteredQuery->where('dues.year', $year);
        }
        if ($month) {
            $filteredQuery->where('dues.month', $month);
        }

        $totalOverdueCount = $filteredQuery->count();
        $totalOverdueAmount = $filteredQuery->sum('dues.amount');
        $distinctOverdueMembers = $filteredQuery->distinct()->count('dues.member_id');

        $stats = [
            'total_overdue' => $totalOverdueCount,
            'distinct_members' => $distinctOverdueMembers,
            'total_amount' => $totalOverdueAmount,
        ];

        // Filtre seçenekleri
        $paymentMethods = [
            'cash' => 'Nakit',
            'bank_transfer' => 'Banka Transferi',
            'lastschrift_monthly' => 'Lastschrift (Aylık)',
            'lastschrift_semi_annual' => 'Lastschrift (6 Aylık)',
            'lastschrift_annual' => 'Lastschrift (Yıllık)'
        ];
        // Yıl filtresi - 01.01.2025 mantığına göre yılları göster
        $yearsQuery = Due::join('members', 'dues.member_id', '=', 'members.id')
            ->where('members.status', 'active')
            ->where('dues.status', 'overdue')
            ->where('dues.due_date', '<', now())
            ->where(function($q) use ($referenceDate) {
                // Üyelik tarihi 01.01.2025'ten önce olanlar için sadece 2025 ve sonrası aidatlar
                $q->where(function($subQ) use ($referenceDate) {
                    $subQ->where('members.membership_date', '<', $referenceDate)
                         ->where('dues.year', '>=', 2025);
                })
                // Üyelik tarihi 01.01.2025'ten sonra olanlar için üyelik tarihinden sonraki aidatlar
                ->orWhere(function($subQ) use ($referenceDate) {
                    $subQ->where('members.membership_date', '>=', $referenceDate)
                         ->where('dues.due_date', '>=', \DB::raw('members.membership_date'));
                });
            });

        $years = $yearsQuery->distinct()
            ->pluck('dues.year')
            ->push(Carbon::now()->year) // Aktif yılı ekle
            ->unique()
            ->sort();
        $months = [
            1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
            5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
            9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
        ];

        return view('admin.dues.overdue', compact('overdueDues', 'stats', 'paymentMethods', 'years', 'months'));
    }

    /**
     * Bulk payment processing - Her aidat için ayrı ödeme kaydı oluştur
     */
    public function bulkPayment(Request $request)
    {
        $validated = $request->validate([
            'selected_dues' => 'required|array',
            'selected_dues.*' => 'exists:dues,id',
            'payment_date' => 'required|date',
        ]);

        $dues = Due::with('member')->whereIn('id', $validated['selected_dues'])->get();
        $createdPayments = [];
        $processedCount = 0;
        $skippedCount = 0;

        // Her aidat için ayrı ödeme kaydı oluştur
        \DB::transaction(function () use ($dues, &$createdPayments, &$processedCount, &$skippedCount) {
            foreach ($dues as $due) {
                // Check for duplicate payments - multiple checks
                // 1. Bu aidat zaten ödenmiş mi?
                if (Payment::isDueAlreadyPaid($due->id)) {
                    $skippedCount++;
                    continue; // Skip this due if already paid
                }

                // 2. Bu üye için aynı ay/yıl için başka bir ödeme var mı?
                if (Payment::hasMemberPaidForMonth($due->member_id, $due->year, $due->month)) {
                    $skippedCount++;
                    continue; // Skip this due if already paid
                }

                // 3. Aidat durumu kontrolü
                if ($due->status === 'paid') {
                    $skippedCount++;
                    continue; // Skip this due if already paid
                }

                // Create individual payment for each due
                $payment = Payment::create([
                    'member_id' => $due->member_id,
                    'amount' => $due->amount, // Individual due amount
                    'payment_method' => $due->member->payment_method ?? 'bank_transfer',
                    'payment_date' => $due->due_date, // Each due's own date
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

        return redirect()->back()->with('success', $message);
    }

    /**
     * Get all dues for bulk operations (across all pages)
     */
    public function selectAllPages(Request $request)
    {
        $paymentMethod = $request->get('payment_method');
        $year = $request->get('year');
        $month = $request->get('month');

        $query = Due::join('members', 'dues.member_id', '=', 'members.id')
            ->where('members.status', 'active')
            ->where('dues.status', 'overdue')
            ->where('dues.due_date', '<', now());

        if ($paymentMethod) {
            $query->where('members.payment_method', $paymentMethod);
        }
        if ($year) {
            $query->where('dues.year', $year);
        }
        if ($month) {
            $query->where('dues.month', $month);
        }

        $dueIds = $query->pluck('dues.id');
        $totalCount = $dueIds->count();
        $totalAmount = $query->sum('dues.amount');

        return response()->json([
            'success' => true,
            'due_ids' => $dueIds,
            'total_count' => $totalCount,
            'total_amount' => number_format($totalAmount, 2),
            'message' => "Toplam {$totalCount} aidat seçildi (Toplam: {$totalAmount} €)"
        ]);
    }

    /**
     * Bulk create dues for multiple members
     */
    public function bulkCreate(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'accountant'])) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $validated = $request->validate([
            'member_ids' => 'required|array',
            'member_ids.*' => 'exists:members,id',
            'year' => 'required|integer|min:2024',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $createdCount = 0;
        foreach ($validated['member_ids'] as $memberId) {
            $member = Member::find($memberId);

            // Check if due already exists
            $existingDue = Due::withTrashed()
                ->where('member_id', $memberId)
                ->where('year', $validated['year'])
                ->where('month', $validated['month'])
                ->first();

            if ($existingDue && $existingDue->trashed()) {
                $existingDue->restore();
                $createdCount++;
                
                // Log access (DSGVO - Veri erişim kaydı)
                \App\Models\AccessLog::create([
                    'member_id' => $memberId,
                    'user_id' => auth()->id(),
                    'action' => 'due_create',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'details' => [
                        'due_id' => $existingDue->id,
                        'amount' => $existingDue->amount,
                        'year' => $validated['year'],
                        'month' => $validated['month'],
                        'action_type' => 'restored',
                    ],
                ]);
            } elseif (!$existingDue) {
                $newDue = Due::create([
                    'member_id' => $memberId,
                    'year' => $validated['year'],
                    'month' => $validated['month'],
                    'amount' => $member->monthly_dues,
                    'due_date' => Carbon::create($validated['year'], $validated['month'])->endOfMonth(),
                    'status' => 'pending',
                ]);
                $createdCount++;
                
                // Log access (DSGVO - Veri erişim kaydı)
                \App\Models\AccessLog::create([
                    'member_id' => $memberId,
                    'user_id' => auth()->id(),
                    'action' => 'due_create',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'details' => [
                        'due_id' => $newDue->id,
                        'amount' => $newDue->amount,
                        'year' => $validated['year'],
                        'month' => $validated['month'],
                        'action_type' => 'created',
                    ],
                ]);
            }
        }

        return redirect()->back()->with('success', "{$createdCount} aidat başarıyla oluşturuldu.");
    }

    /**
     * Generate monthly dues for all active members
     */
    public function generateMonthly(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin'])) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $validated = $request->validate([
            'year' => 'required|integer|min:2024',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $activeMembers = Member::where('status', 'active')->get();
        $createdCount = 0;

        foreach ($activeMembers as $member) {
            // Check if due already exists
            $existingDue = Due::withTrashed()
                ->where('member_id', $member->id)
                ->where('year', $validated['year'])
                ->where('month', $validated['month'])
                ->first();

            if ($existingDue && $existingDue->trashed()) {
                $existingDue->restore();
                $createdCount++;
                
                // Log access (DSGVO - Veri erişim kaydı)
                \App\Models\AccessLog::create([
                    'member_id' => $member->id,
                    'user_id' => auth()->id(),
                    'action' => 'due_create',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'details' => [
                        'due_id' => $existingDue->id,
                        'amount' => $existingDue->amount,
                        'year' => $validated['year'],
                        'month' => $validated['month'],
                        'action_type' => 'restored',
                        'bulk_operation' => true,
                    ],
                ]);
            } elseif (!$existingDue) {
                $newDue = Due::create([
                    'member_id' => $member->id,
                    'year' => $validated['year'],
                    'month' => $validated['month'],
                    'amount' => $member->monthly_dues,
                    'due_date' => Carbon::create($validated['year'], $validated['month'])->endOfMonth(),
                    'status' => 'pending',
                ]);
                $createdCount++;
                
                // Log access (DSGVO - Veri erişim kaydı)
                \App\Models\AccessLog::create([
                    'member_id' => $member->id,
                    'user_id' => auth()->id(),
                    'action' => 'due_create',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'details' => [
                        'due_id' => $newDue->id,
                        'amount' => $newDue->amount,
                        'year' => $validated['year'],
                        'month' => $validated['month'],
                        'action_type' => 'created',
                        'bulk_operation' => true,
                    ],
                ]);
            }
        }

        return redirect()->back()->with('success', "{$createdCount} aylık aidat başarıyla oluşturuldu.");
    }
}
