<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Due;
use Carbon\Carbon;

class DuesValidationService
{
    /**
     * Aidat hesaplama için referans tarih
     * Bu tarihten önce üye olanlar için aidatlar bu tarihten başlar
     */
    public const REFERENCE_DATE = '2026-01-01';

    /**
     * Referans tarihi Carbon instance olarak döndür
     */
    public static function getReferenceDate(): Carbon
    {
        return Carbon::parse(self::REFERENCE_DATE);
    }

    /**
     * Aidat oluşturma mantığını doğrula
     */
    public static function validateDuesCreationLogic(Member $member): array
    {
        $membershipDate = Carbon::parse($member->membership_date);
        $referenceDate = self::getReferenceDate();

        $validation = [
            'is_valid' => true,
            'errors' => [],
            'warnings' => [],
            'start_date' => null,
            'logic_applied' => null
        ];

        // Temel validasyonlar
        if (!$membershipDate) {
            $validation['is_valid'] = false;
            $validation['errors'][] = 'Üyelik tarihi geçersiz';
            return $validation;
        }

        // Mantık kontrolü
        $referenceDateFormatted = $referenceDate->format('d.m.Y');
        if ($membershipDate->lt($referenceDate)) {
            $validation['start_date'] = $referenceDate->copy()->startOfMonth();
            $validation['logic_applied'] = "Üyelik tarihi {$referenceDateFormatted}'ten önce - Aidatlar {$referenceDateFormatted}'ten başlayacak";
        } else {
            $validation['start_date'] = $membershipDate->copy()->startOfMonth();
            $validation['logic_applied'] = "Üyelik tarihi {$referenceDateFormatted}'ten sonra - Aidatlar üyelik tarihinden başlayacak";
        }

        // Geçmiş tarih kontrolü
        if ($validation['start_date']->isPast()) {
            $validation['warnings'][] = 'Başlangıç tarihi geçmişte: ' . $validation['start_date']->format('d.m.Y');
        }

        // Gelecek tarih kontrolü
        if ($validation['start_date']->isFuture()) {
            $validation['warnings'][] = 'Başlangıç tarihi gelecekte: ' . $validation['start_date']->format('d.m.Y');
        }

        return $validation;
    }

    /**
     * Mevcut aidatları kontrol et ve çakışmaları tespit et
     */
    public static function checkExistingDuesConflicts(Member $member, Carbon $startDate): array
    {
        $conflicts = [
            'has_conflicts' => false,
            'conflicts' => [],
            'summary' => []
        ];

        // 10 yıl boyunca kontrol et
        $currentDate = $startDate->copy();
        $endDate = $startDate->copy()->addYears(10);

        while ($currentDate->lte($endDate)) {
            $existingDue = Due::withTrashed()
                ->where('member_id', $member->id)
                ->where('year', $currentDate->year)
                ->where('month', $currentDate->month)
                ->first();

            if ($existingDue) {
                $conflict = [
                    'year' => $currentDate->year,
                    'month' => $currentDate->month,
                    'status' => $existingDue->status,
                    'amount' => $existingDue->amount,
                    'is_deleted' => $existingDue->trashed(),
                    'created_at' => $existingDue->created_at
                ];

                $conflicts['conflicts'][] = $conflict;
                $conflicts['has_conflicts'] = true;

                // Özet bilgileri
                if (!isset($conflicts['summary'][$existingDue->status])) {
                    $conflicts['summary'][$existingDue->status] = 0;
                }
                $conflicts['summary'][$existingDue->status]++;
            }

            $currentDate->addMonth();
        }

        return $conflicts;
    }

    /**
     * Aidat oluşturma işlemini logla
     */
    public static function logDuesCreation(Member $member, array $validation, array $conflicts): void
    {
        // Log kaldırıldı - hosting'de pratik değil
        // Sadece kritik hatalar exception olarak fırlatılıyor
    }

    /**
     * Kritik durumları kontrol et
     */
    public static function checkCriticalConditions(Member $member): array
    {
        $critical = [
            'has_critical_issues' => false,
            'issues' => []
        ];

        // Üyelik tarihi çok eski mi?
        $membershipDate = Carbon::parse($member->membership_date);
        if ($membershipDate->lt(Carbon::parse('1990-01-01'))) {
            $critical['has_critical_issues'] = true;
            $critical['issues'][] = 'Üyelik tarihi çok eski: ' . $membershipDate->format('d.m.Y');
        }

        // Üyelik tarihi gelecekte mi?
        if ($membershipDate->isFuture()) {
            $critical['has_critical_issues'] = true;
            $critical['issues'][] = 'Üyelik tarihi gelecekte: ' . $membershipDate->format('d.m.Y');
        }

        // Aylık aidat miktarı geçerli mi?
        if (!$member->monthly_dues || $member->monthly_dues <= 0) {
            $critical['has_critical_issues'] = true;
            $critical['issues'][] = 'Aylık aidat miktarı geçersiz: ' . $member->monthly_dues;
        }

        return $critical;
    }

    /**
     * Üye durumu değişikliğini doğrula
     */
    public static function validateStatusChange(Member $member, string $newStatus, string $oldStatus): array
    {
        $validation = [
            'is_valid' => true,
            'errors' => [],
            'warnings' => [],
            'action_required' => null
        ];

        // Geçersiz durum kontrolü
        $validStatuses = ['active', 'inactive', 'suspended'];
        if (!in_array($newStatus, $validStatuses)) {
            $validation['is_valid'] = false;
            $validation['errors'][] = "Geçersiz durum: {$newStatus}";
            return $validation;
        }

        // Aynı durum kontrolü
        if ($newStatus === $oldStatus) {
            $validation['warnings'][] = 'Durum değişikliği yok';
            return $validation;
        }

        // Aktif hale gelme kontrolü
        if ($newStatus === 'active' && $oldStatus !== 'active') {
            $validation['action_required'] = 'dues_recalculation';

            // Üye bilgilerini kontrol et
            $critical = self::checkCriticalConditions($member);
            if ($critical['has_critical_issues']) {
                $validation['warnings'] = array_merge($validation['warnings'], $critical['issues']);
            }

            // Mevcut aidatları kontrol et
            $validation['warnings'][] = 'Aktif hale geldiğinde aidatlar yeniden hesaplanacak';
        }

        // Pasif hale gelme kontrolü
        if ($newStatus === 'inactive' && $oldStatus === 'active') {
            $validation['action_required'] = 'dues_pause';
            $validation['warnings'][] = 'Pasif hale geldiğinde gelecekteki aidatlar askıya alınacak';
        }

        // Askıya alma kontrolü
        if ($newStatus === 'suspended' && $oldStatus === 'active') {
            $validation['action_required'] = 'dues_suspend';
            $validation['warnings'][] = 'Askıya alındığında gelecekteki aidatlar askıya alınacak';
        }

        return $validation;
    }

    /**
     * Durum değişikliği işlemini logla
     */
    public static function logStatusChange(Member $member, string $oldStatus, string $newStatus, array $validation): void
    {
        // Log kaldırıldı - hosting'de pratik değil
        // Sadece kritik hatalar exception olarak fırlatılıyor
    }

    /**
     * Durum değişikliği sonrası aidat durumunu kontrol et
     */
    public static function checkDuesAfterStatusChange(Member $member, string $newStatus): array
    {
        $check = [
            'has_issues' => false,
            'issues' => [],
            'summary' => []
        ];

        if ($newStatus === 'active') {
            // Aktif üyenin aidatlarını kontrol et
            $dues = $member->dues()->get();

            foreach ($dues->groupBy('status') as $status => $statusDues) {
                $check['summary'][$status] = $statusDues->count();
            }

            // Gelecekteki aidatlar var mı?
            $futureDues = $member->dues()
                ->where('due_date', '>', now())
                ->count();

            if ($futureDues === 0) {
                $check['has_issues'] = true;
                $check['issues'][] = 'Aktif üyenin gelecekteki aidatı yok';
            }
        } else {
            // Pasif/askıya alınmış üyenin gelecekteki aidatları var mı?
            $futureDues = $member->dues()
                ->where('due_date', '>', now())
                ->whereIn('status', ['pending', 'overdue'])
                ->count();

            if ($futureDues > 0) {
                $check['has_issues'] = true;
                $check['issues'][] = "Pasif/askıya alınmış üyenin {$futureDues} gelecekteki aidatı var";
            }
        }

        return $check;
    }
}
