<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Facade;
use App\Models\Member;
use App\Models\Payment;
use App\Observers\MemberObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // PDF Facade'ını tanımla
        $this->app->alias('dompdf', \Barryvdh\DomPDF\Facade::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Türkçe locale ayarları
        App::setLocale('tr');

        // Carbon için Türkçe ayarları
        Carbon::setLocale('tr');

        // Almanya saat dilimi
        date_default_timezone_set('Europe/Berlin');

        // UTF-8 encoding ayarları
        mb_internal_encoding('UTF-8');
        mb_http_output('UTF-8');

                // Carbon için Türkçe çeviriler
        Carbon::macro('diffForHumansTr', function () {
            $diff = $this->diffForHumans();

            $translations = [
                'ago' => 'önce',
                'from now' => 'sonra',
                'just now' => 'az önce',
                'second' => 'saniye',
                'seconds' => 'saniye',
                'minute' => 'dakika',
                'minutes' => 'dakika',
                'hour' => 'saat',
                'hours' => 'saat',
                'day' => 'gün',
                'days' => 'gün',
                'week' => 'hafta',
                'weeks' => 'hafta',
                'month' => 'ay',
                'months' => 'ay',
                'year' => 'yıl',
                'years' => 'yıl',
            ];

            foreach ($translations as $english => $turkish) {
                $diff = str_replace($english, $turkish, $diff);
            }

            return $diff;
        });

        // Türkçe ay isimleri için macro
        Carbon::macro('formatTr', function ($format) {
            $result = $this->format($format);

            // Ay isimlerini Türkçe'ye çevir
            $months = [
                'January' => 'Ocak',
                'February' => 'Şubat',
                'March' => 'Mart',
                'April' => 'Nisan',
                'May' => 'Mayıs',
                'June' => 'Haziran',
                'July' => 'Temmuz',
                'August' => 'Ağustos',
                'September' => 'Eylül',
                'October' => 'Ekim',
                'November' => 'Kasım',
                'December' => 'Aralık'
            ];

            foreach ($months as $english => $turkish) {
                $result = str_replace($english, $turkish, $result);
            }

            return $result;
        });

        // Observer'ları kaydet
        Member::observe(MemberObserver::class);
    }
}
