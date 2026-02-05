<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Due;
use App\Models\Payment;



class DashboardController extends Controller
{
    public function index()
    {
        // Ana istatistikler
        $total_members = Member::count();
        $active_members = Member::where('status', 'active')->count();
        $this_month_payments = Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        // Aidat gelirleri (üyelerden gelen ödemeler)
        // Bu ay için: bu ayın aidatlarına yapılan ödemeler
        $this_month_dues_income = Payment::whereHas('dues', function($query) {
            $query->where('month', now()->month)
                  ->where('year', now()->year);
        })->sum('amount');

        // Bu yıl için: bu yılın aidatlarına yapılan ödemeler
        $this_year_dues_income = Payment::whereHas('dues', function($query) {
            $query->where('year', now()->year);
        })->sum('amount');

        // Geçen yıl için: geçen yılın aidatlarına yapılan ödemeler
        $last_year_dues_income = Payment::whereHas('dues', function($query) {
            $query->where('year', now()->year - 1);
        })->sum('amount');

        $total_dues_income = Payment::sum('amount');

        // Bu ay toplam gelir sadece aidat gelirlerinden oluşur
        $this_month_total_income = $this_month_dues_income;

        // Son eklenen üyeler
        $recent_members = Member::latest()->take(5)->get();

        // Gecikmiş aidatlar - aktif üyelerin vadesi geçmiş ve ödenmemiş aidatları
        $overdue_dues = Due::with('member')
            ->whereHas('member', function($q) {
                $q->where('status', 'active');
            })
            ->where(function($q) {
                // Overdue status'ünde olanlar VEYA vadesi geçmiş pending olanlar
                $q->where('status', 'overdue')
                  ->orWhere(function($subQuery) {
                      $subQuery->where('due_date', '<', now())
                               ->where('status', 'pending');
                  });
            })
            // Aidat tarihi üyelik tarihinden sonra olmalı
            ->whereRaw('dues.due_date >= (SELECT membership_date FROM members WHERE members.id = dues.member_id)')
            ->orderBy('due_date', 'asc')
            ->take(10)
            ->get();

        // Toplam gecikmiş aidat sayısı ve tutarı (aynı mantıkla)
        $total_overdue_count = Due::whereHas('member', function($q) {
                $q->where('status', 'active');
            })
            ->where(function($q) {
                $q->where('status', 'overdue')
                  ->orWhere(function($subQuery) {
                      $subQuery->where('due_date', '<', now())
                               ->where('status', 'pending');
                  });
            })
            ->whereRaw('dues.due_date >= (SELECT membership_date FROM members WHERE members.id = dues.member_id)')
            ->count();

        $total_overdue_amount = Due::whereHas('member', function($q) {
                $q->where('status', 'active');
            })
            ->where(function($q) {
                $q->where('status', 'overdue')
                  ->orWhere(function($subQuery) {
                      $subQuery->where('due_date', '<', now())
                               ->where('status', 'pending');
                  });
            })
            ->whereRaw('dues.due_date >= (SELECT membership_date FROM members WHERE members.id = dues.member_id)')
            ->sum('amount');

        // Yıllık beklenen aidat (tüm aktif üyelerin aylık aidatları x 12)
        $yearly_expected_dues = Member::where('status', 'active')
            ->sum('monthly_dues') * 12;

        return view('admin.dashboard', compact(
            'total_members',
            'active_members',
            'this_month_payments',
            'this_month_dues_income',
            'this_year_dues_income',
            'last_year_dues_income',
            'this_month_total_income',
            'total_dues_income',
            'recent_members',
            'overdue_dues',
            'total_overdue_count',
            'total_overdue_amount',
            'yearly_expected_dues'
        ));
    }
}
