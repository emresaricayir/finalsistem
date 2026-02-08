<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Due;
use App\Models\Settings;
use App\Models\User;
use App\Models\DeletionRequest;
use App\Models\PrivacyConsentWithdrawal;
use App\Imports\MembersImport;
use App\Mail\MemberApprovalMail;
use App\Mail\AdminNewMemberNotificationMail;
use App\Mail\ApplicationRejected;
use App\Mail\OverdueDuesReminder;
use App\Services\EmailService;
use App\Services\DuesValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class MemberController extends Controller
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
        // Role checks are handled elsewhere
        $query = Member::query();

        // Search functionality with Turkish character normalization
        if ($request->filled('search')) {
            $searchTerm = $request->search;

            $query->where(function($q) use ($searchTerm) {
                // Normalize search term for Turkish characters
                $normalizedTerm = $this->normalizeTurkishChars(mb_strtolower(trim($searchTerm)));

                // Create SQL for normalizing Turkish characters in database fields
                $normalizeSQL = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(%s), 'ş', 's'), 'ı', 'i'), 'ğ', 'g'), 'ü', 'u'), 'ö', 'o'), 'ç', 'c'), 'İ', 'i')";

                $q->whereRaw(sprintf($normalizeSQL, 'name') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'surname') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'email') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'member_no') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'phone') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'occupation') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'birth_place') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'nationality') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'address') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'notes') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'account_holder') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'bank_name') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'iban') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, "CONCAT(name, ' ', surname)") . ' LIKE ?', ["%{$normalizedTerm}%"]);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Email type filter
        if ($request->filled('email_type')) {
            $temporaryEmailDomain = Settings::getTemporaryEmailDomain();
            if ($request->email_type === 'temporary') {
                $query->where('email', 'LIKE', '%@' . $temporaryEmailDomain);
            } elseif ($request->email_type === 'regular') {
                $query->where('email', 'NOT LIKE', '%@' . $temporaryEmailDomain);
            }
        }

        // Payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Phone presence filter
        if ($request->filled('phone_presence')) {
            if ($request->phone_presence === 'has') {
                $query->whereNotNull('phone')->where('phone', '!=', '');
            } elseif ($request->phone_presence === 'missing') {
                $query->where(function($q){
                    $q->whereNull('phone')->orWhere('phone', '=','');
                });
            }
        }

        // Address presence filter
        if ($request->filled('address_presence')) {
            if ($request->address_presence === 'has') {
                $query->whereNotNull('address')
                      ->where('address', '!=', '')
                      ->where('address', '!=', ' ');
            } elseif ($request->address_presence === 'missing') {
                $query->where(function($q){
                    $q->whereNull('address')
                      ->orWhere('address', '=', '')
                      ->orWhere('address', '=', ' ');
                });
            }
        }

        // Gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Sorting functionality - default to surname
        $sortField = $request->get('sort', 'surname');
        $sortDirection = $request->get('direction', 'asc');

        // Validate sort field to prevent SQL injection
        $allowedSortFields = ['name', 'surname', 'membership_date', 'created_at', 'monthly_dues', 'payment_status'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'name';
        }

        // Handle name and surname sorting
        if ($sortField === 'name') {
            $query->orderBy('name', $sortDirection)
                  ->orderBy('surname', $sortDirection);
        } elseif ($sortField === 'surname') {
            $query->orderBy('surname', $sortDirection)
                  ->orderBy('name', $sortDirection);
        } elseif ($sortField === 'payment_status') {
            // Sort by payment status: paid first, then overdue, then pending
            $query->orderByRaw("
                CASE
                    WHEN EXISTS (
                        SELECT 1 FROM dues
                        WHERE dues.member_id = members.id
                        AND dues.status = 'paid'
                        AND dues.due_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
                    ) THEN 1
                    WHEN EXISTS (
                        SELECT 1 FROM dues
                        WHERE dues.member_id = members.id
                        AND dues.status = 'overdue'
                    ) THEN 2
                    ELSE 3
                END {$sortDirection}
            ");
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $members = $query->paginate(18)->appends($request->query());

        // Get total counts for stats
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'active')->count();
        $inactiveMembers = Member::where('status', 'inactive')->count();
        $suspendedMembers = Member::where('status', 'suspended')->count();

        // Get all members for modal dropdown (without pagination)
        $allMembers = Member::orderBy('surname')->orderBy('name')->get();

        // Get pending deletion requests
        $pendingDeletionRequests = DeletionRequest::with(['member', 'reviewedBy'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get recent privacy consent withdrawals (not notified yet)
        $recentPrivacyWithdrawals = PrivacyConsentWithdrawal::with('member')
            ->where('notified', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.members.index', compact('members', 'allMembers', 'totalMembers', 'activeMembers', 'inactiveMembers', 'suspendedMembers', 'pendingDeletionRequests', 'recentPrivacyWithdrawals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'email' => 'required|email|unique:members,email',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'required|date',
            'birth_place' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'address' => 'required|string',
            'membership_date' => 'required|date',
            'monthly_dues' => 'required|numeric|min:' . \App\Models\Settings::getMinimumMonthlyDues(),
            'status' => 'required|in:active,inactive,suspended',
            'payment_method' => 'required|in:cash,bank_transfer,lastschrift_monthly,lastschrift_semi_annual,lastschrift_annual',
            'notes' => 'nullable|string',
            'signature' => 'nullable|string',
            'sepa_agreement' => 'nullable|boolean',
        ]);

        // Üye numarasını otomatik oluştur (11 karakter) - benzersiz olmasını garanti et
        $validated['member_no'] = $this->generateUniqueMemberNumber();

        // Application status ve password ekle
        $validated['application_status'] = 'approved';
        $validated['password'] = Hash::make($this->generatePassword($validated['birth_date']));

        // Activation token oluştur
        $validated['activation_token'] = Str::random(60);

        // Signature date ekle (eğer signature varsa)
        if (!empty($validated['signature'])) {
            $validated['signature_date'] = now();
        }

        // Set default payment method if not provided
        if (empty($validated['payment_method'])) {
            $validated['payment_method'] = 'bank_transfer';
        }

        $member = Member::create($validated);

        // Yıllık aidatları otomatik oluştur
        $this->createYearlyDues($member);

        // Hoş geldiniz e-postası gönder
        $this->sendWelcomeEmail($member);

        // Yöneticilere yeni üye bildirimi gönder
        $this->sendAdminNewMemberNotification($member);


        // Üye belgesini yeni sekmede aç
        return redirect()->route('member.application.pdf', $member->id)
            ->with('success', 'Üye başarıyla eklendi ve 10 yıllık aidatları oluşturuldu.')
            ->with('member_info', [
                'name' => $member->name . ' ' . $member->surname,
                'email' => $member->email,
                'member_no' => $member->member_no,
                'login_url' => url('/uye-giris'),
                'default_password' => $this->generatePassword($validated['birth_date'])
            ]);
    }

    /**
     * Generate unique member number
     */
    private function generateUniqueMemberNumber()
    {
        $maxAttempts = 1000; // Daha fazla deneme
        $attempt = 0;

        // En yüksek mevcut üye numarasını bul
        $lastMember = Member::where('member_no', 'LIKE', 'Mitglied%')
            ->orderByRaw('CAST(SUBSTRING(member_no, 9) AS UNSIGNED) DESC')
            ->first();

        // Başlangıç numarasını belirle
        if ($lastMember) {
            // Son numaradan bir sonrakini al
            $lastNumber = (int) substr($lastMember->member_no, 8); // "Mitglied" kısmını çıkar
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        do {
            $attempt++;

            if ($attempt > $maxAttempts) {
                // Eğer 1000 deneme sonunda da bulamazsa, timestamp kullan
                return 'Mitglied' . substr(time(), -3);
            }

            $memberNo = 'Mitglied' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // Bu numaranın kullanılıp kullanılmadığını kontrol et (soft delete dahil)
            $exists = Member::withTrashed()->where('member_no', $memberNo)->exists();
            
            // Force delete edilen üyelerin numaralarını da kontrol et (AccessLog snapshot'larından)
            $forceDeleted = false;
            if (!$exists) {
                $forceDeleted = \App\Models\AccessLog::where('action', 'force_delete')
                    ->whereNotNull('details')
                    ->whereRaw('JSON_EXTRACT(details, "$.member_snapshot.member_no") = ?', [json_encode($memberNo)])
                    ->exists();
            }

            // Eğer numara kullanılabilir değilse (exists veya forceDeleted), bir sonraki numarayı dene
            if ($exists || $forceDeleted) {
                $nextNumber++;
            }

        } while ($exists || $forceDeleted);

        return $memberNo;
    }

    /**
     * Generate password based on birth date
     */
    private function generatePassword($birthDate)
    {
        if (!$birthDate) {
            return '123456'; // Default password if no birth date
        }

        // Convert birth date to DDMMYYYY format
        $date = \Carbon\Carbon::parse($birthDate);
        return $date->format('dmY');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $member = Member::with([
            'dues.paymentDues',
            'dues.payments',
            'payments.due',
            'payments.dues',
            'payments.recordedBy',
        ])->findOrFail($id);

        // Log access (DSGVO - Veri erişim kaydı)
        \App\Models\AccessLog::create([
            'member_id' => $member->id,
            'user_id' => auth()->id(),
            'action' => 'view',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // JSON response for AJAX requests
        if (request()->wantsJson()) {
            return response()->json($member);
        }

        return view('admin.members.show', compact('member'));
    }

    /**
     * Generate password for member (admin panel)
     */
    public function createPasswordForMember(Request $request, string $id)
    {
        $member = Member::findOrFail($id);

        // Şifre zaten varsa hata döndür
        if (!is_null($member->password)) {
            return redirect()->route('admin.members.show', $member)
                ->with('error', 'Bu üyenin zaten bir şifresi var.');
        }

        // Activation token oluştur
        $member->update([
            'activation_token' => Str::random(60)
        ]);

        // Eğer geçici email değilse, şifre belirleme email'i gönder
        if (!str_contains($member->email, '@uye.com')) {
            try {
                $this->sendWelcomeEmail($member);
                return redirect()->route('admin.members.show', $member)
                    ->with('success', 'Şifre belirleme linki üyenin email adresine gönderildi.');
            } catch (\Exception $e) {
                \Log::error('Welcome email gönderilemedi (şifre oluşturma): ' . $e->getMessage(), [
                    'member_id' => $member->id,
                    'email' => $member->email
                ]);
                return redirect()->route('admin.members.show', $member)
                    ->with('warning', 'Activation token oluşturuldu ancak email gönderilemedi. Token: ' . $member->activation_token);
            }
        } else {
            // Geçici email'li üye için sadece token oluşturuldu bilgisi ver
            return redirect()->route('admin.members.show', $member)
                ->with('success', 'Activation token oluşturuldu. Üye gerçek email\'ini güncellediğinde şifre belirleme linki gönderilecek.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $member = Member::findOrFail($id);
        
        // Log access (DSGVO - Veri erişim kaydı)
        \App\Models\AccessLog::create([
            'member_id' => $member->id,
            'user_id' => auth()->id(),
            'action' => 'edit',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return view('admin.members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'email' => 'required|email|unique:members,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'member_no' => [
                'nullable',
                'string',
                'max:15',
                Rule::unique('members', 'member_no')
                    ->ignore($id), // Mevcut üyeyi ignore et
                    // Not: Laravel'in unique validation'ı default olarak soft deleted kayıtları da kontrol eder
                    // Bu sayede silinen üyelerin numaraları tekrar kullanılamaz
            ],
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended',
            'membership_date' => 'required|date',
            'monthly_dues' => 'required|numeric|min:' . \App\Models\Settings::getMinimumMonthlyDues(),
            'payment_method' => 'nullable|in:cash,bank_transfer,lastschrift_monthly,lastschrift_semi_annual,lastschrift_annual',
            'password' => 'nullable|string|min:6',
            'notes' => 'nullable|string',
            'signature' => 'nullable|string',
            'sepa_agreement' => 'nullable|boolean',
        ], [
            'member_no.unique' => 'Bu üye numarası zaten kullanılıyor (silinen üyeler dahil).',
            'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
        ]);

        // Üye numarası değişikliği kontrolü (sadece super admin)
        $memberNoChanged = false;
        if ($request->filled('member_no') && $member->member_no !== $request->member_no) {
            // Super admin kontrolü
            if (!auth()->user()->hasRole('super_admin')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Üye numarasını sadece super admin değiştirebilir.');
            }
            
            // Force delete edilen üyelerin numaralarını kontrol et (AccessLog snapshot'larından)
            // JSON içinde member_snapshot->member_no kontrolü
            // MySQL/MariaDB için JSON_EXTRACT kullanıyoruz (performans için)
            $forceDeletedMemberNo = \App\Models\AccessLog::where('action', 'force_delete')
                ->whereNotNull('details')
                ->whereRaw('JSON_EXTRACT(details, "$.member_snapshot.member_no") = ?', [json_encode($request->member_no)])
                ->exists();
            
            if ($forceDeletedMemberNo) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Bu üye numarası kalıcı olarak silinen bir üyeye aitti ve tekrar kullanılamaz.');
            }
            
            $memberNoChanged = true;
        }
        
        // Eğer üye numarası boş bırakılmışsa otomatik oluştur
        if (empty($validated['member_no'])) {
            $lastMember = Member::orderBy('id', 'desc')->first();
            $nextId = $lastMember ? $lastMember->id + 1 : 1;
            $validated['member_no'] = 'Mitglied' . str_pad($nextId, 3, '0', STR_PAD_LEFT); // 11 karakter
        }

        // Signature date güncelle (eğer yeni signature varsa)
        if (!empty($validated['signature']) && $validated['signature'] !== $member->signature) {
            $validated['signature_date'] = now();
        }

        // Şifre güncelleme: Eğer şifre girildiyse hash'le ve kaydet
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            // Şifre boş bırakıldıysa, mevcut şifreyi koru (validated'dan çıkar)
            unset($validated['password']);
        }

        // Üyelik tarihi, aylık aidat, ödeme yöntemi veya durum değişti mi kontrol et
        $membershipDateChanged = $member->membership_date != $validated['membership_date'];
        $monthlyDuesChanged = $member->monthly_dues != $validated['monthly_dues'];
        $paymentMethodChanged = $member->payment_method != $validated['payment_method'];
        $statusChanged = $member->status != $validated['status'];

        // Üye numarası değişikliği için özel log (güncellemeden önce)
        if ($memberNoChanged) {
            \App\Models\AccessLog::create([
                'member_id' => $member->id,
                'user_id' => auth()->id(),
                'action' => 'member_no_changed',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'details' => [
                    'old_member_no' => $member->member_no,
                    'new_member_no' => $validated['member_no'],
                    'changed_by' => auth()->user()->name,
                    'changed_at' => now()->toDateTimeString(),
                ],
            ]);
        }

        $member->update($validated);

        // Log access (DSGVO - Veri erişim kaydı)
        // Otomatik timestamp alanlarını filtrele (updated_at, created_at)
        $changes = $member->getChanges();
        $excludedFields = ['updated_at', 'created_at'];
        $changedFields = array_filter(
            array_keys($changes),
            fn($field) => !in_array($field, $excludedFields)
        );
        
        \App\Models\AccessLog::create([
            'member_id' => $member->id,
            'user_id' => auth()->id(),
            'action' => 'edit',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details' => [
                'changed_fields' => array_values($changedFields), // array_values ile indexleri sıfırla
            ],
        ]);

        // Eğer üye durumu değiştiyse, güvenlik kontrollerini yap
        if ($statusChanged) {
            // 🛡️ GÜVENLİK: Durum değişikliğini doğrula
            $statusValidation = DuesValidationService::validateStatusChange($member, $validated['status'], $member->status);

            if (!$statusValidation['is_valid']) {
                return redirect()->route('admin.members.index')
                    ->with('error', 'Durum değişikliği geçersiz: ' . implode(', ', $statusValidation['errors']));
            }
        }

        // Eğer üye durumu değiştiyse ve aktif hale geldiyse, aidat oluşturma mantığını kontrol et
        if ($statusChanged && $validated['status'] === 'active') {
            // 🛡️ GÜVENLİK: Durum değişikliğini doğrula
            $statusValidation = DuesValidationService::validateStatusChange($member, $validated['status'], $member->status);

            if (!$statusValidation['is_valid']) {
                Log::error('Durum değişikliği hatası', [
                    'member_id' => $member->id,
                    'errors' => $statusValidation['errors']
                ]);
                return redirect()->route('admin.members.index')
                    ->with('error', 'Durum değişikliği geçersiz: ' . implode(', ', $statusValidation['errors']));
            }

            // 🛡️ GÜVENLİK: Durum değişikliğini logla
            DuesValidationService::logStatusChange($member, $member->status, $validated['status'], $statusValidation);

            $this->handleMemberStatusChangeToActive($member);

            return redirect()->route('admin.members.index')
                ->with('success', 'Üye durumu aktif yapıldı ve aidatlar 01.01.2025 tarihine göre yeniden hesaplandı.');
        }

        // Eğer üye durumu değiştiyse ve pasif/askıya alındıysa, gelecekteki aidatları askıya al
        if ($statusChanged && in_array($validated['status'], ['inactive', 'suspended'])) {
            $this->handleMemberStatusChangeToInactive($member, $validated['status']);

            $statusText = $validated['status'] === 'inactive' ? 'pasif' : 'askıya alındı';
            return redirect()->route('admin.members.index')
                ->with('success', "Üye durumu {$statusText} yapıldı ve gelecekteki aidatlar askıya alındı.");
        }

        // Eğer üyelik tarihi değiştiyse aidatları yeniden hesapla
        if ($membershipDateChanged && !$monthlyDuesChanged) {
            $this->recalculateDuesForMembershipDateChange($member);
            return redirect()->route('admin.members.index')
                ->with('success', 'Üye bilgileri başarıyla güncellendi ve aidatlar üyelik tarihine göre yeniden hesaplandı.');
        }

        // Eğer aylık aidat değiştiyse, sadece gelecekteki ödenmemiş aidatları güncelle
        if ($monthlyDuesChanged) {
            $oldAmount = $member->monthly_dues; // Eski miktarı kaydet
            $this->updateFutureDuesAmount($member, $validated['monthly_dues'], $oldAmount);
            return redirect()->route('admin.members.index')
                ->with('success', 'Üye bilgileri başarıyla güncellendi ve gelecekteki aidatların tutarı güncellendi.');
        }

        // Eğer sadece ödeme yöntemi değiştiyse, mevcut ödenmemiş aidatları güncelle
        if ($paymentMethodChanged) {
            $this->updateUnpaidDuesPaymentMethod($member, $validated['payment_method']);
            return redirect()->route('admin.members.index')
                ->with('success', 'Üye bilgileri başarıyla güncellendi ve ödenmemiş aidatların ödeme yöntemi güncellendi.');
        }

        return redirect()->route('admin.members.index')
            ->with('success', 'Üye bilgileri başarıyla güncellendi.');
    }

    /**
     * Update payment method for unpaid dues
     */
    private function updateUnpaidDuesPaymentMethod(Member $member, $newPaymentMethod)
    {
        // Ödenmemiş aidatları bul ve güncelle
        $unpaidDues = $member->dues()
            ->whereIn('status', ['pending', 'overdue'])
            ->get();

        $updatedCount = 0;
        foreach ($unpaidDues as $due) {
            // Bu aidat için yapılan ödemeleri güncelle
            $due->payments()->update([
                'payment_method' => $newPaymentMethod
            ]);
            $updatedCount++;
        }

        return $updatedCount;
    }

    /**
     * Create yearly dues for new member
     */
    private function createYearlyDues(Member $member, $forceCreate = false)
    {
        // 🛡️ GÜVENLİK: Aidat oluşturma mantığını doğrula
        $validation = DuesValidationService::validateDuesCreationLogic($member);

        if (!$validation['is_valid']) {
            throw new \Exception('Aidat oluşturma mantığı geçersiz: ' . implode(', ', $validation['errors']));
        }

        // 🛡️ GÜVENLİK: Kritik durumları kontrol et
        $critical = DuesValidationService::checkCriticalConditions($member);
        if ($critical['has_critical_issues']) {
            if (!$forceCreate) {
                throw new \Exception('Kritik durum tespit edildi: ' . implode(', ', $critical['issues']));
            }
        }

        $startDate = $validation['start_date'];

        // 🛡️ GÜVENLİK: Mevcut aidat çakışmalarını kontrol et
        $conflicts = DuesValidationService::checkExistingDuesConflicts($member, $startDate);

        // 10 yıl boyunca her ay için aidat oluştur
        $currentDate = $startDate->copy();
        $endDate = $startDate->copy()->addYears(10);

        while ($currentDate->lte($endDate)) {
            // Bu ay için aidat var mı kontrol et (silinmiş olanları da dahil et)
            $existingDue = Due::withTrashed()
                ->where('member_id', $member->id)
                ->where('year', $currentDate->year)
                ->where('month', $currentDate->month)
                ->first();

            // Eğer silinmiş aidat varsa, geri yükle
            if ($existingDue && $existingDue->trashed()) {
                $existingDue->restore();
            }

            // Aidat oluştur veya güncelle
            $dueDate = $currentDate->copy()->endOfMonth();
            $status = 'pending';

            // Status hesaplama: sadece geçmiş tarihlerdeki aidatlar gecikmiş olur
            if ($dueDate->isPast()) {
                $status = 'overdue';
            } else {
                $status = 'pending';
            }

            // Önce mevcut aidatı kontrol et
            if ($existingDue && $existingDue->status === 'paid') {
                // Eğer aidat mevcut ve ödenmişse, güncelleme yapma
                $currentDate->addMonth();
                continue;
            }

            // Aidat oluştur veya güncelle - güvenli yaklaşım
            try {
                if ($existingDue) {
                    // Mevcut aidatı güncelle (sadece ödenmemiş olanları)
                    if ($existingDue->status !== 'paid') {
                        $existingDue->amount = $member->monthly_dues;
                        $existingDue->due_date = $dueDate;
                        $existingDue->status = $status;
                        $existingDue->save();
                    }
                } else {
                    // Yeni aidat oluştur
                    Due::create([
                        'member_id' => $member->id,
                        'year' => $currentDate->year,
                        'month' => $currentDate->month,
                        'amount' => $member->monthly_dues,
                        'due_date' => $dueDate,
                        'status' => $status,
                    ]);
                }
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // Eğer aynı anda başka bir işlem aynı kaydı oluşturduysa, mevcut kaydı bul ve güncelle
                $existingDue = Due::withTrashed()
                    ->where('member_id', $member->id)
                    ->where('year', $currentDate->year)
                    ->where('month', $currentDate->month)
                    ->first();

                // Eğer silinmiş aidat varsa, geri yükle
                if ($existingDue && $existingDue->trashed()) {
                    $existingDue->restore();
                }

                if ($existingDue && $existingDue->status !== 'paid') {
                    $existingDue->amount = $member->monthly_dues;
                    $existingDue->due_date = $dueDate;
                    $existingDue->status = $status;
                    $existingDue->save();
                }
            }

            $currentDate->addMonth();
        }
    }

    /**
     * Recalculate dues when membership date changes
     */
    private function recalculateDuesForMembershipDateChange(Member $member)
    {
        // 🛡️ GÜVENLİK: Aidat yeniden hesaplama mantığını doğrula
        $validation = DuesValidationService::validateDuesCreationLogic($member);

        if (!$validation['is_valid']) {
            throw new \Exception('Aidat yeniden hesaplama mantığı geçersiz: ' . implode(', ', $validation['errors']));
        }

        $startDate = $validation['start_date'];

        // Yeni başlangıç tarihinden ÖNCE kalan ödenmemiş aidatları sil
        Due::where('member_id', $member->id)
            ->where(function($query) use ($startDate) {
                $query->where('year', '<', $startDate->year)
                    ->orWhere(function($q) use ($startDate) {
                        $q->where('year', '=', $startDate->year)
                          ->where('month', '<', $startDate->month);
                    });
            })
            ->whereIn('status', ['pending', 'overdue'])
            ->delete();

        // 10 yıl boyunca her ay için aidat oluştur
        $currentDate = $startDate->copy();
        $endDate = $startDate->copy()->addYears(10);
        $createdCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;

        while ($currentDate->lte($endDate)) {
            // Bu ay için aidat var mı kontrol et (silinmiş olanları da dahil et)
            $existingDue = Due::withTrashed()
                ->where('member_id', $member->id)
                ->where('year', $currentDate->year)
                ->where('month', $currentDate->month)
                ->first();

            // Eğer silinmiş aidat varsa, geri yükle
            if ($existingDue && $existingDue->trashed()) {
                $existingDue->restore();
            }

            // Aidat oluştur veya güncelle
            $dueDate = $currentDate->copy()->endOfMonth();
            $status = 'pending';

            // Status hesaplama: sadece geçmiş tarihlerdeki aidatlar gecikmiş olur
            if ($dueDate->isPast()) {
                $status = 'overdue';
            } else {
                $status = 'pending';
            }

            // Önce mevcut aidatı kontrol et
            if ($existingDue && $existingDue->status === 'paid') {
                // Eğer aidat mevcut ve ödenmişse, güncelleme yapma
                $skippedCount++;
                $currentDate->addMonth();
                continue;
            }

            // Aidat oluştur veya güncelle - güvenli yaklaşım
            try {
                if ($existingDue) {
                    // Mevcut aidatı güncelle (sadece ödenmemiş olanları)
                    if ($existingDue->status !== 'paid') {
                        $existingDue->amount = $member->monthly_dues;
                        $existingDue->due_date = $dueDate;
                        $existingDue->status = $status;
                        $existingDue->save();
                        $updatedCount++;
                    }
                } else {
                    // Yeni aidat oluştur
                    Due::create([
                        'member_id' => $member->id,
                        'year' => $currentDate->year,
                        'month' => $currentDate->month,
                        'amount' => $member->monthly_dues,
                        'due_date' => $dueDate,
                        'status' => $status,
                    ]);
                    $createdCount++;
                }
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // Eğer aynı anda başka bir işlem aynı kaydı oluşturduysa, mevcut kaydı bul ve güncelle
                $existingDue = Due::withTrashed()
                    ->where('member_id', $member->id)
                    ->where('year', $currentDate->year)
                    ->where('month', $currentDate->month)
                    ->first();

                // Eğer silinmiş aidat varsa, geri yükle
                if ($existingDue && $existingDue->trashed()) {
                    $existingDue->restore();
                }

                if ($existingDue && $existingDue->status !== 'paid') {
                    $existingDue->amount = $member->monthly_dues;
                    $existingDue->due_date = $dueDate;
                    $existingDue->status = $status;
                    $existingDue->save();
                    $updatedCount++;
                } else if ($existingDue && $existingDue->status === 'paid') {
                    $skippedCount++;
                }
            }

            $currentDate->addMonth();
        }
    }

    /**
     * Update future dues amount when monthly dues change
     */
    private function updateFutureDuesAmount(Member $member, $newAmount, $oldAmount)
    {
        $now = now();

        // Aidat değişiklik tarihini ve eski miktarı kaydet
        $member->update([
            'monthly_dues_change_date' => $now,
            'previous_monthly_dues' => $oldAmount,
            'monthly_dues' => $newAmount
        ]);

        // Sadece gelecek aidatları güncelle
        $affectedRows = $member->dues()
            ->where('status', '!=', 'paid')
            ->where('due_date', '>', $now)
            ->where('amount', $oldAmount)
            ->update(['amount' => $newAmount]);

        \Log::info('Aidat güncelleme:', [
            'member_id' => $member->id,
            'old_amount' => $oldAmount,
            'new_amount' => $newAmount,
            'affected_rows' => $affectedRows
        ]);


        // Geçmiş aidatları eski miktarda bırak
        // Bu kısım gerekli değil çünkü yukarıdaki where koşulu sadece
        // gelecek ve bu ay içindeki aidatları seçiyor
    }

    /**
     * Send welcome email to new member
     */
    private function sendWelcomeEmail(Member $member)
    {
        try {
            // Use EmailService for centralized logging
            EmailService::sendMemberWelcome($member, [
                'sent_by' => auth()->user()->name ?? 'System',
                'recipient_name' => $member->name . ' ' . $member->surname,
            ]);
        } catch (\Exception $e) {
            \Log::error("Failed to send welcome email to {$member->email}: " . $e->getMessage());
            // Email gönderimi başarısız olsa bile üye kaydı devam etsin
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $request->validate([
            'deletion_reason' => 'required|string|max:1000',
        ]);

        $member = Member::findOrFail($id);

        // Silme nedenini kaydet
        $member->update([
            'deletion_reason' => $request->deletion_reason,
            'deleted_by' => auth()->id()
        ]);

        // Log access (DSGVO - Veri erişim kaydı)
        \App\Models\AccessLog::create([
            'member_id' => $member->id,
            'user_id' => auth()->id(),
            'action' => 'delete',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details' => [
                'deletion_reason' => $request->deletion_reason,
            ],
        ]);

        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Üye başarıyla silindi.');
    }

    /**
     * Show all applications (pending, approved, rejected)
     */
    public function pendingApplications()
    {
        $applications = Member::whereNotNull('application_date')
            ->orderByRaw("CASE
                WHEN application_status = 'pending' THEN 1
                WHEN application_status = 'rejected' THEN 2
                WHEN application_status = 'approved' THEN 3
                ELSE 4 END")
            ->orderBy('application_date', 'desc')
            ->paginate(15);

        return view('admin.members.pending-applications', compact('applications'));
    }

    /**
     * Approve membership application
     */
    public function approveApplication(Request $request, string $id)
    {
        $member = Member::findOrFail($id);

        $member->update([
            'application_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => auth()->user()->name,
        ]);

        // Yıllık aidatları oluştur
        $this->createYearlyDues($member);

        // Üyeye onay maili gönder
        $mailSent = $this->sendMemberApprovalEmail($member);

        $successMessage = 'Üyelik başvurusu onaylandı ve aidatları otomatik oluşturuldu.';
        if ($mailSent) {
            $successMessage .= ' Üyeye bilgilendirme maili gönderildi.';
        } else {
            $successMessage .= ' ⚠️ Mail gönderimi başarısız oldu, lütfen log dosyalarını kontrol edin.';
        }

        return redirect()->route('admin.members.pending-applications')
            ->with('success', $successMessage);
    }

    /**
     * Resend approval email to member
     */
    public function resendApprovalEmail(Request $request, string $id)
    {
        $member = Member::findOrFail($id);

        if ($member->application_status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Sadece onaylanmış üyelere mail gönderilebilir.');
        }

        $mailSent = $this->sendMemberApprovalEmail($member);

        if ($mailSent) {
            return redirect()->back()
                ->with('success', "Onay maili {$member->name} {$member->surname} adlı üyeye tekrar gönderildi.");
        } else {
            return redirect()->back()
                ->with('error', 'Mail gönderimi başarısız oldu, lütfen log dosyalarını kontrol edin.');
        }
    }

    /**
     * Reject membership application
     */
    public function rejectApplication(Request $request, string $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $member = Member::findOrFail($id);

        $member->update([
            'application_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Send rejection email to member
        try {
            // Use dynamic email template
            EmailService::sendApplicationRejected($member, [
                'sent_by' => auth()->user()->name ?? 'System',
                'recipient_name' => $member->name . ' ' . $member->surname,
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the operation
            \Log::error('Failed to send rejection email to member: ' . $e->getMessage());
        }

        // Create notification for admin
        try {
            \App\Models\Notification::create([
                'title' => 'Üyelik Başvurusu Reddedildi',
                'message' => $member->name . ' ' . $member->surname . ' adlı kişinin üyelik başvurusu reddedildi.',
                'type' => 'warning',
                'icon' => 'fa-user-times'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create notification: ' . $e->getMessage());
        }


        return redirect()->route('admin.members.pending-applications')
            ->with('success', 'Üyelik başvurusu reddedildi ve başvuru sahibine bilgilendirme maili gönderildi.');
    }

    /**
     * Send overdue dues reminder to a specific member
     */
    public function sendOverdueReminder(Request $request, string $id)
    {
        $member = Member::findOrFail($id);

        // months parametresi opsiyonel - verilmezse tüm gecikmiş aidatlar
        $request->validate([
            'months' => 'nullable|integer|min:1|max:12',
        ]);

        $months = $request->input('months');

        // Üyenin tüm gecikmiş aidatlarını al
        $overdueDuesQuery = $member->dues()
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->orderBy('due_date', 'asc');

        // Eğer months parametresi varsa, son X ay ile sınırla
        if ($months) {
            $cutoffDate = now()->subMonths($months);
            $overdueDuesQuery->where('due_date', '>=', $cutoffDate);
        }

        $overdueDuesCollection = $overdueDuesQuery->get();
        $totalOverdueAmount = $overdueDuesCollection->sum('amount');

        // Eğer gerçek aidat kaydı yoksa ve months belirtilmişse, aylık aidat üzerinden hesapla
        if ($overdueDuesCollection->isEmpty() && $months) {
            $estimatedAmount = $member->monthly_dues * $months;
            $overdueDuesCollection = collect([
                (object) [
                    'month_name' => 'Gecikmiş Aidat (' . $months . ' ay)',
                    'year' => date('Y'),
                    'amount' => $estimatedAmount,
                    'due_date' => now()->subMonths($months)->format('Y-m-d'),
                    'formatted_due_date' => now()->subMonths($months)->format('d.m.Y')
                ]
            ]);
            $totalOverdueAmount = $estimatedAmount;
        } else {
            // Gerçek aidat kayıtlarını formatla
            $overdueDuesCollection = $overdueDuesCollection->map(function ($due) {
                return (object) [
                    'month_name' => $due->month_name, // Accessor kullan
                    'year' => $due->year,
                    'amount' => $due->amount,
                    'due_date' => $due->due_date,
                    'formatted_due_date' => \Carbon\Carbon::parse($due->due_date)->format('d.m.Y')
                ];
            });
        }

        if ($totalOverdueAmount > 0) {
            try {
                // Detaylı aidat bilgileri ile email gönder
                EmailService::sendOverdueDuesReminder($member, $overdueDuesCollection, [
                    'sent_by' => auth()->user()->name,
                    'recipient_name' => $member->full_name,
                ]);

                // Bildirim oluştur
                \App\Models\Notification::create([
                    'title' => 'Aidat Hatırlatması Gönderildi',
                    'message' => "{$member->name} {$member->surname} adlı üyeye {$overdueDuesCollection->count()} adet gecikmiş aidat hatırlatması gönderildi. Toplam tutar: €" . number_format($totalOverdueAmount, 2),
                    'type' => 'info',
                    'icon' => 'fa-envelope'
                ]);

                // Log kaydı
                \Log::info('Overdue dues reminder sent', [
                    'member_id' => $member->id,
                    'member_name' => $member->name . ' ' . $member->surname,
                    'member_email' => $member->email,
                    'months_requested' => $months,
                    'total_amount' => $totalOverdueAmount,
                    'dues_count' => $overdueDuesCollection->count(),
                    'sent_by' => auth()->user()->email
                ]);

                return redirect()->back()->with('success',
                    "{$member->name} {$member->surname} adlı üyeye {$overdueDuesCollection->count()} adet gecikmiş aidat hatırlatması gönderildi. Toplam tutar: €" . number_format($totalOverdueAmount, 2)
                );
            } catch (\Exception $e) {
                // Hata logu
                \Log::error('Failed to send overdue dues reminder', [
                    'member_id' => $member->id,
                    'member_email' => $member->email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return redirect()->back()->with('error', 'Mail gönderilirken hata oluştu: ' . $e->getMessage());
            }
        } else {
            $errorMsg = $months
                ? "Bu üyenin son {$months} ay içinde gecikmiş aidatı bulunmuyor."
                : "Bu üyenin gecikmiş aidatı bulunmuyor.";
            return redirect()->back()->with('error', $errorMsg);
        }
    }

    /**
     * Send overdue dues reminders to all members with overdue dues
     */
    public function sendBulkOverdueReminders(Request $request)
    {
        $request->validate([
            'months' => 'required|integer|min:1|max:12',
        ]);

        $months = $request->months;
        $cutoffDate = now()->subMonths($months);

        // Get members with overdue dues
        $membersWithOverdueDues = Member::where('status', 'active')
            ->where('application_status', 'approved')
            ->whereHas('dues', function ($query) use ($cutoffDate) {
                $query->where('due_date', '<', $cutoffDate)
                      ->where('status', '!=', 'paid');
            })
            ->orWhereDoesntHave('dues', function ($query) use ($cutoffDate) {
                $query->where('due_date', '>=', $cutoffDate);
            })
            ->get();

        if ($membersWithOverdueDues->isEmpty()) {
            return redirect()->back()->with('error', 'Gecikmiş aidatı olan üye bulunamadı.');
        }

        $sentCount = 0;
        $failedCount = 0;

        // Generate unique batch ID for this bulk operation
        $batchId = 'overdue-reminder-' . now()->format('Y-m-d-H-i-s') . '-' . auth()->id();

        $totalAmountSent = 0;
        $totalDuesCount = 0;

        foreach ($membersWithOverdueDues as $member) {
            try {
                // Gerçek gecikmiş aidatları hesapla
                $overdueDuesQuery = $member->dues()
                    ->where('status', '!=', 'paid')
                    ->where('due_date', '<', now())
                    ->where('due_date', '>=', $cutoffDate)
                    ->orderBy('due_date', 'asc');

                $overdueDuesCollection = $overdueDuesQuery->get();
                $memberTotalOverdue = $overdueDuesCollection->sum('amount');

                // Eğer gerçek aidat kaydı yoksa, aylık aidat üzerinden hesapla
                if ($overdueDuesCollection->isEmpty()) {
                    $estimatedAmount = $member->monthly_dues * $months;
                    $overdueDuesCollection = collect([
                        (object) [
                            'month_name' => 'Gecikmiş Aidat (' . $months . ' ay)',
                            'year' => date('Y'),
                            'amount' => $estimatedAmount,
                            'due_date' => $cutoffDate->format('Y-m-d'),
                            'formatted_due_date' => $cutoffDate->format('d.m.Y')
                        ]
                    ]);
                    $memberTotalOverdue = $estimatedAmount;
                } else {
                    // Gerçek aidat kayıtlarını formatla
                    $overdueDuesCollection = $overdueDuesCollection->map(function ($due) {
                        return (object) [
                            'month_name' => $due->month_name, // Accessor kullan
                            'year' => $due->year,
                            'amount' => $due->amount,
                            'due_date' => $due->due_date,
                            'formatted_due_date' => \Carbon\Carbon::parse($due->due_date)->format('d.m.Y')
                        ];
                    });
                }

                if ($memberTotalOverdue > 0) {
                    // Detaylı aidat bilgileri ile email gönder
                    EmailService::sendOverdueDuesReminder($member, $overdueDuesCollection, [
                        'batch_id' => $batchId,
                        'sent_by' => auth()->user()->name,
                        'recipient_name' => $member->full_name,
                    ]);

                    $sentCount++;
                    $totalAmountSent += $memberTotalOverdue;
                    $totalDuesCount += $overdueDuesCollection->count();

                    // Her üye için log kaydı
                    \Log::info('Bulk overdue dues reminder sent', [
                        'batch_id' => $batchId,
                        'member_id' => $member->id,
                        'member_name' => $member->name . ' ' . $member->surname,
                        'member_email' => $member->email,
                        'total_amount' => $memberTotalOverdue,
                        'dues_count' => $overdueDuesCollection->count(),
                    ]);
                }
            } catch (\Exception $e) {
                $failedCount++;
                \Log::error("Failed to send bulk reminder to {$member->email}", [
                    'batch_id' => $batchId,
                    'member_id' => $member->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        // Toplu işlem log kaydı
        \Log::info('Bulk overdue dues reminder completed', [
            'batch_id' => $batchId,
            'total_members_processed' => $membersWithOverdueDues->count(),
            'successful_sends' => $sentCount,
            'failed_sends' => $failedCount,
            'total_amount_sent' => $totalAmountSent,
            'total_dues_count' => $totalDuesCount,
            'months_requested' => $months,
            'initiated_by' => auth()->user()->email
        ]);

        // Create notification
        \App\Models\Notification::create([
            'title' => 'Toplu Aidat Hatırlatması Tamamlandı',
            'message' => "{$sentCount} üyeye toplam {$totalDuesCount} adet gecikmiş aidat hatırlatması gönderildi. Toplam tutar: €" . number_format($totalAmountSent, 2) . ". Başarısız: {$failedCount}",
            'type' => $failedCount > 0 ? 'warning' : 'success',
            'icon' => 'fa-envelope'
        ]);

        $successMessage = "{$sentCount} üyeye toplam {$totalDuesCount} adet gecikmiş aidat hatırlatması gönderildi. Toplam tutar: €" . number_format($totalAmountSent, 2);
        if ($failedCount > 0) {
            $successMessage .= " (Başarısız: {$failedCount})";
        }

        return redirect()->back()->with('success', $successMessage);
    }

    /**
     * Show Excel import form
     */
    public function showImportForm()
    {
        return view('admin.members.import');
    }

    /**
     * Import members from Excel file
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            // Increase memory and execution time limits for large imports
            ini_set('memory_limit', '1024M'); // 1GB for large imports
            set_time_limit(1800); // 30 minutes for large files

            // Use database transaction to ensure all-or-nothing import
            $importedCount = 0;
            \DB::transaction(function () use ($request, &$importedCount) {
                // Import members first
                $import = new MembersImport();
                Excel::import($import, $request->file('excel_file'));

                // Get imported members count
                $importedCount = Member::where('created_at', '>=', now()->subMinutes(1))->count();

                // Create yearly dues for newly imported members in chunks
                $newMembers = Member::where('created_at', '>=', now()->subMinutes(1))->get();
                $newMembers->chunk(100)->each(function ($chunk) {
                    foreach ($chunk as $member) {
                        $this->createYearlyDues($member);
                    }
                });
            });

            return redirect()->route('admin.members.index')
                ->with('success', "Excel dosyasından {$importedCount} üye başarıyla içe aktarıldı ve aidatları oluşturuldu.");

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorDetails = [];
            $errorSummary = [];

            foreach ($failures as $failure) {
                $rowNumber = $failure->row();
                $attribute = $failure->attribute();
                $errors = $failure->errors();
                $values = $failure->values();

                // Satır bilgilerini topla
                $rowInfo = [];
                if (isset($values['ad'])) $rowInfo[] = "Ad: {$values['ad']}";
                if (isset($values['soyad'])) $rowInfo[] = "Soyad: {$values['soyad']}";
                $rowDescription = !empty($rowInfo) ? ' (' . implode(', ', $rowInfo) . ')' : '';

                // Her hata için detaylı bilgi
                foreach ($errors as $error) {
                    $errorDetails[] = [
                        'row' => $rowNumber,
                        'attribute' => $attribute,
                        'error' => $error,
                        'row_info' => $rowDescription
                    ];
                }

                // Özet için
                $errorSummary[] = "Satır {$rowNumber}{$rowDescription}: " . implode(', ', $errors);
            }

            // Hata detaylarını session'a kaydet
            session()->flash('excel_errors', $errorDetails);

            return redirect()->back()
                ->withErrors(['excel_file' => 'Excel dosyasında ' . count($failures) . ' satırda hata bulundu. Detaylar aşağıda gösterilmektedir.'])
                ->withInput();

        } catch (\Exception $e) {
            \Log::error('Excel import error: ' . $e->getMessage(), [
                'file' => $request->file('excel_file')->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);

            // Transaction automatically rolled back on exception
            return redirect()->back()
                ->withErrors(['excel_file' => 'Excel dosyası işlenirken beklenmeyen bir hata oluştu. Tüm değişiklikler geri alındı. Lütfen dosya formatını kontrol edin ve tekrar deneyin.'])
                ->withInput();
        }
    }

        /**
     * Download sample Excel template
     */
    public function downloadTemplate()
    {
        // Sample data for template with Turkish characters - including payment method, gender, and password
        $sampleData = [
            ['ad', 'soyad', 'cinsiyet', 'email', 'telefon', 'dogum_tarihi', 'dogum_yeri', 'uyruk', 'meslek', 'adres', 'uyelik_tarihi', 'aylik_aidat', 'odeme_yontemi', 'sifre', 'durum', 'notlar'],
            ['Ahmet', 'Yılmaz', 'Erkek', 'ahmet@example.com', '+49 123 456 789', '1990-01-15', 'İstanbul', 'Türkiye', 'Mühendis', 'Test Adres 1', '2025-01-01', '25.00', 'nakit', 'sifre123', 'active', 'Örnek üye 1 - Şifre belirtilmiş'],
            ['Fatma', 'Özdemir', 'Kadın', 'fatma@example.com', '+49 987 654 321', '1985-05-20', 'Ankara', 'Türkiye', 'Öğretmen', 'Test Adres 2', '2025-01-01', '20.00', 'banka', '', 'active', 'Örnek üye 2 - Şifre boş (admin panelinden oluşturulacak)'],
            ['Mehmet', 'Güler', 'Männlich', '', '+49 555 123 456', '1992-08-10', 'İzmir', 'Türkiye', 'Doktor', 'Test Adres 3', '2025-01-01', '30.00', 'lastschrift', '', 'active', 'Örnek üye 3 - Geçici email, şifre boş'],
            ['Ayşe', 'Kaya', 'Weiblich', 'ayse@example.com', '+49 444 789 012', '1988-12-05', 'Bursa', 'Türkiye', 'Avukat', 'Test Adres 4', '2025-01-01', '15.00', '', 'sifre456', 'active', 'Örnek üye 4 - Şifre belirtilmiş'],
        ];

        // Create Excel file using PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set UTF-8 encoding
        $sheet->setTitle('Üye Şablonu');

        // Start with clean template from row 1
        $sheet->fromArray($sampleData, null, 'A1');

        // Auto-size columns (now 16 columns: A-P)
        foreach (range('A', 'P') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Style the header row (row 1)
        $sheet->getStyle('A1:P1')->getFont()->setBold(true);
        $sheet->getStyle('A1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:P1')->getFill()->getStartColor()->setRGB('4F46E5'); // Indigo color
        $sheet->getStyle('A1:P1')->getFont()->getColor()->setRGB('FFFFFF'); // White text

        // Style the data rows
        $dataEndRow = count($sampleData);
        $sheet->getStyle('A2:P' . $dataEndRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A2:P' . $dataEndRow)->getFill()->getStartColor()->setRGB('F8FAFC'); // Light gray

        // Add borders
        $sheet->getStyle('A1:P' . $dataEndRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Create Excel writer
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Set headers for download
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="uye_sablonu.xlsx"',
            'Cache-Control' => 'no-cache, must-revalidate',
        ];

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, 'uye_sablonu.xlsx', $headers);
    }

    /**
     * Send member approval email
     */
    private function sendMemberApprovalEmail(Member $member)
    {
        try {
            $organizationName = Settings::get('organization_name', 'Cami Üyelik Sistemi');

            $settings = [
                'organization_name' => $organizationName,
                'organization_email' => Settings::get('organization_email', ''),
                'organization_phone' => Settings::get('organization_phone', ''),
                'organization_address' => Settings::get('organization_address', ''),
                'bank_name' => Settings::get('bank_name', ''),
                'account_holder' => Settings::get('account_holder', ''),
                'bank_iban' => Settings::get('bank_iban', ''),
                'bank_bic' => Settings::get('bank_bic', ''),
                'bank_purpose' => Settings::get('bank_purpose', 'Aidat Ödemesi'),
            ];

            // Use dynamic email template with settings
            $result = EmailService::sendMemberApproval($member, [
                'settings' => $settings,
                'sent_by' => auth()->user()->name ?? 'System',
                'recipient_name' => $member->name . ' ' . $member->surname,
            ]);

            if ($result) {
                \Log::info("Member approval email sent successfully", [
                    'member_id' => $member->id,
                    'member_email' => $member->email,
                    'member_name' => $member->name . ' ' . $member->surname,
                    'sent_by' => auth()->user()->name ?? 'System'
                ]);
                return true;
            } else {
                \Log::error("EmailService returned false for member approval email", [
                    'member_id' => $member->id,
                    'member_email' => $member->email,
                ]);
                return false;
            }
        } catch (\Exception $e) {
            \Log::error("Failed to send member approval email", [
                'member_id' => $member->id,
                'member_email' => $member->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Generate member labels PDF
     */
    public function generateLabels(Request $request)
    {
        $query = Member::query();

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by search term if provided
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('surname', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('member_no', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Get selected member IDs if provided
        if ($request->filled('member_ids')) {
            $memberIds = explode(',', $request->member_ids);
            $query->whereIn('id', $memberIds);
        }

        // Gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $members = $query->orderBy('name')->orderBy('surname')->get();

        if ($members->isEmpty()) {
            return back()->with('error', 'Yazdırılacak üye bulunamadı.');
        }





        $settings = [
            'organization_name' => Settings::get('organization_name', 'Cami Derneği'),
            'total_members' => $members->count(),
            'labels_per_page' => 24,
            'total_pages' => ceil($members->count() / 24),
        ];

        try {
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.members.labels-pdf', compact('members', 'settings'));
            $pdf->setPaper('A4', 'portrait');

            $filename = 'uye-etiketleri-' . now()->format('Y-m-d-H-i-s') . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            \Log::error('Label generation error: ' . $e->getMessage());
            return back()->with('error', 'Etiket oluşturulurken hata oluştu: ' . $e->getMessage());
        }
    }



    /**
     * Generate 220x110 mm envelopes PDF
     */
    public function generateEnvelopes(Request $request)
    {
        $query = Member::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                // Normalize search term for Turkish characters
                $normalizedTerm = $this->normalizeTurkishChars(mb_strtolower(trim($searchTerm)));
                $normalizeSQL = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(%s), 'ş', 's'), 'ı', 'i'), 'ğ', 'g'), 'ü', 'u'), 'ö', 'o'), 'ç', 'c'), 'İ', 'i')";

                $q->whereRaw(sprintf($normalizeSQL, 'name') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'surname') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'member_no') . ' LIKE ?', ["%{$normalizedTerm}%"]);
            });
        }

        if ($request->filled('member_ids')) {
            $memberIds = explode(',', $request->member_ids);
            $query->whereIn('id', $memberIds);
        }

        // Adres varlığı filtresi
        if ($request->filled('address_presence')) {
            if ($request->address_presence === 'has') {
                $query->whereNotNull('address')
                      ->where('address', '!=', '')
                      ->where('address', '!=', ' ');
            } elseif ($request->address_presence === 'missing') {
                $query->where(function($q){
                    $q->whereNull('address')
                      ->orWhere('address', '=', '')
                      ->orWhere('address', '=', ' ');
                });
            }
        }

        // Telefon varlığı filtresi
        if ($request->filled('phone_presence')) {
            if ($request->phone_presence === 'has') {
                $query->whereNotNull('phone')
                      ->where('phone', '!=', '')
                      ->where('phone', '!=', ' ');
            } elseif ($request->phone_presence === 'missing') {
                $query->where(function($q){
                    $q->whereNull('phone')
                      ->orWhere('phone', '=', '')
                      ->orWhere('phone', '=', ' ');
                });
            }
        }

        // Email tipi filtresi
        if ($request->filled('email_type')) {
            if ($request->email_type === 'temp') {
                $query->where('email', 'LIKE', '%@uye.com');
            } elseif ($request->email_type === 'permanent') {
                $query->where('email', 'NOT LIKE', '%@uye.com');
            }
        }

        // Ödeme yöntemi filtresi
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $members = $query->orderBy('surname')->orderBy('name')->get();

        if ($members->isEmpty()) {
            return back()->with('error', 'Yazdırılacak üye bulunamadı.');
        }

        $settings = [
            'organization_name' => Settings::get('organization_name', 'Cami Derneği'),
            'organization_address' => Settings::get('organization_address', ''),
            'organization_street' => Settings::get('organization_street', ''),
            'organization_house_number' => Settings::get('organization_house_number', ''),
            'organization_postal_code' => Settings::get('organization_postal_code', ''),
            'organization_city' => Settings::get('organization_city', ''),
            'organization_phone' => Settings::get('organization_phone', ''),
            'organization_email' => Settings::get('organization_email', ''),
        ];

        try {
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.members.envelopes-pdf', compact('members', 'settings'));
            // Custom paper size: 220x110 mm -> convert to points (1 mm ≈ 2.83465 pt)
            $widthPt = 220 * 2.83465;
            $heightPt = 110 * 2.83465;
            // Use wide canvas (220x110mm). Portrait here keeps the given width as the wide side.
            $pdf->setPaper([0, 0, $widthPt, $heightPt], 'portrait');

            $filename = 'zarf-yazdir-' . now()->format('Y-m-d-H-i-s') . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            \Log::error('Envelope generation error: ' . $e->getMessage());
            return back()->with('error', 'Zarf oluşturulurken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF for filtered members
     */
    public function generateFilteredMembersPdf(Request $request)
    {
        $query = Member::query();

        // Search functionality with Turkish character normalization
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $normalizedTerm = $this->normalizeTurkishChars(mb_strtolower(trim($searchTerm)));
                $normalizeSQL = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(%s), 'ş', 's'), 'ı', 'i'), 'ğ', 'g'), 'ü', 'u'), 'ö', 'o'), 'ç', 'c'), 'İ', 'i')";
                $q->whereRaw(sprintf($normalizeSQL, 'name') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'surname') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'email') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'member_no') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'phone') . ' LIKE ?', ["%{$normalizedTerm}%"]);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Email type filter
        if ($request->filled('email_type')) {
            $temporaryEmailDomain = Settings::getTemporaryEmailDomain();
            if ($request->email_type === 'temporary') {
                $query->where('email', 'LIKE', '%@' . $temporaryEmailDomain);
            } elseif ($request->email_type === 'regular') {
                $query->where('email', 'NOT LIKE', '%@' . $temporaryEmailDomain);
            }
        }

        // Payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Phone presence filter
        if ($request->filled('phone_presence')) {
            if ($request->phone_presence === 'has') {
                $query->whereNotNull('phone')->where('phone', '!=', '');
            } elseif ($request->phone_presence === 'missing') {
                $query->where(function($q){
                    $q->whereNull('phone')->orWhere('phone', '=','');
                });
            }
        }

        // Address presence filter
        if ($request->filled('address_presence')) {
            if ($request->address_presence === 'has') {
                $query->whereNotNull('address')
                      ->where('address', '!=', '')
                      ->where('address', '!=', ' ');
            } elseif ($request->address_presence === 'missing') {
                $query->where(function($q){
                    $q->whereNull('address')
                      ->orWhere('address', '=', '')
                      ->orWhere('address', '=', ' ');
                });
            }
        }

        // Gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Sorting - default to surname
        $sortField = $request->get('sort', 'surname');
        $sortDirection = $request->get('direction', 'asc');
        $allowedSortFields = ['name', 'surname', 'membership_date', 'created_at', 'monthly_dues', 'payment_status'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'surname';
        }

        if ($sortField === 'name') {
            $query->orderBy('name', $sortDirection)->orderBy('surname', $sortDirection);
        } elseif ($sortField === 'surname') {
            $query->orderBy('surname', $sortDirection)->orderBy('name', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $members = $query->get();

        if ($members->isEmpty()) {
            return back()->with('error', 'Yazdırılacak üye bulunamadı.');
        }

        // Load overdue dues for each member to calculate overdue_count
        foreach ($members as $member) {
            $member->load(['dues' => function($q) {
                $q->where(function($query) {
                    $query->where('status', 'overdue')
                          ->orWhere(function($subQuery) {
                              $subQuery->where('status', 'pending')
                                       ->where('due_date', '<', now());
                          });
                });
            }]);
        }

        try {
            // Filtre bilgilerini hazırla
            $filters = [];
            if ($request->filled('search')) {
                $filters[] = 'Arama: "' . $request->search . '"';
            }
            if ($request->filled('status')) {
                $statusText = $request->status == 'active' ? 'Aktif' : ($request->status == 'inactive' ? 'Pasif' : 'Askıya Alınmış');
                $filters[] = 'Durum: ' . $statusText;
            }
            if ($request->filled('email_type')) {
                $emailTypeText = $request->email_type == 'temporary' ? 'Geçici Email' : 'Normal Email';
                $filters[] = 'Email Türü: ' . $emailTypeText;
            }
            if ($request->filled('payment_method')) {
                $paymentTexts = [
                    'cash' => 'Nakit',
                    'bank_transfer' => 'Banka Transferi',
                    'lastschrift_monthly' => 'Lastschrift (Aylık)',
                    'lastschrift_semi_annual' => 'Lastschrift (6 Aylık)',
                    'lastschrift_annual' => 'Lastschrift (Yıllık)'
                ];
                $filters[] = 'Ödeme Yöntemi: ' . ($paymentTexts[$request->payment_method] ?? $request->payment_method);
            }
            if ($request->filled('phone_presence')) {
                $filters[] = 'Telefon: ' . ($request->phone_presence == 'has' ? 'Var' : 'Yok');
            }
            if ($request->filled('address_presence')) {
                $filters[] = 'Adres: ' . ($request->address_presence == 'has' ? 'Var' : 'Yok');
            }
            if ($request->filled('gender')) {
                $filters[] = 'Cinsiyet: ' . ($request->gender == 'male' ? 'Erkek' : 'Kadın');
            }

            // HTML içeriğini UTF-8 olarak render et
            $html = view('admin.members.filtered-pdf', compact('members', 'filters'))->render();
            
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'landscape');
            $pdf->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
                'chroot' => public_path(),
                'convert_entities' => false, // Türkçe karakterler için false
            ]);
            
            $filename = 'filtrelenmis-uyeler-' . now()->format('Y-m-d-H-i-s') . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            \Log::error('Filtered members PDF generation error: ' . $e->getMessage());
            return back()->with('error', 'PDF oluşturulurken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Send admin notification for new member
     */
    private function sendAdminNewMemberNotification(Member $member)
    {
        try {
            $organizationName = Settings::get('organization_name', 'Cami Üyelik Sistemi');
            $adminEmail = Settings::get('organization_email');

            // Settings'teki email adresine gönder
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AdminNewMemberNotificationMail($member, $organizationName));
                \Log::info("Admin notification email sent to {$adminEmail} for new member: {$member->email}");
            }

            // Ayrıca tüm admin kullanıcılarına da gönder
            $adminUsers = User::whereIn('role', ['admin', 'super_admin'])->get();

            foreach ($adminUsers as $admin) {
                // Settings'teki email ile aynı değilse gönder
                if ($admin->email !== $adminEmail) {
                    Mail::to($admin->email)->send(new AdminNewMemberNotificationMail($member, $organizationName));
                }
            }

            \Log::info("Admin notification emails sent for new member: {$member->email}");
        } catch (\Exception $e) {
            \Log::error("Failed to send admin notification emails for member {$member->email}: " . $e->getMessage());
            // Email gönderimi başarısız olsa bile işlem devam etsin
        }
    }

    /**
     * Tüm sayfalardaki üyeleri seç
     */
    public function selectAll(Request $request)
    {
        $query = Member::query();

        // Filtreleri uygula
        if ($request->filled('filters.search')) {
            $searchTerm = $request->filters['search'];
            $query->where(function($q) use ($searchTerm) {
                // Normalize search term for Turkish characters
                $normalizedTerm = $this->normalizeTurkishChars(mb_strtolower(trim($searchTerm)));

                // Create SQL for normalizing Turkish characters in database fields
                $normalizeSQL = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(%s), 'ş', 's'), 'ı', 'i'), 'ğ', 'g'), 'ü', 'u'), 'ö', 'o'), 'ç', 'c'), 'İ', 'i')";

                $q->whereRaw(sprintf($normalizeSQL, 'name') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'surname') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'email') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'member_no') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'phone') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'occupation') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'birth_place') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'nationality') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'address') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'notes') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'account_holder') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'bank_name') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, 'iban') . ' LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw(sprintf($normalizeSQL, "CONCAT(name, ' ', surname)") . ' LIKE ?', ["%{$normalizedTerm}%"]);
            });
        }

        if ($request->filled('filters.status')) {
            $query->where('status', $request->filters['status']);
        }

        if ($request->filled('filters.email_type')) {
            if ($request->filters['email_type'] === 'temporary') {
                $query->where('email', 'LIKE', '%@uye.com');
            } elseif ($request->filters['email_type'] === 'regular') {
                $query->where('email', 'NOT LIKE', '%@uye.com');
            }
        }

        if ($request->filled('filters.payment_method')) {
            $query->where('payment_method', $request->filters['payment_method']);
        }

        if ($request->filled('filters.phone_presence')) {
            if ($request->filters['phone_presence'] === 'has') {
                $query->whereNotNull('phone')->where('phone', '!=', '');
            } elseif ($request->filters['phone_presence'] === 'missing') {
                $query->where(function($q){
                    $q->whereNull('phone')->orWhere('phone', '=','');
                });
            }
        }

        if ($request->filled('filters.address_presence')) {
            if ($request->filters['address_presence'] === 'has') {
                $query->whereNotNull('address')
                      ->where('address', '!=', '')
                      ->where('address', '!=', ' ');
            } elseif ($request->filters['address_presence'] === 'missing') {
                $query->where(function($q){
                    $q->whereNull('address')
                      ->orWhere('address', '=', '')
                      ->orWhere('address', '=', ' ');
                });
            }
        }

        // Gender filter
        if ($request->filled('filters.gender')) {
            $query->where('gender', $request->filters['gender']);
        }

        $count = $query->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Display a listing of soft-deleted members.
     */
    public function deleted(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin', 'accountant'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $query = Member::onlyTrashed();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $normalizedTerm = mb_strtolower(trim($searchTerm));
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw('LOWER(surname) LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw('LOWER(member_no) LIKE ?', ["%{$normalizedTerm}%"])
                  ->orWhereRaw("LOWER(CONCAT(name, ' ', surname)) LIKE ?", ["%{$normalizedTerm}%"]);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'deleted_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $deletedMembers = $query->paginate(20);

        return view('admin.members.deleted', compact('deletedMembers'));
    }

    /**
     * Restore a soft-deleted member.
     */
    public function restore(string $id)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin', 'accountant'])) {
            abort(403, 'Bu işlemi yapma yetkiniz yok.');
        }

        $member = Member::onlyTrashed()->findOrFail($id);
        
        // Restore öncesi ödeme sayısını kaydet
        $paymentCountBefore = \DB::table('payments')
            ->where('member_id', $member->id)
            ->whereNull('deleted_at')
            ->count();
        
        // TAMAMEN BYPASS: Observer'ı geçici olarak devre dışı bırak
        // Bu sayede hiçbir event tetiklenmez ve duplicate ödeme oluşmaz
        
        // Restore öncesi silinmiş ödeme ve aidat sayılarını kaydet
        $deletedPaymentCount = \DB::table('payments')
            ->where('member_id', $member->id)
            ->whereNotNull('deleted_at')
            ->count();
        
        $deletedDueCount = \DB::table('dues')
            ->where('member_id', $member->id)
            ->whereNotNull('deleted_at')
            ->count();
        
        \Log::info("Starting restore for member {$member->id}", [
            'deleted_payment_count' => $deletedPaymentCount,
            'deleted_due_count' => $deletedDueCount,
            'payment_count_before' => $paymentCountBefore
        ]);
        
        // Restore edilen ödemelerin ID'lerini kaydet (restore öncesi)
        $restoredPaymentIdsBefore = \DB::table('payments')
            ->where('member_id', $member->id)
            ->whereNotNull('deleted_at')
            ->orderBy('created_at', 'asc')
            ->pluck('id')
            ->toArray();
        
        \DB::transaction(function() use ($member, $deletedPaymentCount) {
            // 1. Önce ödemeleri restore et (önemli: önce ödemeler, sonra aidatlar)
            // Çünkü ödemeler aidatlara bağlı olabilir
            $restoredPayments = \DB::table('payments')
                ->where('member_id', $member->id)
                ->whereNotNull('deleted_at')
                ->update(['deleted_at' => null]);
            
            \Log::info("Restored {$restoredPayments} payments for member {$member->id}");
            
            // 2. Sonra aidatları restore et
            $restoredDues = \DB::table('dues')
                ->where('member_id', $member->id)
                ->whereNotNull('deleted_at')
                ->update(['deleted_at' => null]);
            
            \Log::info("Restored {$restoredDues} dues for member {$member->id}");
            
            // 3. En son üyeyi restore et (bu observer'ı tetikleyebilir ama withoutEvents ile engelledik)
            Member::withoutEvents(function() use ($member) {
                \DB::table('members')
                    ->where('id', $member->id)
                    ->update(['deleted_at' => null]);
            });
            
            \Log::info("Restored member {$member->id}");
            
            // 4. Restore sonrası hemen kontrol et (transaction içinde)
            $paymentCountDuringTransaction = \DB::table('payments')
                ->where('member_id', $member->id)
                ->whereNull('deleted_at')
                ->count();
            
            \Log::info("Payment count during transaction: {$paymentCountDuringTransaction} for member {$member->id}");
        });
        
        // Restore sonrası ödeme sayısını kontrol et
        $paymentCountAfter = \DB::table('payments')
            ->where('member_id', $member->id)
            ->whereNull('deleted_at')
            ->count();
        
        // ÖNEMLİ: Restore edilen ödemeler duplicate değil, bunlar gerçek ödemeler
        // Sadece restore sonrası yeni oluşturulan ödemeler duplicate olabilir
        
        // Restore sonrası yeni oluşturulan ödemeleri bul (restore edilen ID'lerde olmayanlar)
        $newPaymentIds = \DB::table('payments')
            ->where('member_id', $member->id)
            ->whereNull('deleted_at')
            ->whereNotIn('id', $restoredPaymentIdsBefore)
            ->pluck('id')
            ->toArray();
        
        // Eğer yeni oluşturulan ödeme varsa, bunlar duplicate'dir ve silinmeli
        if (count($newPaymentIds) > 0) {
            \Log::warning("New payments detected after restore - these are duplicates and will be removed", [
                'member_id' => $member->id,
                'payment_count_before' => $paymentCountBefore,
                'payment_count_after' => $paymentCountAfter,
                'restored_payment_ids_count' => count($restoredPaymentIdsBefore),
                'new_payment_ids_count' => count($newPaymentIds),
                'new_payment_ids' => $newPaymentIds
            ]);
            
            // Yeni oluşturulan ödemeler duplicate'dir, hepsini sil
            $deletedCount = \DB::table('payments')
                ->whereIn('id', $newPaymentIds)
                ->update(['deleted_at' => now()]);
            
            \Log::info("Removed {$deletedCount} duplicate payments for member {$member->id} (kept restored payments)", [
                'deleted_ids' => $newPaymentIds
            ]);
        }
        
        // ÖNEMLİ: Restore edilen ödemeler arasında da duplicate kontrolü yap
        // Çünkü restore edilen ödemeler arasında gerçek duplicate'ler olabilir
        // (aynı amount, date VE aynı aidatlara bağlı olanlar)
        if (count($restoredPaymentIdsBefore) > 0) {
            \Log::info("Checking for duplicates among restored payments for member {$member->id}", [
                'restored_payment_ids_count' => count($restoredPaymentIdsBefore),
                'payment_count_after' => $paymentCountAfter
            ]);
            
            // Amount ve payment_date bazlı duplicate kontrolü
            $duplicateGroups = \DB::table('payments')
                ->select('amount', 'payment_date', \DB::raw('COUNT(*) as count'))
                ->where('member_id', $member->id)
                ->whereNull('deleted_at')
                ->groupBy('amount', 'payment_date')
                ->having('count', '>', 1)
                ->get();
            
            if ($duplicateGroups->isNotEmpty()) {
                \Log::warning("Found duplicates by amount/date for member {$member->id}", [
                    'duplicate_groups' => $duplicateGroups->toArray()
                ]);
                
                $totalDeleted = 0;
                foreach ($duplicateGroups as $group) {
                    // Bu amount/date kombinasyonuna sahip tüm ödemeleri al
                    $paymentsInGroup = \DB::table('payments')
                        ->where('member_id', $member->id)
                        ->whereNull('deleted_at')
                        ->where('amount', $group->amount)
                        ->where('payment_date', $group->payment_date)
                        ->orderBy('created_at', 'asc')
                        ->get();
                    
                    // Her ödemenin bağlı olduğu aidatları kontrol et
                    $paymentDueMap = [];
                    foreach ($paymentsInGroup as $payment) {
                        $dueIds = \DB::table('payment_due')
                            ->where('payment_id', $payment->id)
                            ->pluck('due_id')
                            ->toArray();
                        $paymentDueMap[$payment->id] = $dueIds;
                    }
                    
                    // Gerçek duplicate'leri bul: Aynı amount, date VE aynı aidatlara bağlı olanlar
                    $paymentGroups = [];
                    
                    foreach ($paymentsInGroup as $payment) {
                        $dueIds = $paymentDueMap[$payment->id];
                        sort($dueIds); // Array'i sırala
                        $key = $group->amount . '|' . $group->payment_date . '|' . implode(',', $dueIds);
                        
                        if (!isset($paymentGroups[$key])) {
                            $paymentGroups[$key] = [];
                        }
                        $paymentGroups[$key][] = $payment->id;
                    }
                    
                    // Duplicate setlerini işle (aynı key'e sahip birden fazla ödeme varsa)
                    foreach ($paymentGroups as $key => $duplicateIds) {
                        // Eğer sadece 1 ödeme varsa, duplicate değil, atla
                        if (count($duplicateIds) <= 1) {
                            continue;
                        }
                        // İlk ödemeyi koru (en eski restore edilen ödeme)
                        $keepId = null;
                        $deleteIds = [];
                        
                        foreach ($duplicateIds as $paymentId) {
                            if (in_array($paymentId, $restoredPaymentIdsBefore)) {
                                if ($keepId === null) {
                                    $keepId = $paymentId;
                                } else {
                                    // Birden fazla restore edilen ödeme varsa, en eski olanı koru
                                    $currentCreatedAt = \DB::table('payments')->where('id', $paymentId)->value('created_at');
                                    $keepCreatedAt = \DB::table('payments')->where('id', $keepId)->value('created_at');
                                    if ($currentCreatedAt < $keepCreatedAt) {
                                        $deleteIds[] = $keepId;
                                        $keepId = $paymentId;
                                    } else {
                                        $deleteIds[] = $paymentId;
                                    }
                                }
                            } else {
                                // Restore edilen ID'lerde yoksa, duplicate olarak işaretle
                                if ($keepId === null) {
                                    // İlk ödemeyi koru
                                    $keepId = $paymentId;
                                } else {
                                    $deleteIds[] = $paymentId;
                                }
                            }
                        }
                        
                        // Duplicate ödemeleri sil
                        if (count($deleteIds) > 0 && $keepId !== null) {
                            $deletedCount = \DB::table('payments')
                                ->whereIn('id', $deleteIds)
                                ->update(['deleted_at' => now()]);
                            
                            $totalDeleted += $deletedCount;
                            
                            $dueIdsStr = implode(',', $paymentDueMap[$keepId]);
                            \Log::info("Removed {$deletedCount} duplicate payments for member {$member->id} (amount: {$group->amount}, date: {$group->payment_date}, dues: {$dueIdsStr})", [
                                'kept_id' => $keepId,
                                'deleted_ids' => $deleteIds
                            ]);
                        }
                    }
                }
                
                if ($totalDeleted > 0) {
                    \Log::info("Total removed {$totalDeleted} duplicate payments for member {$member->id}");
                }
            } else {
                \Log::info("No duplicate payments found among restored payments for member {$member->id}");
            }
        }

        // Log access (DSGVO - Veri erişim kaydı)
        \App\Models\AccessLog::create([
            'member_id' => $member->id,
            'user_id' => auth()->id(),
            'action' => 'restore',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.members.deleted')
            ->with('success', 'Üye başarıyla geri getirildi.');
    }

    /**
     * Permanently delete a member.
     * 
     * DSGVO uyumluluğu: Üye silinmeden önce log kayıtlarına snapshot eklenir
     */
    public function forceDelete(string $id)
    {
        if (!auth()->user()->hasRole('super_admin')) {
            abort(403, 'Bu işlemi sadece başkan yapabilir.');
        }

        $member = Member::onlyTrashed()->findOrFail($id);
        
        // DSGVO: Üye silinmeden önce log kayıtlarına snapshot ekle
        $memberSnapshot = [
            'member_id' => $member->id,
            'member_no' => $member->member_no,
            'name' => $member->name,
            'surname' => $member->surname,
            'email' => $member->email,
            'deleted_at' => now()->toDateTimeString(),
            'deleted_by' => auth()->id(),
            'snapshot_reason' => 'Üye kalıcı olarak silindi, log kayıtları korunuyor (DSGVO)',
        ];
        
        // Bu üyeye ait tüm log kayıtlarını bul ve snapshot ekle
        \App\Models\AccessLog::where('member_id', $member->id)
            ->whereNull('details->member_snapshot') // Daha önce snapshot eklenmemiş olanlar
            ->get()
            ->each(function ($log) use ($memberSnapshot) {
                $details = $log->details ?? [];
                $details['member_snapshot'] = $memberSnapshot;
                $log->update(['details' => $details]);
            });
        
        // Son log kaydı: Üyenin kalıcı olarak silindiğini kaydet
        \App\Models\AccessLog::create([
            'member_id' => $member->id, // Silinmeden önce kaydediliyor
            'user_id' => auth()->id(),
            'action' => 'force_delete',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details' => [
                'member_snapshot' => $memberSnapshot,
                'force_deleted_at' => now()->toDateTimeString(),
            ],
        ]);
        
        // Üyeyi kalıcı olarak sil
        // NOT: Foreign key constraint onDelete('set null') olduğu için
        // log kayıtlarındaki member_id null olacak ama details'te snapshot kalacak
        $member->forceDelete();

        return redirect()->route('admin.members.deleted')
            ->with('success', 'Üye kalıcı olarak silindi. Veri erişim logları korunuyor (DSGVO).');
    }

    /**
     * Approve deletion request and soft delete member
     */
    public function approveDeletionRequest(Request $request, $id)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu işlemi yapma yetkiniz yok.');
        }

        $deletionRequest = DeletionRequest::with('member')->findOrFail($id);

        if ($deletionRequest->status !== 'pending') {
            return back()->with('error', 'Bu talep zaten işleme alınmış.');
        }

        $member = $deletionRequest->member;

        // Üyenin yazdığı gerekçeyi member'a kaydet
        $member->deletion_reason = $deletionRequest->reason;
        $member->deleted_by = auth()->id();
        $member->save();

        // Soft delete the member
        $member->delete();

        // Update deletion request
        $deletionRequest->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $request->input('review_notes'),
        ]);

        return redirect()->route('admin.members.index')
            ->with('success', 'Silme talebi onaylandı ve üye silindi.');
    }

    /**
     * Mark privacy consent withdrawal as notified
     */
    public function markPrivacyWithdrawalNotified(Request $request, $id)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu işlemi yapma yetkiniz yok.');
        }

        $withdrawal = PrivacyConsentWithdrawal::findOrFail($id);
        $withdrawal->update([
            'notified' => true,
        ]);

        return redirect()->route('admin.members.index')
            ->with('success', 'Rıza geri çekme bildirimi okundu olarak işaretlendi.');
    }

    /**
     * Reject deletion request
     */
    public function rejectDeletionRequest(Request $request, $id)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu işlemi yapma yetkiniz yok.');
        }

        $deletionRequest = DeletionRequest::findOrFail($id);

        if ($deletionRequest->status !== 'pending') {
            return back()->with('error', 'Bu talep zaten işleme alınmış.');
        }

        $deletionRequest->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $request->input('review_notes'),
        ]);

        return back()->with('success', 'Silme talebi reddedildi.');
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
     * Handle member status change to active - 01.01.2025 tarih kontrolü ile
     */
    private function handleMemberStatusChangeToActive(Member $member)
    {
        // 🛡️ GÜVENLİK: Aidat yeniden hesaplama mantığını doğrula
        $validation = DuesValidationService::validateDuesCreationLogic($member);

        if (!$validation['is_valid']) {
            throw new \Exception('Aktif hale getirme mantığı geçersiz: ' . implode(', ', $validation['errors']));
        }

        $startDate = $validation['start_date'];

        // Mevcut aidatları temizle (sadece gelecekteki olanları)
        Due::where('member_id', $member->id)
            ->where(function($query) use ($startDate) {
                $query->where('due_date', '>=', $startDate)
                      ->orWhere(function($q) use ($startDate) {
                          $q->whereYear('due_date', $startDate->year)
                            ->whereMonth('due_date', '>=', $startDate->month);
                      });
            })
            ->where('status', '!=', 'paid')
            ->delete();

        // Yeni aidatları oluştur
        $this->createYearlyDues($member, true);
    }

    /**
     * Handle member status change to inactive/suspended - gelecekteki aidatları askıya al
     */
    private function handleMemberStatusChangeToInactive(Member $member, string $newStatus)
    {
        // Gelecekteki ödenmemiş aidatları askıya al (soft delete)
        $futureDues = Due::where('member_id', $member->id)
            ->where('due_date', '>', now())
            ->whereIn('status', ['pending', 'overdue'])
            ->get();

        $suspendedCount = 0;
        foreach ($futureDues as $due) {
            $due->delete(); // Soft delete
            $suspendedCount++;
        }
    }

}
