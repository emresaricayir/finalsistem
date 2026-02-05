<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Settings;
use App\Models\User;
use App\Mail\AdminNewMemberNotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class MemberApplicationController extends Controller
{
    /**
     * Show the membership application form
     */
    public function showApplicationForm()
    {
        $settings = [
            'organization_name' => Settings::get('organization_name', 'Cami Derneği'),
            'organization_address' => Settings::get('organization_address', ''),
            'organization_phone' => Settings::get('organization_phone', ''),
            'organization_fax' => Settings::get('organization_fax', ''),
            'organization_email' => Settings::get('organization_email', 'info@camidernegi.com'),
            'form_title' => Settings::get('form_title', 'Üyelik Başvuru Formu'),
            'bank_name' => Settings::get('bank_name', ''),
            'account_holder' => Settings::get('account_holder', ''),
            'bank_iban' => Settings::get('bank_iban', ''),
            'bank_bic' => Settings::get('bank_bic', ''),
            'bank_purpose' => Settings::get('bank_purpose', 'Aidat Ödemesi'),
        ];

        return view('member.application', compact('settings'));
    }

    /**
     * Store the membership application
     */
    public function storeApplication(Request $request)
    {
        // Check if email exists in soft deleted records
        $existingDeletedMember = Member::onlyTrashed()->where('email', $request->email)->first();

        if ($existingDeletedMember) {
            // Handle monthly_dues - convert custom to actual amount
            $monthlyDues = $request->monthly_dues;
            if ($monthlyDues === 'custom') {
                $customAmount = $request->input('custom_amount');
                if (!$customAmount || !is_numeric($customAmount) || $customAmount < 5) {
                    return back()->withInput()->withErrors([
                        'custom_amount' => 'Özel aidat miktarı en az 5 € olmalıdır.'
                    ]);
                }
                $monthlyDues = (float) $customAmount;
            } else {
                if (!is_numeric($monthlyDues) || $monthlyDues < 5) {
                    return back()->withInput()->withErrors([
                        'monthly_dues' => 'Aylık aidat miktarı geçersizdir.'
                    ]);
                }
                $monthlyDues = (float) $monthlyDues;
            }

            // If email exists in soft deleted records, restore the member
            $existingDeletedMember->restore();
            $existingDeletedMember->update([
                'name' => $request->name,
                'surname' => $request->surname,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'birth_place' => $request->birth_place,
                'nationality' => $request->nationality,
                'occupation' => $request->occupation,
                'password' => Hash::make($request->password),
                'status' => 'inactive',
                'application_status' => 'pending',
                'membership_date' => now(),
                'monthly_dues' => $monthlyDues,
                'payment_method' => $request->payment_method,
                'payment_frequency' => $request->payment_frequency,
                'mandate_number' => $request->mandate_number,
                'account_holder' => $request->account_holder,
                'bank_name' => $request->bank_name,
                'iban' => $request->iban,
                'bic' => $request->bic,
                'payment_due_date' => $request->payment_due_date,
                'signature' => $request->signature,
                'signature_date' => now(),
                'sepa_agreement' => $request->has('sepa_agreement'),
                'application_date' => now(),
            ]);

            // Send email notification
            $this->sendApplicationEmail($existingDeletedMember);

            // Send admin notification
            $this->sendAdminNewMemberNotification($existingDeletedMember);

            return redirect()->route('member.application.success', ['id' => $existingDeletedMember->id])
                ->with('success', 'Üyelik başvurunuz başarıyla alındı. Başvurunuz incelendikten sonra size bilgi verilecektir.');
        }

        // Email kontrolü - soft deleted kayıtlar dahil
        $emailExists = Member::withTrashed()->where('email', $request->email)->exists();
        if ($emailExists) {
            return back()->withInput()->withErrors([
                'email' => 'Bu e-posta adresi zaten kullanılıyor. / Diese E-Mail-Adresse wird bereits verwendet.'
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'birth_date' => 'required|date|before_or_equal:' . now()->subYears(16)->format('Y-m-d'),
            'birth_place' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'monthly_dues' => 'required',
            'custom_amount' => 'nullable|numeric|min:' . \App\Models\Settings::getMinimumMonthlyDues(),
            'payment_method' => 'required|in:cash,bank_transfer,lastschrift_monthly,lastschrift_semi_annual,lastschrift_annual',
            'payment_frequency' => 'nullable|in:monthly,semi_annual,annual',
            'mandate_number' => 'nullable|string|max:255',
            'account_holder' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'bic' => 'nullable|string|max:255',
            'sepa_agreement' => 'nullable|boolean',
            'payment_due_date' => 'nullable|date',
            'signature' => 'required|string',
            'signature_confirmation' => 'required|accepted',
        ], [
            'birth_date.before_or_equal' => 'Üye olmak için en az 16 yaşında olmanız gerekmektedir. / Für die Mitgliedschaft müssen Sie mindestens 16 Jahre alt sein.',
            'monthly_dues.required' => 'Aylık aidat seçimi yapmanız gerekmektedir.',
            'custom_amount.numeric' => 'Özel aidat miktarı sayı olmalıdır.',
            'custom_amount.min' => 'Özel aidat miktarı en az ' . \App\Models\Settings::getMinimumMonthlyDues() . ' € olmalıdır.',
        ]);

        // Custom SEPA validation
        if (in_array($validated['payment_method'], ['lastschrift_monthly', 'lastschrift_semi_annual', 'lastschrift_annual']) && !$request->has('sepa_agreement')) {
            return back()->withInput()->withErrors([
                'sepa_agreement' => 'Lastschrift ödeme yöntemi için SEPA-Lastschriftmandat onayı gereklidir.'
            ]);
        }

        // Handle custom amount
        if ($validated['monthly_dues'] === 'custom') {
            $customAmount = $request->input('custom_amount');
            if (!$customAmount) {
                return back()->withInput()->withErrors([
                    'custom_amount' => 'Özel aidat miktarı girilmesi gerekmektedir.'
                ]);
            }
            if (!is_numeric($customAmount)) {
                return back()->withInput()->withErrors([
                    'custom_amount' => 'Aylık aidat miktarı sayı olmalıdır.'
                ]);
            }
            if ($customAmount < 5) {
                return back()->withInput()->withErrors([
                    'custom_amount' => 'Özel aidat miktarı en az 5 € olmalıdır.'
                ]);
            }
            $validated['monthly_dues'] = (float) $customAmount;
        } else {
            // Ensure monthly_dues is numeric when not custom
            if (!is_numeric($validated['monthly_dues']) || $validated['monthly_dues'] < 5) {
                return back()->withInput()->withErrors([
                    'monthly_dues' => 'Aylık aidat miktarı geçersizdir.'
                ]);
            }
            $validated['monthly_dues'] = (float) $validated['monthly_dues'];
        }

        // Final safety check - ensure monthly_dues is always a numeric value
        if (!is_numeric($validated['monthly_dues']) || $validated['monthly_dues'] < 5) {
            return back()->withInput()->withErrors([
                'monthly_dues' => 'Aylık aidat miktarı geçersizdir.'
            ]);
        }

        $maxRetries = 5;
        $retryCount = 0;
        $member = null;

        while ($retryCount < $maxRetries) {
            try {
                DB::beginTransaction();

                // Generate unique member number (with lock to prevent race conditions)
                $memberNo = $this->generateUniqueMemberNumber();

                // Create member with pending status
                $member = Member::create([
                    'name' => $validated['name'],
                    'surname' => $validated['surname'],
                    'gender' => $validated['gender'] ?? null,
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'password' => Hash::make($validated['password']),
                    'address' => $validated['address'],
                    'birth_date' => $validated['birth_date'],
                    'birth_place' => $validated['birth_place'],
                    'nationality' => $validated['nationality'],
                    'occupation' => $validated['occupation'],
                    'member_no' => $memberNo,
                    'status' => 'inactive',
                    'application_status' => 'pending',
                    'membership_date' => now(),
                    'monthly_dues' => $validated['monthly_dues'],
                    'payment_method' => $validated['payment_method'],
                    'payment_frequency' => $validated['payment_frequency'] ?? null,
                    'mandate_number' => $validated['mandate_number'] ?? null,
                    'account_holder' => $validated['account_holder'] ?? null,
                    'bank_name' => $validated['bank_name'] ?? null,
                    'iban' => $validated['iban'] ?? null,
                    'bic' => $validated['bic'] ?? null,
                    'payment_due_date' => $validated['payment_due_date'] ?? null,
                    'signature' => $validated['signature'],
                    'signature_date' => now(),
                    'sepa_agreement' => $request->has('sepa_agreement'),
                    'application_date' => now(),
                ]);

                DB::commit();
                break; // Başarılı, döngüden çık

            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                
                // Unique constraint hatası kontrolü
                if ($e->getCode() == 23000 && strpos($e->getMessage(), 'member_no_unique') !== false) {
                    $retryCount++;
                    if ($retryCount >= $maxRetries) {
                        throw new \Exception('Üye numarası oluşturulamadı. Lütfen tekrar deneyin.');
                    }
                    // Kısa bir süre bekle ve tekrar dene
                    usleep(100000); // 0.1 saniye bekle
                    continue;
                }
                // Diğer hatalar için fırlat
                throw $e;
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }

        if (!$member) {
            throw new \Exception('Üye kaydı oluşturulamadı. Lütfen tekrar deneyin.');
        }

        // Send email notification
        $this->sendApplicationEmail($member);

        // Send admin notification
        $this->sendAdminNewMemberNotification($member);

        return redirect()->route('member.application.success', ['id' => $member->id])
            ->with('success', __('common.application_received_review'));
    }

    /**
     * Show application success page
     */
        public function applicationSuccess($id)
    {
        $member = Member::findOrFail($id);

        $settings = [
            'organization_name' => Settings::get('organization_name', 'Cami Derneği'),
            'organization_address' => Settings::get('organization_address', ''),
            'organization_phone' => Settings::get('organization_phone', ''),
            'organization_fax' => Settings::get('organization_fax', ''),
            'organization_email' => Settings::get('organization_email', 'info@camidernegi.com'),
            'logo' => Settings::get('logo'),
            'bank_name' => Settings::get('bank_name', ''),
            'account_holder' => Settings::get('account_holder', ''),
            'bank_iban' => Settings::get('bank_iban', ''),
            'bank_bic' => Settings::get('bank_bic', ''),
            'bank_purpose' => Settings::get('bank_purpose', 'Aidat Ödemesi'),
        ];

        return view('member.application-success', compact('member', 'settings'));
    }

    /**
     * Generate PDF application form
     */
    public function generatePdf($id, Request $request)
    {
        $member = Member::findOrFail($id);

        // Erişim kontrolü
        $isAdmin = auth()->check() && auth()->user()->hasAnyRole(['super_admin', 'admin', 'accountant']); // Admin girişi kontrolü
        $isMemberOwner = session('member_id') == $id; // Üyenin kendi kaydı kontrolü

        if (!$isAdmin && !$isMemberOwner) {
            abort(403, 'Bu PDF\'e erişim yetkiniz bulunmamaktadır.');
        }

        // Üyeler sadece onaylanmış durumda PDF görebilir, adminler her durumda görebilir
        if (!$isAdmin && $member->application_status !== 'approved') {
            abort(403, 'Bu PDF sadece üyeliği onaylanmış kişiler tarafından görüntülenebilir.');
        }

        $settings = [
            'organization_name' => Settings::get('organization_name'),
            'organization_subtitle' => Settings::get('organization_subtitle'),
            'organization_address' => Settings::get('organization_address'),
            'organization_phone' => Settings::get('organization_phone'),
            'organization_fax' => Settings::get('organization_fax'),
            'organization_email' => Settings::get('organization_email'),
            'form_title' => Settings::get('form_title'),
            'certificate_title' => Settings::get('certificate_title'),
            'president_name' => Settings::get('president_name'),
            'president_title' => Settings::get('president_title'),
            'pdf_member1_name' => Settings::get('pdf_member1_name'),
            'pdf_member2_name' => Settings::get('pdf_member2_name'),
            'pdf_president_name' => Settings::get('pdf_president_name'),
            'pdf_secretary_name' => Settings::get('pdf_secretary_name'),
            'pdf_accountant_name' => Settings::get('pdf_accountant_name'),
            'pdf_vice_president_name' => Settings::get('pdf_vice_president_name'),
            'logo' => Settings::get('logo', ''),
        ];

                        try {
            // Admin ve üye paneli için farklı template'ler kullan
            $template = $isAdmin ? 'pdf.member-application-admin' : 'member.certificate-pdf';

            // HTML template ile başvuru formu oluştur
            $html = view($template, compact('member', 'settings'))->render();

            $filename = 'uyelik-basvuru-' . $member->member_no . '.html';

            // HTML olarak tarayıcıda göster
            return response($html)->header('Content-Type', 'text/html');

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'PDF oluşturulurken hata oluştu.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate HTML content for PDF (modern & stylish)
     */
    private function generatePdfHtml($member, $settings)
{
    $html = '
    <style>
        body { font-family: dejavusans; font-size: 9pt; line-height: 1.4; color: #333; }
        .header { text-align: center; background-color: #004085; color: #fff; padding: 14px; margin-bottom: 12px; }
        .header h2 { margin: 0; font-size: 16pt; font-weight: bold; }
        .header h3 { margin: 4px 0 0; font-size: 10pt; font-weight: normal; }

        .section { margin-bottom: 14px; border: 1px solid #ddd; border-radius: 4px; }
        .section-title { font-weight: bold; background-color: #f1f3f5; padding: 6px 8px; font-size: 9.5pt; border-bottom: 1px solid #ddd; }

        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px 8px; border: 1px solid #ddd; font-size: 8.5pt; }
        .label { width: 35%; font-weight: bold; background: #f8f9fa; }
        .value { width: 65%; }

        .signature-box { border: 1px solid #000; height: 70px; width: 180px; margin: 8px auto; }
        .signature-note { font-size: 7pt; color: #666; margin-top: 2px; text-align: center; }

        .footer { background:#f8f9fa; padding:6px; font-size:7pt; text-align:center; color:#555; margin-top: 16px; border-top: 1px solid #ddd; }
    </style>

    <div class="header">
        <h2>' . htmlspecialchars($settings['organization_name']) . '</h2>
        <h3>Üyelik Başvuru Formu / Mitgliedschaftsantrag</h3>
    </div>

    <div class="section">
        <div class="section-title">Kişisel Bilgiler / Persönliche Daten</div>
        <table>
            <tr><td class="label">Ad / Vorname</td><td class="value">' . htmlspecialchars($member->name) . '</td></tr>
            <tr><td class="label">Soyad / Nachname</td><td class="value">' . htmlspecialchars($member->surname) . '</td></tr>
            <tr><td class="label">Doğum Tarihi / Geburtsdatum</td><td class="value">' . ($member->birth_date ? $member->birth_date->format("d.m.Y") : "-") . '</td></tr>
            <tr><td class="label">Doğum Yeri / Geburtsort</td><td class="value">' . htmlspecialchars($member->birth_place ?? "-") . '</td></tr>
            <tr><td class="label">Uyruk / Staatsangehörigkeit</td><td class="value">' . htmlspecialchars($member->nationality ?? "-") . '</td></tr>
            <tr><td class="label">Meslek / Beruf</td><td class="value">' . htmlspecialchars($member->occupation ?? "-") . '</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">İletişim Bilgileri / Kontaktdaten</div>
        <table>
            <tr><td class="label">E-posta / Email</td><td class="value">' . htmlspecialchars($member->email) . '</td></tr>
            <tr><td class="label">Telefon / Telefon</td><td class="value">' . htmlspecialchars($member->phone ?? "-") . '</td></tr>
            <tr><td class="label">Adres / Adresse</td><td class="value">' . htmlspecialchars($member->address ?? "-") . '</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Üyelik Bilgileri / Mitgliedschaftsdaten</div>
        <table>
            <tr><td class="label">Üye No / Mitgliedsnr.</td><td class="value">' . htmlspecialchars($member->member_no) . '</td></tr>
            <tr><td class="label">Başvuru Tarihi / Antragsdatum</td><td class="value">' . ($member->application_date ? $member->application_date->format("d.m.Y") : "-") . '</td></tr>
            <tr><td class="label">Aylık Aidat / Monatsbeitrag</td><td class="value">' . number_format($member->monthly_dues, 2) . ' €</td></tr>
            <tr><td class="label">Ödeme Yöntemi / Zahlungsart</td><td class="value">' . $this->getPaymentMethodText($member->payment_method) . '</td></tr>
            <tr><td class="label">Ödeme Sıklığı / Zahlungsfrequenz</td><td class="value">' . $this->getPaymentFrequencyText($member->payment_frequency) . '</td></tr>
        </table>
    </div>';

    // Banka bilgileri sadece nakit değilse
    if ($member->payment_method !== 'cash') {
        $html .= '
        <div class="section">
            <div class="section-title">Banka Bilgileri / Bankdaten</div>
            <table>
                <tr><td class="label">Hesap Sahibi / Kontoinhaber</td><td class="value">' . htmlspecialchars($member->account_holder ?? "-") . '</td></tr>
                <tr><td class="label">Banka Adı / Bankname</td><td class="value">' . htmlspecialchars($member->bank_name ?? "-") . '</td></tr>
                <tr><td class="label">IBAN</td><td class="value">' . htmlspecialchars($member->iban ?? "-") . '</td></tr>
                <tr><td class="label">BIC</td><td class="value">' . htmlspecialchars($member->bic ?? "-") . '</td></tr>
                <tr><td class="label">Mandat No / Mandatsreferenz</td><td class="value">' . htmlspecialchars($member->mandate_number ?? "-") . '</td></tr>
                <tr><td class="label">Ödeme Tarihi / Zahlungstermin</td><td class="value">' . ($member->payment_due_date ? $member->payment_due_date->format("d.m.Y") : "-") . '</td></tr>
            </table>
        </div>';
    }

    // İmza alanı
    if ($member->signature) {
        $html .= '
        <div class="section">
            <div class="section-title">Beyan ve Dijital İmza / Erklärung und Digitale Unterschrift</div>
            <p style="font-size:8pt; margin-bottom:4px;">
                Yukarıdaki bilgilerin doğru olduğunu ve dernek tüzüğünü kabul ettiğimi beyan ederim.<br>
                <em>Ich erkläre, dass die obigen Angaben richtig sind und ich die Vereinssatzung akzeptiere.</em>
            </p>
            <div style="text-align:center;">
                <img src="' . $member->signature . '" style="max-width:120px; max-height:30px; border:1px solid #ccc; background:white;" />
                <div class="signature-note">İmza Tarihi: ' . ($member->signature_date ? $member->signature_date->format("d.m.Y H:i") : "-") . '</div>
            </div>
        </div>';
    }

    // Yönetim Kurulu onayı
    $html .= '
    <div class="section">
        <div class="section-title">Onay / Genehmigung</div>
        <table>
            <tr>
                <td style="text-align:center; width:33%;">
                    <strong>' . ($settings["pdf_member1_name"] ?: "1. Üye") . '</strong><br><em>1. Mitglied</em>
                    <div class="signature-box"></div>
                    <div class="signature-note">Tarih ve İmza</div>
                </td>
                <td style="text-align:center; width:33%;">
                    <strong>' . ($settings["pdf_member2_name"] ?: "2. Üye") . '</strong><br><em>2. Mitglied</em>
                    <div class="signature-box"></div>
                    <div class="signature-note">Tarih ve İmza</div>
                </td>
                <td style="text-align:center; width:33%;">
                    <strong>' . ($settings["pdf_president_name"] ?: "Başkan") . '</strong><br><em>Vorsitzender</em>
                    <div class="signature-box"></div>
                    <div class="signature-note">Tarih ve İmza</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        ' . htmlspecialchars($settings["organization_name"]) . ' - ' . htmlspecialchars($settings["organization_email"]) . ' | ' . htmlspecialchars($settings["organization_phone"]) . '<br>
        <em>Bu form otomatik olarak oluşturulmuştur / Dieses Formular wurde automatisch erstellt</em>
    </div>';

    return $html;
}



    /**
     * Send application confirmation email
     */
    private function sendApplicationEmail(Member $member)
    {
        try {
            $settings = [
                'organization_name' => Settings::get('organization_name', 'Cami Derneği'),
                'organization_address' => Settings::get('organization_address', ''),
                'organization_phone' => Settings::get('organization_phone', ''),
                'organization_fax' => Settings::get('organization_fax', ''),
                'organization_email' => Settings::get('organization_email', 'info@camidernegi.com'),
                'bank_name' => Settings::get('bank_name', ''),
                'account_holder' => Settings::get('account_holder', ''),
                'bank_iban' => Settings::get('bank_iban', ''),
                'bank_bic' => Settings::get('bank_bic', ''),
                'bank_purpose' => Settings::get('bank_purpose', 'Aidat Ödemesi'),
            ];

            Mail::send('emails.application-confirmation', compact('member', 'settings'), function ($message) use ($member, $settings) {
                $message->to($member->email, $member->name . ' ' . $member->surname)
                        ->subject('Üyelik Başvurunuz Alındı - ' . $settings['organization_name'])
                        ->from(config('mail.from.address'), $settings['organization_name']);
            });
        } catch (\Exception $e) {
            // Log the error but don't fail the application process
            \Log::error('Failed to send application confirmation email: ' . $e->getMessage());
        }
    }

    /**
     * Send admin notification for new member application
     */
    private function sendAdminNewMemberNotification(Member $member)
    {
        try {
            $organizationName = Settings::get('organization_name', 'Cami Üyelik Sistemi');
            $adminEmail = Settings::get('organization_email');

            // Settings'teki email adresine gönder
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AdminNewMemberNotificationMail($member, $organizationName));
                \Log::info("Admin notification email sent to {$adminEmail} for new member application: {$member->email}");
            }

            // Ayrıca tüm admin kullanıcılarına da gönder
            $adminUsers = User::whereIn('role', ['admin', 'super_admin'])->get();

            foreach ($adminUsers as $admin) {
                // Settings'teki email ile aynı değilse gönder
                if ($admin->email !== $adminEmail) {
                    Mail::to($admin->email)->send(new AdminNewMemberNotificationMail($member, $organizationName));
                }
            }

            \Log::info("Admin notification emails sent for new member application: {$member->email}");
        } catch (\Exception $e) {
            \Log::error("Failed to send admin notification emails for member application {$member->email}: " . $e->getMessage());
            // Email gönderimi başarısız olsa bile işlem devam etsin
        }
    }

    /**
     * Generate unique member number
     */
    private function generateUniqueMemberNumber()
    {
        $maxAttempts = 100; // Maksimum deneme sayısı
        $attempt = 0;

        do {
            $attempt++;
            
            if ($attempt > $maxAttempts) {
                throw new \Exception('Üye numarası oluşturulamadı. Lütfen tekrar deneyin.');
            }

            // Lock ile en yüksek mevcut üye numarasını bul (race condition'ı önlemek için)
            // Önce tüm aktif Mitglied numaralarını lock'la (soft deleted hariç)
            DB::table('members')
                ->where('member_no', 'LIKE', 'Mitglied%')
                ->whereNull('deleted_at') // Sadece aktif kayıtlar
                ->lockForUpdate()
                ->get();
            
            // Sonra en yüksek numarayı bul (sadece aktif kayıtlar)
            $lastMember = DB::table('members')
                ->where('member_no', 'LIKE', 'Mitglied%')
                ->whereNull('deleted_at') // Sadece aktif kayıtlar
                ->orderByRaw('CAST(SUBSTRING(member_no, 9) AS UNSIGNED) DESC')
                ->first();

            if ($lastMember) {
                // Son numaradan bir sonrakini al
                // "Mitglied" 8 karakter, sonrasındaki sayıyı al
                $lastNumber = (int) substr($lastMember->member_no, 8);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $memberNo = 'Mitglied' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // Bu numaranın kullanılıp kullanılmadığını kontrol et (sadece aktif kayıtlar)
            // Soft deleted kayıtların numaralarını tekrar kullanmıyoruz
            $exists = Member::where('member_no', $memberNo)->exists();

            // Eğer numara kullanılıyorsa, bir sonraki numarayı dene
            if ($exists) {
                $nextNumber++;
                $memberNo = 'Mitglied' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                $exists = Member::where('member_no', $memberNo)->exists();
            }

        } while ($exists);

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
}
