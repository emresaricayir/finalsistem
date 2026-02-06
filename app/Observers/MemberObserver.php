<?php

namespace App\Observers;

use App\Models\Member;
use App\Models\Due;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MemberObserver
{
    /**
     * Handle the Member "created" event.
     */
    public function created(Member $member): void
    {
        // Sadece onaylanmış üyeler için aidat oluştur
        if ($member->application_status === 'approved' && $member->status === 'active') {
            $this->generateDuesForNewMember($member);
        }
    }

    /**
     * Handle the Member "updated" event.
     */
    public function updated(Member $member): void
    {
        // Üye onaylandığında aidat oluştur
        if ($member->wasChanged('application_status') && $member->application_status === 'approved') {
            $this->generateDuesForNewMember($member);
        }

        // Aidat miktarı değiştiyse gelecek aidatları güncelle
        if ($member->wasChanged('monthly_dues')) {
            $this->updateFutureDues($member);
        }
    }

    /**
     * Handle the Member "deleted" event.
     */
    public function deleted(Member $member): void
    {
        // Soft delete durumunda aidatları ve ödemeleri de soft delete yap
        if (!$member->isForceDeleting()) {
            // Soft delete - aidatları ve ödemeleri de soft delete yap
            // ÖNEMLİ: Pivot tablo ilişkileri korunmalı (Payment modelinde detach sadece force delete'te yapılıyor)
            Due::where('member_id', $member->id)->delete();
            Payment::where('member_id', $member->id)->delete();
        }
    }

    /**
     * Handle the Member "restored" event.
     * 
     * ÖNEMLİ: Bu metod artık hiçbir şey yapmıyor!
     * Restore işlemi MemberController::restore() metodunda direkt SQL ile yapılıyor.
     * Bu sayede hiçbir event tetiklenmez ve duplicate ödeme oluşmaz.
     */
    public function restored(Member $member): void
    {
        // BOŞ: Restore işlemi MemberController::restore() metodunda direkt SQL ile yapılıyor
        // Bu metod artık hiçbir şey yapmıyor, sadece observer'ın tetiklenmesini engellemek için boş bırakıldı
        return;
    }

    /**
     * Handle the Member "force deleted" event.
     */
    public function forceDeleted(Member $member): void
    {
        // Üye kalıcı silindiğinde tüm aidatlarını ve ödemelerini kalıcı sil
        // Pivot tablo ilişkileri Payment modelinde force delete'te otomatik silinecek
        Due::where('member_id', $member->id)->forceDelete();
        Payment::where('member_id', $member->id)->forceDelete();
    }

        /**
     * Yeni üye için 10 yıllık aidat oluştur
     */
private function generateDuesForNewMember(Member $member): void
    {
        $startDate = now()->startOfMonth();
        $memberAmount = $member->monthly_dues ?? 50.00; // Varsayılan miktar

        // 10 yıl boyunca her ay için aidat oluştur
        // ÖNEMLİ: Event'leri devre dışı bırakarak aidat oluştur
        // Bu sayede aidat oluşturma sırasında ödeme oluşturulmaz
        for ($year = $startDate->year; $year < $startDate->year + 10; $year++) {
            for ($month = 1; $month <= 12; $month++) {
                // Geçmiş aylar için aidat oluşturma
                if ($year == $startDate->year && $month < $startDate->month) {
                    continue;
                }

                // Bu ay için aidat zaten var mı kontrol et (soft-deleted dahil)
                $existingDue = DB::table('dues')
                    ->where('member_id', $member->id)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->first();

                if (!$existingDue) {
                    // Aidat son ödeme tarihi: ayın son günü
                    $dueDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

                    // Direkt SQL ile aidat oluştur (event'ler tetiklenmez)
                    DB::table('dues')->insert([
                        'member_id' => $member->id,
                        'year' => $year,
                        'month' => $month,
                        'amount' => $memberAmount,
                        'due_date' => $dueDate->format('Y-m-d'),
                        'status' => 'pending',
                        'notes' => "Otomatik oluşturulan " . $dueDate->format('F Y') . " aidatı",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Gelecek aidatları güncelle
     */
    private function updateFutureDues(Member $member): void
    {
        $newAmount = $member->monthly_dues;
        $currentDate = now();

        // Gelecek aidatları güncelle (henüz ödenmemiş olanlar)
        Due::where('member_id', $member->id)
            ->where('due_date', '>', $currentDate)
            ->where('status', '!=', 'paid')
            ->update([
                'amount' => $newAmount,
                'notes' => \DB::raw("CONCAT(notes, ' - Aidat miktarı güncellendi: €{$newAmount}')")
            ]);
    }
}
