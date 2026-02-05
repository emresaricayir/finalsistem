<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Aylık aidat oluşturma - Her ayın 1'inde saat 00:01'de çalışır
Schedule::command('dues:create-monthly')
    ->monthlyOn(1, '00:01')
    ->timezone('Europe/Istanbul')
    ->withoutOverlapping()
    ->runInBackground();

// Gecikmiş aidatları güncelleme - Her gün saat 08:00'da çalışır
Schedule::command('dues:update-overdue')
    ->dailyAt('08:00')
    ->timezone('Europe/Istanbul')
    ->withoutOverlapping()
    ->runInBackground();

// 3 ay gecikmiş aidat hatırlatması - Her gün saat 09:00'da çalışır
Schedule::command('dues:send-overdue-reminders --months=3')
    ->dailyAt('09:00')
    ->timezone('Europe/Istanbul')
    ->withoutOverlapping()
    ->runInBackground();

// Email log temizleme - Her hafta Pazar günü saat 03:00'da çalışır (30 günden eski logları sil)
Schedule::command('email-logs:clean --days=30')
    ->weeklyOn(0, '03:00')
    ->timezone('Europe/Istanbul')
    ->withoutOverlapping()
    ->runInBackground();
