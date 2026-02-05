<?php

namespace App\Observers;

use App\Models\Member;
use App\Models\Due;
use App\Models\Payment;
use Carbon\Carbon;

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
            Due::where('member_id', $member->id)->delete();
            Payment::where('member_id', $member->id)->delete();
        }
    }

    /**
     * Handle the Member "restored" event.
     */
    public function restored(Member $member): void
    {
        // Üye geri getirildiğinde aidatları ve ödemeleri de geri getir
        if ($member->status === 'active') {
            // Önce mevcut aidatları ve ödemeleri geri getir
            Due::where('member_id', $member->id)->restore();
            Payment::where('member_id', $member->id)->restore();

            // Eğer aidat yoksa yeni oluştur
            $existingDues = Due::where('member_id', $member->id)->count();
            if ($existingDues == 0) {
                $this->generateDuesForNewMember($member);
            }
        }
    }

    /**
     * Handle the Member "force deleted" event.
     */
    public function forceDeleted(Member $member): void
    {
        // Üye kalıcı silindiğinde tüm aidatlarını sil
        Due::where('member_id', $member->id)->forceDelete();
    }

        /**
     * Yeni üye için 10 yıllık aidat oluştur
     */
private function generateDuesForNewMember(Member $member): void
    {
        $startDate = now()->startOfMonth();
        $memberAmount = $member->monthly_dues ?? 50.00; // Varsayılan miktar

        // 10 yıl boyunca her ay için aidat oluştur
        for ($year = $startDate->year; $year < $startDate->year + 10; $year++) {
            for ($month = 1; $month <= 12; $month++) {
                // Geçmiş aylar için aidat oluşturma
                if ($year == $startDate->year && $month < $startDate->month) {
                    continue;
                }

                // Bu ay için aidat zaten var mı kontrol et
                $existingDue = Due::where('member_id', $member->id)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->first();

                if (!$existingDue) {
                    // Aidat son ödeme tarihi: ayın son günü
                    $dueDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

                    Due::create([
                        'member_id' => $member->id,
                        'year' => $year,
                        'month' => $month,
                        'amount' => $memberAmount,
                        'due_date' => $dueDate,
                        'status' => 'pending',
                        'notes' => "Otomatik oluşturulan " . $dueDate->format('F Y') . " aidatı",
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
