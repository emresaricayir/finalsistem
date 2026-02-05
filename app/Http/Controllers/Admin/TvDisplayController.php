<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Due;
use App\Models\TvDisplayMessage;
use App\Models\TvDisplaySettings;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TvDisplayController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin', 'accountant'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        // TV ayarlarını al
        $settings = TvDisplaySettings::getCurrentSettings();


        $year = $request->get('year', $settings->default_year ?? 2026);
        $page = $request->get('page', 1);
        $perPage = $settings->member_display_limit; // Ayarlardan alınan sayfa başına üye sayısı

        // Aktif üyeleri al (sayfalama ile)
        $membersQuery = Member::where('status', 'active')
            ->orderBy('surname', 'asc')
            ->orderBy('name', 'asc');

        $totalMembers = $membersQuery->count();
        $members = $membersQuery->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        // Aylar listesi
        $months = [
            1 => '1', 2 => '2', 3 => '3', 4 => '4',
            5 => '5', 6 => '6', 7 => '7', 8 => '8',
            9 => '9', 10 => '10', 11 => '11', 12 => '12'
        ];

        // Her üye için ödeme durumunu kontrol et
        $memberPayments = [];
        foreach ($members as $member) {
            $memberPayments[$member->id] = [
                'member' => $member,
                'payments' => []
            ];

            // Aidat gösterimi ayarına göre ödeme durumunu kontrol et
            if ($settings->show_dues) {
                // Bu üyenin tüm aidatlarını al
                $dues = Due::where('member_id', $member->id)
                    ->where('year', $year)
                    ->get();

                // Her ay için ödeme durumunu kontrol et
                for ($month = 1; $month <= 12; $month++) {
                    $paidDue = $dues->where('month', $month)->where('status', 'paid')->first();
                    $memberPayments[$member->id]['payments'][$month] = $paidDue ? true : false;
                }
            } else {
                // Aidat gösterimi kapalıysa tüm ayları ödenmiş olarak işaretle
                for ($month = 1; $month <= 12; $month++) {
                    $memberPayments[$member->id]['payments'][$month] = true;
                }
            }
        }

        // Tüm üyelerin genel ödeme oranını hesapla
        $allMembers = Member::where('status', 'active')->get();
        $totalPossiblePayments = $allMembers->count() * 12; // Her üye için 12 ay
        $totalPaidPayments = 0;

        if ($settings->show_dues) {
            // Aidat gösterimi açıksa gerçek ödeme durumunu hesapla
            foreach ($allMembers as $member) {
                $paidDues = Due::where('member_id', $member->id)
                    ->where('year', $year)
                    ->where('status', 'paid')
                    ->count();
                $totalPaidPayments += $paidDues;
            }
        } else {
            // Aidat gösterimi kapalıysa %100 göster
            $totalPaidPayments = $totalPossiblePayments;
        }

        $overallCompletionRate = $totalPossiblePayments > 0 ?
            round(($totalPaidPayments / $totalPossiblePayments) * 100) : 0;

        // Sayfalama bilgileri
        $totalPages = ceil($totalMembers / $perPage);
        $currentPage = $page;
        $hasNextPage = $currentPage < $totalPages;
        $hasPrevPage = $currentPage > 1;

        // Normal mod: Sadece belirli sayfalarda mesajları göster
        $displayMessages = collect();
        $allMessages = TvDisplayMessage::where('is_active', true)->get();


        foreach ($allMessages as $message) {
            $displayPages = $message->display_pages ?? [];

            // Eğer display_pages boşsa, her sayfada göster
            if (empty($displayPages)) {
                $displayMessages->push($message);
                continue;
            }

            // Sadece seçilen sayfa sonrasında bir kez göster
            foreach ($displayPages as $displayPage) {
                if ($currentPage == $displayPage + 1) {
                    $displayMessages->push($message);
                    break;
                }
            }
        }
        $showInfoScreen = $displayMessages->count() > 0;

        // Toplam sayfa sayısını hesapla (TV ekranında kullanım için)
        $totalPages = ceil($totalMembers / $perPage);

        return view('admin.tv-display.index', compact(
            'members',
            'months',
            'memberPayments',
            'year',
            'currentPage',
            'totalPages',
            'hasNextPage',
            'hasPrevPage',
            'totalMembers',
            'overallCompletionRate',
            'totalPaidPayments',
            'totalPossiblePayments',
            'showInfoScreen',
            'displayMessages',
            'settings'
        ));
    }
}
