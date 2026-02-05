<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ClearCacheOnUpdate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // POST, PUT, PATCH, DELETE istekleri sonrası cache temizle
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            // Genel cache'leri temizle
            Cache::forget('board_members');
            Cache::forget('main_menu');
            Cache::forget('personnel_categories');

            // Eğer admin panelindeyse ek cache'leri de temizle
            if (str_contains($request->path(), 'admin')) {
                Cache::flush();
            }
        }

        return $response;
    }
}

