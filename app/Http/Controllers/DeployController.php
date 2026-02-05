<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class DeployController extends Controller
{
    /**
     * Test endpoint - webhook'un çalışıp çalışmadığını kontrol etmek için
     */
    public function test()
    {
        $secret = config('app.webhook_secret');
        return response()->json([
            'status' => 'ok',
            'message' => 'Webhook endpoint is accessible',
            'timestamp' => now()->toDateTimeString(),
            'webhook_secret_configured' => !empty($secret),
            'webhook_secret_length' => $secret ? strlen($secret) : 0,
            'webhook_secret_preview' => $secret ? substr($secret, 0, 10) . '...' : null,
            'routes_loaded' => true,
            'controller' => 'DeployController',
        ]);
    }
    
    /**
     * GET endpoint - tarayıcıdan test için
     */
    public function index(Request $request)
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'Webhook endpoint is working',
            'method' => $request->method(),
            'post_endpoint' => '/webhook/deploy',
            'test_endpoint' => '/webhook/deploy/test',
            'timestamp' => now()->toDateTimeString(),
            'server_info' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'app_env' => config('app.env'),
            ],
        ]);
    }
    
    /**
     * GitHub webhook handler
     */
    public function handle(Request $request)
    {
        // GitHub webhook secret kontrolü
        $secret = config('app.webhook_secret');
        $event = $request->header('X-GitHub-Event');
        
        if ($secret) {
            $signature = $request->header('X-Hub-Signature-256');
            $payload = $request->getContent();
            
            if (empty($signature)) {
                Log::warning('Webhook signature missing', ['event' => $event]);
                abort(403, 'Missing signature');
            }
            
            $hash = 'sha256=' . hash_hmac('sha256', $payload, $secret);
            
            if (!hash_equals($signature, $hash)) {
                Log::warning('Webhook signature mismatch', [
                    'event' => $event,
                    'expected' => substr($hash, 0, 20) . '...',
                    'received' => substr($signature, 0, 20) . '...',
                ]);
                abort(403, 'Invalid signature');
            }
        } else {
            Log::warning('Webhook secret not configured', ['event' => $event]);
        }
        
        // Ping event'i için özel yanıt (GitHub webhook test)
        if ($event === 'ping') {
            return response()->json([
                'status' => 'ok',
                'message' => 'Webhook is working correctly',
                'event' => 'ping',
                'timestamp' => now()->toDateTimeString(),
            ], 200);
        }
        
        // Sadece push event'lerini işle
        if ($event !== 'push') {
            return response()->json(['message' => 'Event ignored: ' . $event], 200);
        }
        
        // Sadece main/master branch'i işle
        $payload = $request->json()->all();
        $branch = str_replace('refs/heads/', '', $payload['ref'] ?? '');
        
        if (!in_array($branch, ['main', 'master'])) {
            return response()->json(['message' => 'Branch ignored: ' . $branch], 200);
        }
        
        try {
            $output = [];
            $basePath = base_path();
            
            // Git pull
            $output[] = "📥 Git pull yapılıyor...";
            $gitPull = shell_exec("cd {$basePath} && git pull origin {$branch} 2>&1");
            $output[] = $gitPull;
            
            if (strpos($gitPull, 'fatal') !== false || strpos($gitPull, 'error') !== false) {
                throw new \Exception("Git pull hatası: " . $gitPull);
            }
            
            // Composer install
            $output[] = "\n📦 Composer bağımlılıkları güncelleniyor...";
            $composerInstall = shell_exec("cd {$basePath} && composer install --no-dev --optimize-autoloader 2>&1");
            $output[] = $composerInstall;
            
            // Cache temizle
            $output[] = "\n🧹 Cache temizleniyor...";
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            $output[] = "✅ Cache temizlendi.";
            
            // Cache'le
            $output[] = "\n⚡ Cache'ler oluşturuluyor...";
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            $output[] = "✅ Cache'ler oluşturuldu.";
            
            // Migration kontrolü
            if ($this->hasNewMigrations($payload)) {
                $output[] = "\n🗄️ Veritabanı güncelleniyor...";
                Artisan::call('migrate', ['--force' => true]);
                $output[] = "✅ Migration'lar çalıştırıldı.";
            }
            
            // Storage link kontrolü
            try {
                Artisan::call('storage:link');
                $output[] = "✅ Storage link kontrol edildi.";
            } catch (\Exception $e) {
                // Link zaten varsa devam et
            }
            
            // Log kaydı
            Log::info('Deployment successful', [
                'branch' => $branch,
                'commit' => $payload['head_commit']['id'] ?? 'unknown',
                'author' => $payload['head_commit']['author']['name'] ?? 'unknown',
                'message' => $payload['head_commit']['message'] ?? 'unknown'
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Deployment completed successfully',
                'branch' => $branch,
                'commit' => $payload['head_commit']['id'] ?? 'unknown',
                'output' => implode("\n", $output)
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Deployment failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'output' => implode("\n", $output ?? [])
            ], 500);
        }
    }
    
    /**
     * Yeni migration var mı kontrol et
     */
    private function hasNewMigrations($payload)
    {
        $commits = $payload['commits'] ?? [];
        foreach ($commits as $commit) {
            $added = $commit['added'] ?? [];
            $modified = $commit['modified'] ?? [];
            $files = array_merge($added, $modified);
            
            foreach ($files as $file) {
                if (strpos($file, 'database/migrations/') !== false) {
                    return true;
                }
            }
        }
        return false;
    }
}
