<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationPayment;
use App\Models\EducationMember;
use App\Models\EducationDue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EducationPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Show paid dues instead of payments
        $query = \App\Models\EducationDue::with(['educationMember'])
            ->where('status', 'paid');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('educationMember', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%")
                  ->orWhere('student_name', 'like', "%{$search}%")
                  ->orWhere('student_surname', 'like', "%{$search}%");
            });
        }

        // Date range filter (based on due_date, not paid_date)
        if ($request->filled('date_from')) {
            $query->where('due_date', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('due_date', '<=', $request->get('date_to'));
        }

        // Month filter (based on due_date, not paid_date)
        if ($request->filled('year')) {
            $query->whereYear('due_date', $request->get('year'));
        }
        if ($request->filled('month')) {
            $query->whereMonth('due_date', $request->get('month'));
        }

        $payments = $query->orderBy('paid_date', 'desc')->paginate(20)->appends($request->query());

        // Get statistics (based on due_date, not paid_date)
        $totalPayments = \App\Models\EducationDue::where('status', 'paid')->count();
        $totalAmount = \App\Models\EducationDue::where('status', 'paid')->sum('amount');
        $thisMonthPayments = \App\Models\EducationDue::where('status', 'paid')
            ->whereMonth('due_date', now()->month)
            ->whereYear('due_date', now()->year)
            ->count();
        $thisMonthAmount = \App\Models\EducationDue::where('status', 'paid')
            ->whereMonth('due_date', now()->month)
            ->whereYear('due_date', now()->year)
            ->sum('amount');

        return view('admin.education-payments.index', compact(
            'payments',
            'totalPayments',
            'totalAmount',
            'thisMonthPayments',
            'thisMonthAmount'
        ));
    }

    /**
     * Show bulk payment form
     */
    public function bulkPayment(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        // Get members with unpaid dues (pending or overdue) for the selected month
        $members = EducationMember::active()->with(['dues' => function($query) use ($year, $month) {
            $query->whereIn('status', ['pending', 'overdue'])
                  ->whereYear('due_date', $year)
                  ->whereMonth('due_date', $month);
        }])->get();

        // Filter out members who don't have pending dues
        $members = $members->filter(function($member) {
            return $member->dues->count() > 0;
        });

        // Get available years for filter (only current and previous year)
        $availableYears = collect([now()->year, now()->year - 1]);

        // Always show all 12 months
        $availableMonths = collect(range(1, 12));

        return view('admin.education-payments.bulk', compact('members', 'year', 'month', 'availableYears', 'availableMonths'));
    }

    /**
     * Process bulk payment
     */
    public function processBulkPayment(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer',
            'selected_members' => 'required|array',
            'selected_members.*' => 'exists:education_members,id',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $year = $request->year;
        $month = $request->month;
        $selectedMembers = $request->selected_members;
        $paymentDate = $request->payment_date;
        $notes = $request->notes;

        $processedCount = 0;
        $totalAmount = 0;

        DB::transaction(function () use ($year, $month, $selectedMembers, $paymentDate, $notes, &$processedCount, &$totalAmount) {
            foreach ($selectedMembers as $memberId) {
                // Find the due for this member, year and month
                $due = EducationDue::where('education_member_id', $memberId)
                    ->whereYear('due_date', $year)
                    ->whereMonth('due_date', $month)
                    ->whereIn('status', ['pending', 'overdue'])
                    ->first();

                if ($due) {
                    // Create a payment record for this due (no actual payment processing)
                    $payment = EducationPayment::create([
                        'education_member_id' => $due->education_member_id,
                        'amount' => $due->amount,
                        'payment_method' => 'cash',
                        'payment_date' => $paymentDate,
                        'notes' => $notes,
                        'recorded_by' => auth()->id(),
                    ]);

                    // Mark due as paid and link to payment record
                    $due->update([
                        'status' => 'paid',
                        'paid_date' => $paymentDate,
                        'payment_id' => $payment->id,
                        'notes' => $notes,
                    ]);

                    $processedCount++;
                    $totalAmount += $due->amount;
                }
            }
        });

        $monthNames = [
            1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
            5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
            9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
        ];

        if ($processedCount > 0) {
            return redirect()->route('admin.education-members.index')
                ->with('success', "{$year} yılı {$monthNames[$month]} ayı için {$processedCount} üyenin aidatı ödendi olarak işaretlendi. Toplam: €{$totalAmount}");
        } else {
            return redirect()->route('admin.education-members.index')
                ->with('warning', "Seçilen üyeler için işlem yapılamadı.");
        }
    }

    /**
     * Delete a paid due (mark as pending)
     */
    public function destroy($id)
    {
        $due = \App\Models\EducationDue::where('id', $id)->where('status', 'paid')->first();

        if (!$due) {
            return redirect()->route('admin.education-payments.index')
                ->with('error', 'Ödeme bulunamadı.');
        }

        DB::transaction(function () use ($due) {
            // First unlink from payment and mark pending
            $payment = $due->payment;
            $due->update([
                'status' => 'pending',
                'paid_date' => null,
                'payment_id' => null,
                'notes' => null,
            ]);

            // Then delete the payment record if it existed
            if ($payment) {
                $payment->delete();
            }
        });

        // If AJAX/JSON expected, return JSON
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        // If request came from toggle on members list, redirect back; otherwise to payments index
        if (url()->previous() && str_contains(url()->previous(), '/admin/education-members')) {
            return redirect()->back()->with('success', 'Ödeme silindi, aidat ödenmemişe alındı.');
        }

        return redirect()->route('admin.education-payments.index')
            ->with('success', 'Ödeme başarıyla silindi ve aidat bekleyen duruma getirildi.');
    }
}
