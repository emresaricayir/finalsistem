<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     * Dil algılama ve ayarlama işlemi
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Session'dan dil bilgisini al
        $locale = Session::get('locale', 'tr'); // Varsayılan: Türkçe
        
        // 2. Geçerli dil kontrolü (sadece tr ve de kabul et)
        if (!in_array($locale, ['tr', 'de'])) {
            $locale = 'tr'; // Geçersizse varsayılan olarak Türkçe
        }
        
        // 3. Locale'i ayarla
        App::setLocale($locale);
        
        // 4. Request'e locale'i ekle (opsiyonel - view'larda kullanmak için)
        $request->attributes->set('locale', $locale);
        
        return $next($request);
    }
}
