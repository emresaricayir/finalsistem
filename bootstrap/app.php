<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Web middleware grubuna SetLocale ekle (tüm web route'larında çalışsın)
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        
        $middleware->alias([
            'member.auth' => \App\Http\Middleware\MemberAuth::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'update.last.login' => \App\Http\Middleware\UpdateLastLogin::class,
        ]);
        
        // GitHub webhook için CSRF korumasını devre dışı bırak
        // Hem route'da withoutMiddleware hem de burada except kullanıyoruz (çift koruma)
        $middleware->validateCsrfTokens(except: [
            'webhook/deploy',
            'webhook/deploy/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            $statusCode = $e->getStatusCode();

            if ($request->expectsJson()) {
                $messages = [
                    403 => 'Bu alana girme yetkiniz yoktur.',
                    404 => 'Sayfa bulunamadı.',
                    500 => 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
                ];

                $types = [
                    403 => 'Forbidden',
                    404 => 'Not Found',
                    500 => 'Internal Server Error'
                ];

                return response()->json([
                    'message' => $messages[$statusCode] ?? 'Bir hata oluştu.',
                    'error' => $types[$statusCode] ?? 'Error'
                ], $statusCode);
            }

            switch ($statusCode) {
                case 403:
                    return response()->view('errors.403', [
                        'message' => 'Bu alana girme yetkiniz yoktur.'
                    ], 403);
                case 404:
                    return response()->view('errors.404', [], 404);
                case 500:
                    return response()->view('errors.500', [], 500);
                default:
                    return response()->view('errors.500', [], 500);
            }
        });
    })->create();
