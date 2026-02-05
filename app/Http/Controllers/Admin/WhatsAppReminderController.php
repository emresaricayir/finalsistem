<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppService;
use App\Models\Member;
use App\Models\Due;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WhatsAppReminderController extends Controller
{
    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Aidat hatırlatma sayfasını göster
     */
    public function index()
    {
        // 🛡️ GÜVENLİK: Referans tarih mantığına göre gecikmiş aidatları al
        $referenceDate = \App\Services\DuesValidationService::getReferenceDate();

        // Gecikmiş aidatları al
        $overdueDues = Due::with('member')
            ->join('members', 'dues.member_id', '=', 'members.id')
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
            })
            ->select('dues.*')
            ->orderBy('dues.due_date', 'asc')
            ->paginate(50);

        // İstatistikler (aynı mantıkla)
        $totalOverdueQuery = Due::join('members', 'dues.member_id', '=', 'members.id')
            ->where('members.status', 'active')
            ->where('dues.status', 'overdue')
            ->where('dues.due_date', '<', now())
            ->where(function($q) use ($referenceDate) {
                $q->where(function($subQ) use ($referenceDate) {
                    $subQ->where('members.membership_date', '<', $referenceDate)
                         ->where('dues.year', '>=', 2025);
                })
                ->orWhere(function($subQ) use ($referenceDate) {
                    $subQ->where('members.membership_date', '>=', $referenceDate)
                         ->where('dues.due_date', '>=', \DB::raw('members.membership_date'));
                });
            });

        $stats = [
            'total_overdue' => $totalOverdueQuery->count(),
            'total_amount' => $totalOverdueQuery->sum('dues.amount'),
            'members_with_phone' => Member::whereNotNull('phone')
                ->where('phone', '!=', '')
                ->whereHas('dues', function($q) use ($referenceDate) {
                    $q->where('status', 'overdue')
                      ->where('due_date', '<', now())
                      ->where(function($subQ) use ($referenceDate) {
                          $subQ->where(function($subSubQ) use ($referenceDate) {
                              $subSubQ->where('members.membership_date', '<', $referenceDate)
                                      ->where('dues.year', '>=', 2025);
                          })
                          ->orWhere(function($subSubQ) use ($referenceDate) {
                              $subSubQ->where('members.membership_date', '>=', $referenceDate)
                                      ->where('dues.due_date', '>=', \DB::raw('members.membership_date'));
                          });
                      });
                })
                ->count(),
        ];

        return view('admin.whatsapp.due-reminders', compact('overdueDues', 'stats'));
    }

    /**
     * Seçili üyelere aidat hatırlatması gönder
     */
    public function sendReminders(Request $request)
    {
        \Log::info('Send reminders request', $request->all());

        try {
            $request->validate([
                'member_ids' => 'required|array|min:1',
                'member_ids.*' => 'exists:members,id',
                'message_template' => 'required|string|max:1000'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in sendReminders', $e->errors());
            return back()->with('errors', $e->errors());
        }

        $memberIds = $request->member_ids;
        $messageTemplate = $request->message_template;

        // Telefon numarası olan üyeleri al
        $members = Member::whereIn('id', $memberIds)
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->with(['dues' => function($q) {
                $q->overdue()->orderBy('due_date', 'asc');
            }])
            ->get();

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($members as $member) {
            if (!$member->phone) {
                $errorCount++;
                $errors[] = "{$member->full_name} - Telefon numarası yok";
                continue;
            }

            // Üyeye özel mesaj oluştur
            $personalizedMessage = $this->personalizeMessage($messageTemplate, $member);

            // WhatsApp mesajı gönder
            $result = $this->whatsapp->sendMessage($member->phone, $personalizedMessage);

            if ($result['success']) {
                $successCount++;

                // Log kaydı tut
                \Log::info('WhatsApp aidat hatırlatması gönderildi', [
                    'member_id' => $member->id,
                    'phone' => $member->phone,
                    'message' => $personalizedMessage
                ]);
            } else {
                $errorCount++;
                $errors[] = "{$member->full_name} - {$result['error']}";

                \Log::error('WhatsApp aidat hatırlatması gönderilemedi', [
                    'member_id' => $member->id,
                    'phone' => $member->phone,
                    'error' => $result['error']
                ]);
            }

            // Rate limit - 12 saniye bekle (5 mesaj/dakika)
            if ($successCount > 0 && $successCount % 1 === 0) {
                sleep(12);
            }
        }

        $message = "✅ {$successCount} üyeye başarıyla gönderildi.";
        if ($errorCount > 0) {
            $message .= " ❌ {$errorCount} üyeye gönderilemedi.";
        }

        return back()->with('success', $message)->with('errors', $errors);
    }

    /**
     * Tüm gecikmiş aidatlara hatırlatma gönder
     */
    public function sendBulkReminders(Request $request)
    {
        \Log::info('Bulk reminder request received', $request->all());

        $request->validate([
            'message_template' => 'required|string|max:1000'
        ]);

        // Gecikmiş aidatı olan ve telefon numarası olan üyeleri al
        $members = Member::whereNotNull('phone')
            ->where('phone', '!=', '')
            ->whereHas('dues', function($q) {
                $q->overdue();
            })
            ->with(['dues' => function($q) {
                $q->overdue()->orderBy('due_date', 'asc');
            }])
            ->get();

        if ($members->isEmpty()) {
            \Log::warning('No members found for bulk reminder');
            return back()->with('error', 'Hatırlatma gönderilecek üye bulunamadı.');
        }

        \Log::info('Found members for bulk reminder', ['count' => $members->count()]);

        $memberIds = $members->pluck('id')->toArray();
        $request->merge(['member_ids' => $memberIds]);

        return $this->sendReminders($request);
    }

    /**
     * Mesajı üyeye özel hale getir
     */
    private function personalizeMessage($template, $member)
    {
        $overdueDues = $member->dues;
        $totalAmount = $overdueDues->sum('amount');
        $oldestDue = $overdueDues->first();

        $replacements = [
            '{name}' => $member->name,
            '{surname}' => $member->surname,
            '{full_name}' => $member->full_name,
            '{member_number}' => $member->member_number,
            '{total_amount}' => number_format($totalAmount, 2),
            '{due_count}' => $overdueDues->count(),
            '{oldest_due_date}' => $oldestDue ? $oldestDue->due_date->format('d.m.Y') : '',
            '{oldest_due_month}' => $oldestDue ? $oldestDue->due_date->format('F Y') : '',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    /**
     * Mesaj şablonları
     */
    public function getTemplates()
    {
        return response()->json([
            'templates' => [
                [
                    'name' => 'Standart Hatırlatma',
                    'message' => "Sayın {full_name},\n\n{due_count} adet gecikmiş aidatınız bulunmaktadır.\nToplam tutar: {total_amount} €\nEn eski aidat: {oldest_due_date}\n\nLütfen en kısa sürede ödemenizi yapınız.\n\nTeşekkürler."
                ],
                [
                    'name' => 'Nazik Hatırlatma',
                    'message' => "Merhaba {name},\n\nGecikmiş {due_count} adet aidatınız için nazik bir hatırlatma.\nToplam: {total_amount} €\n\nZamanınızda ödeme yapabilirseniz çok memnun oluruz.\n\nSaygılarımızla."
                ],
                [
                    'name' => 'Acil Hatırlatma',
                    'message' => "Sayın {full_name},\n\n⚠️ ACİL: {oldest_due_date} tarihinden beri gecikmiş aidatlarınız var!\n\nToplam: {total_amount} € ({due_count} adet)\n\nLütfen DERHAL ödemenizi yapınız.\n\nCami Yönetimi"
                ]
            ]
        ]);
    }
}
