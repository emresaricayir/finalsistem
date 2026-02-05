<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationMember;
use App\Models\EducationDue;
use App\Imports\EducationMembersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class EducationMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EducationMember::query();
        // Build available years dynamically from existing education dues
        $availableYears = \App\Models\EducationDue::selectRaw('YEAR(due_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Default selected year: latest available year, fallback to current year
        $defaultYear = $availableYears->first() ?: now()->year;
        $year = (int) $request->get('year', $defaultYear);

        // Search functionality with Turkish character normalization
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                // Normalize search term for Turkish characters
                $normalizedTerm = $this->normalizeTurkishChars(mb_strtolower(trim($search)));

                // Create SQL for normalizing Turkish characters in database fields
                $normalizeSQL = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(%s), 'ş', 's'), 'ı', 'i'), 'ğ', 'g'), 'ü', 'u'), 'ö', 'o'), 'ç', 'c'), 'İ', 'i')";

                $q->whereRaw(sprintf($normalizeSQL, 'name') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'surname') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'student_name') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'student_surname') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'email') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'phone') . ' LIKE ?', ["%{$normalizedTerm}%"]);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Sort functionality
        $sortField = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');

        $allowedSortFields = ['name', 'surname', 'membership_date', 'monthly_dues', 'status'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'name';
        }

        if ($sortField === 'name') {
            $query->orderBy('name', $sortDirection)->orderBy('surname', $sortDirection);
        } elseif ($sortField === 'surname') {
            $query->orderBy('surname', $sortDirection)->orderBy('name', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $members = $query->with(['dues' => function ($query) use ($year) {
                $query->whereYear('due_date', $year)->orderBy('due_date', 'desc');
            }, 'payments'])
            ->paginate(20)->appends($request->query());

        // Get total counts for stats
        $totalMembers = EducationMember::count();
        $activeMembers = EducationMember::active()->count();
        $inactiveMembers = EducationMember::inactive()->count();
        // Suspended status not used in UI anymore

        return view('admin.education-members.index', compact(
            'members',
            'totalMembers',
            'activeMembers',
            'inactiveMembers',
            'year',
            'availableYears'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.education-members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'student_name' => 'required|string|max:255',
            'student_surname' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,suspended',
            'membership_date' => 'required|date',
            'monthly_dues' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $educationMember = EducationMember::create($request->only([
            'name',
            'surname',
            'student_name',
            'student_surname',
            'email',
            'phone',
            'status',
            'membership_date',
            'monthly_dues',
            'notes',
        ]));

        // Generate dues for the rest of the current year starting from membership month
        $this->generateMemberDuesForYear($educationMember, now()->year);

        return redirect()->route('admin.education-members.index')
            ->with('success', 'Eğitim üyesi başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EducationMember $educationMember)
    {
        $educationMember->load('dues');
        return view('admin.education-members.show', compact('educationMember'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EducationMember $educationMember)
    {
        return view('admin.education-members.edit', compact('educationMember'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EducationMember $educationMember)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'student_name' => 'required|string|max:255',
            'student_surname' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,suspended',
            'membership_date' => 'required|date',
            'monthly_dues' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $originalMonthlyDues = $educationMember->monthly_dues;
        $educationMember->update($request->only([
            'name',
            'surname',
            'student_name',
            'student_surname',
            'email',
            'phone',
            'status',
            'membership_date',
            'monthly_dues',
            'notes',
        ]));

        // If monthly_dues changed, update pending/future dues amounts for current and future months
        if ($originalMonthlyDues != $educationMember->monthly_dues) {
            $currentYear = now()->year;
            // Ensure dues exist for the current year for this member
            $this->generateMemberDuesForYear($educationMember, $currentYear);

            // Update only dues that are still pending and due today or in the future
            \App\Models\EducationDue::where('education_member_id', $educationMember->id)
                ->where(function($q) use ($currentYear) {
                    $q->whereYear('due_date', '>=', $currentYear);
                })
                ->where('status', 'pending')
                ->update(['amount' => $educationMember->monthly_dues]);
        }

        return redirect()->route('admin.education-members.index')
            ->with('success', 'Eğitim üyesi başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EducationMember $educationMember)
    {
        $educationMember->delete();

        return redirect()->route('admin.education-members.index')
            ->with('success', 'Eğitim üyesi başarıyla silindi.');
    }

    /**
     * Generate monthly dues for all active education members
     */
    public function generateAnnualDues(Request $request)
    {
        $year = (int) $request->get('year', now()->year);

        $activeMembers = EducationMember::active()->get();
        $createdCount = 0;
        $updatedCount = 0;

        DB::transaction(function () use ($activeMembers, $year, &$createdCount, &$updatedCount) {
            foreach ($activeMembers as $member) {
                [$c, $u] = $this->generateMemberDuesForYear($member, $year);
                $createdCount += $c;
                $updatedCount += $u;
            }
        });

        $msg = [];
        if ($createdCount > 0) { $msg[] = "Oluşturulan: {$createdCount}"; }
        if ($updatedCount > 0) { $msg[] = "Güncellenen: {$updatedCount}"; }
        if (empty($msg)) { $msg[] = 'Tüm aidatlar güncel'; }

        return redirect()->back()->with('success', "{$year} yılı için işlem tamamlandı. " . implode(' - ', $msg));
    }

    /**
     * Complete missing dues for a specific year
     */
    // completeMissingDues removed: annual generator now handles creation and updates idempotently

    /**
     * Generate or update dues for a member for a given year.
     * - Always considers the full target year (January to December), regardless of membership date.
     * - Skips existing records; updates amount when status is pending and amount differs.
     * Returns [createdCount, updatedCount].
     */
    private function generateMemberDuesForYear(EducationMember $member, int $year): array
    {
        $createdCount = 0;
        $updatedCount = 0;

        if ($member->monthly_dues <= 0) {
            return [0, 0];
        }

        // Always from January to December of the target year
        $startMonth = 1;

        for ($month = $startMonth; $month <= 12; $month++) {
            $monthlyDueDate = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();

            $existingDue = EducationDue::where('education_member_id', $member->id)
                ->whereYear('due_date', $year)
                ->whereMonth('due_date', $month)
                ->first();

            if (!$existingDue) {
                EducationDue::create([
                    'education_member_id' => $member->id,
                    'amount' => $member->monthly_dues,
                    'due_date' => $monthlyDueDate,
                    'status' => 'pending',
                ]);
                $createdCount++;
            } else if ($existingDue->status === 'pending' && (float)$existingDue->amount !== (float)$member->monthly_dues) {
                $existingDue->update(['amount' => $member->monthly_dues]);
                $updatedCount++;
            }
        }

        return [$createdCount, $updatedCount];
    }

    /**
     * Mark due as paid
     */
    public function markPaid(Request $request, EducationDue $educationDue)
    {
        $request->validate([
            'paid_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer',
            'notes' => 'nullable|string',
        ]);

        // Create payment record
        $payment = \App\Models\EducationPayment::create([
            'education_member_id' => $educationDue->education_member_id,
            'amount' => $educationDue->amount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->paid_date,
            'notes' => $request->notes,
            'recorded_by' => auth()->id(),
        ]);

        // Update due record
        $educationDue->update([
            'status' => 'paid',
            'paid_date' => $request->paid_date,
            'payment_id' => $payment->id,
            'notes' => $request->notes,
        ]);

        return redirect()->back()
            ->with('success', 'Aidat ödemesi başarıyla kaydedildi.');
    }

    /**
     * Export education members to Excel
     */
    public function export(Request $request)
    {
        $query = EducationMember::query();

        // Apply same filters as index method
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                // Normalize search term for Turkish characters
                $normalizedTerm = $this->normalizeTurkishChars(mb_strtolower(trim($search)));

                // Create SQL for normalizing Turkish characters in database fields
                $normalizeSQL = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(%s), 'ş', 's'), 'ı', 'i'), 'ğ', 'g'), 'ü', 'u'), 'ö', 'o'), 'ç', 'c'), 'İ', 'i')";

                $q->whereRaw(sprintf($normalizeSQL, 'name') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'surname') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'student_name') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'student_surname') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'email') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'phone') . ' LIKE ?', ["%{$normalizedTerm}%"]);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Get year filter (default to current year or latest year with dues)
        $availableYears = \App\Models\EducationDue::selectRaw('YEAR(due_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        $defaultYear = $availableYears->first() ?: now()->year;
        $year = (int) $request->get('year', $defaultYear);

        // Load dues for the selected year
        $members = $query->with(['dues' => function ($query) use ($year) {
                $query->whereYear('due_date', $year)->orderBy('due_date', 'asc');
            }])
            ->orderBy('name')
            ->orderBy('surname')
            ->get();

        $filename = 'egitim_uyeleri_' . $year . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\EducationMembersExport($members, $year),
            $filename
        );
    }

    /**
     * Show import form
     */
    public function import()
    {
        return view('admin.education-members.import');
    }

    /**
     * Download Excel template
     */
    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\EducationMembersTemplateExport(),
            'egitim_uyeleri_sablon.xlsx'
        );
    }

    /**
     * Process import
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
        ]);

        try {
            $import = new EducationMembersImport;
            Excel::import($import, $request->file('file'));

            $importedCount = $import->getImportedCount();
            $skippedCount = $import->getSkippedCount();
            $errors = $import->getErrors();

            // Import işleminden sonra yeni eklenen üyeler için aidat oluştur
            if ($importedCount > 0) {
                $newMembers = EducationMember::where('created_at', '>=', now()->subMinutes(5))
                    ->where('notes', 'Excel ile içe aktarıldı')
                    ->get();
                
                foreach ($newMembers as $member) {
                    $this->generateMemberDuesForYear($member, now()->year);
                }
            }

            $message = "İçe aktarma tamamlandı. ";
            $message .= "İçe aktarılan: {$importedCount} üye, ";
            $message .= "Atlanan: {$skippedCount} üye.";

            if (!empty($errors)) {
                $message .= " Hatalar: " . implode(', ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= " ve " . (count($errors) - 5) . " hata daha.";
                }
            }

            return redirect()->route('admin.education-members.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'İçe aktarma sırasında hata oluştu: ' . $e->getMessage());
        }
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
}
