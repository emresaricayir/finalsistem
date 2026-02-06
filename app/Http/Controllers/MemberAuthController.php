<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payment;
use App\Models\Settings;
use App\Models\DeletionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

class MemberAuthController extends Controller
{
    /**
     * Show member login form
     */
    public function showLoginForm()
    {
        $settings = [
            'organization_name' => Settings::get('organization_name', 'Cami Derneği'),
            'organization_address' => Settings::get('organization_address', ''),
            'organization_phone' => Settings::get('organization_phone', ''),
            'organization_fax' => Settings::get('organization_fax', ''),
            'organization_email' => Settings::get('organization_email', 'info@camidernegi.com'),
            'form_title' => Settings::get('form_title', 'Üye Girişi'),
            'logo' => Settings::get('logo'),
        ];
        return view('member.auth.login', compact('settings'));
    }

    /**
     * Handle member login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $member = Member::where('email', $request->email)->first();

        if (!$member) {
            throw ValidationException::withMessages([
                'email' => [__('common.member_not_found')],
            ]);
        }

        if ($member->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => [__('common.member_status_not_active')],
            ]);
        }

        if ($member->application_status !== 'approved') {
            throw ValidationException::withMessages([
                'email' => [__('common.application_not_approved')],
            ]);
        }

        // Password kontrolü - sadece hash kontrolü (güvenlik için)
        if (!Hash::check($request->password, $member->password)) {
            throw ValidationException::withMessages([
                'email' => [__('common.password_incorrect')],
            ]);
        }

        // Store member in session
        session(['member_id' => $member->id]);
        session(['member_name' => $member->name . ' ' . $member->surname]);

        return redirect()->route('member.dashboard');
    }

    /**
     * Show member dashboard
     */
    public function dashboard()
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->route('member.login');
        }

        $member = Member::findOrFail($memberId);

        // Get member's dues
        $dues = $member->dues()->orderBy('due_date', 'desc')->get();
        $unpaidDues = $dues->where('status', 'pending');
        $paidDues = $dues->where('status', 'paid');

        // Get recent payments (last 5)
        $recentPayments = $member->payments()->with('due')->orderBy('payment_date', 'desc')->limit(5)->get();

        // Get all payments for detailed view
        $allPayments = $member->payments()->with('due')->orderBy('payment_date', 'desc')->get();

        $settings = [
            'organization_name' => Settings::get('organization_name'),
        ];

        return view('member.dashboard', compact('member', 'unpaidDues', 'paidDues', 'recentPayments', 'allPayments', 'settings'));
    }

    /**
     * Show member payments
     */
    public function payments()
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->route('member.login');
        }

        $member = Member::findOrFail($memberId);

        // Get all payments for detailed view with pagination
        $allPayments = $member->payments()->with(['due', 'dues'])->orderBy('payment_date', 'desc')->paginate(12);

        // Sort payments by month of their dues
        $allPayments->getCollection()->transform(function($payment) {
            if($payment->dues && $payment->dues->count() > 0) {
                $payment->sortOrder = $payment->dues->min('month');
            } else {
                $payment->sortOrder = 999;
            }
            return $payment;
        });

        // Sort the collection
        $sortedPayments = $allPayments->getCollection()->sortBy('sortOrder');
        $allPayments->setCollection($sortedPayments);

        // Group dues by year for each payment and sort months
        foreach($allPayments as $payment) {
            if($payment->dues && $payment->dues->count() > 0) {
                $payment->duesByYear = $payment->dues->groupBy('year')->map(function($yearDues) {
                    return $yearDues->sortBy(function($due) {
                        return (int) $due->month;
                    })->values();
                });
            }
        }

        // Get member's unpaid dues
        $unpaidDues = $member->dues()->where('status', '!=', 'paid')->orderBy('due_date', 'desc')->get();

        $settings = [
            'organization_name' => Settings::get('organization_name'),
        ];

        return view('member.payments', compact('member', 'allPayments', 'unpaidDues', 'settings'));
    }

    /**
     * Show member profile
     */
    public function profile()
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->route('member.login');
        }

        $member = Member::findOrFail($memberId);
        $settings = [
            'organization_name' => Settings::get('organization_name'),
        ];

        return view('member.profile', compact('member', 'settings'));
    }

    /**
     * Update member profile
     */
    public function updateProfile(Request $request)
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->route('member.login');
        }

        $member = Member::findOrFail($memberId);

        $validated = $request->validate([
            'email' => 'nullable|email|unique:members,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'address' => 'required|string',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        // Check if email is being updated from temporary to real email
        $isUpdatingFromTemporary = false;
        if ($request->filled('email') && str_contains($member->email, '@uye.com')) {
            $isUpdatingFromTemporary = true;
        }

        // Update basic info
        $updateData = [
            'phone' => $validated['phone'],
            'birth_date' => $validated['birth_date'],
            'birth_place' => $validated['birth_place'],
            'nationality' => $validated['nationality'],
            'occupation' => $validated['occupation'],
            'address' => $validated['address'],
        ];

        // Update email if provided and different from current
        if ($request->filled('email') && $request->email !== $member->email) {
            $updateData['email'] = $request->email;
        }

        $member->update($updateData);

        // Update password if provided
        if ($request->filled('new_password')) {
            // Mevcut şifre kontrolü - sadece hash kontrolü (güvenlik için)
            if (!Hash::check($request->current_password, $member->password)) {
                return back()->withErrors(['current_password' => 'Mevcut şifre hatalı.']);
            }

            $member->update([
                'password' => Hash::make($request->new_password)
            ]);
        }

        // Success message based on what was updated
        $message = 'Profil bilgileriniz güncellendi.';
        if ($isUpdatingFromTemporary) {
            $message = 'Email adresiniz başarıyla güncellendi. Artık gerçek email adresinizle giriş yapabilirsiniz.';
        }

        return back()->with('success', $message);
    }



    /**
     * Generate membership certificate PDF
     */
    public function generateCertificate()
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->route('member.login');
        }

        $member = Member::findOrFail($memberId);

        // Only active members can get certificate
        if ($member->status !== 'active') {
            return redirect()->route('member.dashboard')
                ->with('error', 'Sadece aktif üyeler belge alabilir.');
        }

        $settings = [
            'organization_name' => Settings::get('organization_name', 'Cami Derneği'),
            'organization_subtitle' => Settings::get('organization_subtitle', ''),
            'organization_address' => Settings::get('organization_address', ''),
            'organization_phone' => Settings::get('organization_phone', ''),
            'organization_fax' => Settings::get('organization_fax', ''),
            'organization_email' => Settings::get('organization_email', 'info@camidernegi.com'),
            'pdf_president_name' => Settings::get('pdf_president_name', 'Başkan Adı'),
            'logo' => Settings::get('logo', ''),
        ];

        $pdf = \PDF::loadView('member.certificate-pdf', compact('member', 'settings'));

        return $pdf->download('uye-belgesi-' . $member->member_no . '.pdf');
    }

    /**
     * Generate receipt PDF for member's payment
     */
    public function generateReceipt(Payment $payment)
    {
        try {
            $memberId = session('member_id');
            if (!$memberId) {
                return redirect()->route('member.login');
            }

            // Ensure payment belongs to logged-in member
            if ($payment->member_id !== $memberId) {
                return redirect()->route('member.dashboard')
                    ->with('error', 'Bu makbuz size ait değil.');
            }

            $payment->load(['member', 'due']);

            $settings = [
                'organization_name' => Settings::get('organization_name', 'Cami Derneği'),
                'organization_address' => Settings::get('organization_address', ''),
                'organization_phone' => Settings::get('organization_phone', ''),
                'organization_fax' => Settings::get('organization_fax', ''),
                'organization_email' => Settings::get('organization_email', 'info@camidernegi.com'),
                'tax_number' => Settings::get('tax_number', 'Hannover Land II, StNr.27/209/02246'),
                'tax_office_date' => Settings::get('tax_office_date', '2021-11-11'),
            ];

            // Try PDF first, if fails return HTML
            try {
                $pdf = \PDF::loadView('admin.payments.receipt-pdf-simple', compact('payment', 'settings'));
                $receiptFileName = 'makbuz-' . ($payment->receipt_no ?: 'NO-' . $payment->id) . '.pdf';
                return $pdf->stream($receiptFileName);
            } catch (\Exception $pdfError) {
                \Log::error('PDF generation failed, falling back to HTML: ' . $pdfError->getMessage());

                // Return HTML view instead
                return view('admin.payments.receipt-html', compact('payment', 'settings'));
            }
        } catch (\Exception $e) {
            \Log::error('Member PDF generation error: ' . $e->getMessage());
            \Log::error('Member PDF generation error trace: ' . $e->getTraceAsString());

            // Return a simple error response
            return response()->json([
                'error' => 'Makbuz oluşturulurken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show contact and donation page
     */
    public function contact()
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->route('member.login');
        }

        $member = Member::findOrFail($memberId);
        $settings = [
            'organization_name' => Settings::get('organization_name'),
            'organization_address' => Settings::get('organization_address'),
            'organization_phone' => Settings::get('organization_phone'),
            'organization_fax' => Settings::get('organization_fax'),
            'organization_email' => Settings::get('organization_email'),
            'bank_name' => Settings::get('bank_name'),
            'account_holder' => Settings::get('account_holder'),
            'bank_iban' => Settings::get('bank_iban'),
            'bank_bic' => Settings::get('bank_bic'),
            'paypal_link' => Settings::get('paypal_link'),
        ];

        return view('member.contact', compact('member', 'settings'));
    }

    /**
     * Logout member
     */
    public function logout(Request $request)
    {
        $request->session()->forget(['member_id', 'member_name']);
        return redirect()->route('member.login')->with('success', 'Başarıyla çıkış yaptınız.');
    }

    /**
     * Show member's application form (view only)
     */
    public function viewApplication()
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->route('member.login');
        }

        $member = Member::findOrFail($memberId);

        return view('member.application-view', compact('member'));
    }

    /**
     * View member's application as HTML (instead of PDF download)
     */
    public function viewApplicationHtml()
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->route('member.login');
        }

        $member = Member::findOrFail($memberId);

        $settings = [
            'organization_name' => Settings::get('organization_name', 'Cami Derneği'),
            'organization_address' => Settings::get('organization_address', ''),
            'organization_phone' => Settings::get('organization_phone', ''),
            'organization_fax' => Settings::get('organization_fax', ''),
            'organization_email' => Settings::get('organization_email', 'info@camidernegi.com'),
            'organization_subtitle' => Settings::get('organization_subtitle'),
            'certificate_title' => Settings::get('certificate_title'),
            'president_name' => Settings::get('president_name'),
            'president_title' => Settings::get('president_title'),
            'pdf_president_name' => Settings::get('pdf_president_name'),
            'bank_name' => Settings::get('bank_name', ''),
            'account_holder' => Settings::get('account_holder', ''),
            'bank_iban' => Settings::get('bank_iban', ''),
            'bank_bic' => Settings::get('bank_bic', ''),
            'bank_purpose' => Settings::get('bank_purpose', 'Aidat Ödemesi'),
            'logo' => Settings::get('logo', ''),
        ];

        return view('member.application-html', compact('member', 'settings'));
    }

    /**
     * Export member data (DSGVO - Right to data portability)
     */
    public function exportData($format = 'json')
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->route('member.login');
        }

        $member = Member::findOrFail($memberId);

        // Collect all member data
        $memberData = [
            'export_date' => now()->format('Y-m-d H:i:s'),
            'member_information' => [
                'member_no' => $member->member_no,
                'name' => $member->name,
                'surname' => $member->surname,
                'gender' => $member->gender,
                'email' => $member->email,
                'phone' => $member->phone,
                'birth_date' => $member->birth_date ? $member->birth_date->format('Y-m-d') : null,
                'birth_place' => $member->birth_place,
                'nationality' => $member->nationality,
                'address' => $member->address,
                'occupation' => $member->occupation,
                'family_members_count' => $member->family_members_count,
                'membership_date' => $member->membership_date ? $member->membership_date->format('Y-m-d') : null,
                'status' => $member->status,
                'application_status' => $member->application_status,
                'application_date' => $member->application_date ? $member->application_date->format('Y-m-d H:i:s') : null,
                'approved_at' => $member->approved_at ? $member->approved_at->format('Y-m-d H:i:s') : null,
                'privacy_consent' => $member->privacy_consent,
                'privacy_consent_date' => $member->privacy_consent_date ? $member->privacy_consent_date->format('Y-m-d H:i:s') : null,
            ],
            'payment_information' => [
                'monthly_dues' => (float) $member->monthly_dues,
                'payment_method' => $member->payment_method,
                'payment_frequency' => $member->payment_frequency,
                'payment_due_date' => $member->payment_due_date ? $member->payment_due_date->format('Y-m-d') : null,
                'sepa_agreement' => $member->sepa_agreement,
                'mandate_number' => $member->mandate_number,
                'account_holder' => $member->account_holder,
                'bank_name' => $member->bank_name,
                'iban' => $member->iban,
                'bic' => $member->bic,
            ],
            'payments' => $member->payments()->withoutTrashed()->orderBy('payment_date', 'desc')->get()->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => (float) $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'payment_date' => $payment->payment_date ? $payment->payment_date->format('Y-m-d') : null,
                    'receipt_no' => $payment->receipt_no,
                    'description' => $payment->description,
                    'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
                ];
            })->toArray(),
        ];

        // Log access (DSGVO - Veri erişim kaydı)
        // Not: Üye kendi verisini indirdiği için user_id = null (kendi işlemi)
        \App\Models\AccessLog::create([
            'member_id' => $member->id,
            'user_id' => null, // Üye kendi verisini indiriyor
            'action' => 'export',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details' => [
                'format' => $format,
                'exported_by_member' => true,
            ],
        ]);

        if ($format === 'json') {
            return response()->json($memberData, 200, [
                'Content-Type' => 'application/json; charset=utf-8',
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ->header('Content-Disposition', 'attachment; filename="meine-daten-' . $member->member_no . '-' . now()->format('Y-m-d') . '.json"');
        }

        // PDF format
        $settings = [
            'organization_name' => Settings::get('organization_name', 'Cami Derneği'),
            'organization_address' => Settings::get('organization_address', ''),
            'organization_phone' => Settings::get('organization_phone', ''),
            'organization_email' => Settings::get('organization_email', 'info@camidernegi.com'),
        ];

        try {
            // Render the view to HTML first
            $html = view('member.data-export-pdf', compact('memberData', 'member', 'settings'))->render();
            
            // Load HTML and set options
            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'DejaVu Sans',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ]);
            
            return $pdf->download('meine-daten-' . $member->member_no . '-' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF export error: ' . $e->getMessage());
            return redirect()->route('member.profile')
                ->with('error', 'PDF oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Request data deletion (DSGVO - Right to erasure)
     */
    public function requestDeletion(Request $request)
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->route('member.login');
        }

        $member = Member::findOrFail($memberId);

        // Check if there's already a pending request
        $existingRequest = DeletionRequest::where('member_id', $member->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'Zaten bekleyen bir silme talebiniz var.');
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:1000',
            'confirm' => 'required|accepted',
        ]);

        DeletionRequest::create([
            'member_id' => $member->id,
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Silme talebiniz başarıyla gönderildi. Yönetim kurulu tarafından değerlendirilecektir.');
    }

    /**
     * Withdraw privacy consent (DSGVO - Right to withdraw consent)
     */
    public function withdrawPrivacyConsent(Request $request)
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->route('member.login');
        }

        $member = Member::findOrFail($memberId);

        if (!$member->privacy_consent) {
            return back()->with('error', 'Zaten gizlilik politikası rızanız bulunmuyor.');
        }

        $validated = $request->validate([
            'confirm' => 'required|accepted',
        ]);

        $member->update([
            'privacy_consent' => false,
            'privacy_consent_date' => null,
        ]);

        return back()->with('success', 'Gizlilik politikası rızanız başarıyla geri çekildi.');
    }
}
