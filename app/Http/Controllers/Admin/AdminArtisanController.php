<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class AdminArtisanController extends Controller
{
    public function __construct()
    {
        // Middleware will be handled in routes
    }

    /**
     * Show the database update interface
     */
    public function index()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.db-update.index');
    }


    /**
     * Run database migrations
     */
    public function migrate(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        try {
            $output = '';

            // Run migrations
            Artisan::call('migrate', ['--force' => true]);
            $output .= "=== Database Migrations Executed ===\n";
            $output .= Artisan::output();

            Log::info('Database migrations executed by user: ' . auth()->user()->email);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Veritabanı migrationları başarıyla çalıştırıldı.',
                    'output' => $output,
                ]);
            }

            return redirect()->route('admin.db-update')->with('success', 'Veritabanı migrationları başarıyla çalıştırıldı.');

        } catch (\Exception $e) {
            Log::error('Migration execution failed: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Migration çalıştırılırken hata oluştu: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->route('admin.db-update')->with('error', 'Migration çalıştırılırken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Optimize the application
     */
    public function optimize(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        try {
            $output = '';

            // Clear and cache config
            Artisan::call('config:cache');
            $output .= "=== Config Cache ===\n";
            $output .= Artisan::output();

            // Clear and cache routes
            Artisan::call('route:cache');
            $output .= "\n=== Route Cache ===\n";
            $output .= Artisan::output();

            // Clear and cache views
            Artisan::call('view:cache');
            $output .= "\n=== View Cache ===\n";
            $output .= Artisan::output();

            // Optimize autoloader
            Artisan::call('optimize');
            $output .= "\n=== Application Optimization ===\n";
            $output .= Artisan::output();

            Log::info('Application optimization executed by user: ' . auth()->user()->email);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Uygulama başarıyla optimize edildi.',
                    'output' => $output,
                ]);
            }

            return redirect()->route('admin.db-update')->with('success', 'Uygulama başarıyla optimize edildi.');

        } catch (\Exception $e) {
            Log::error('Application optimization failed: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Uygulama optimize edilirken hata oluştu: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->route('admin.db-update')->with('error', 'Uygulama optimize edilirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Clear all caches
     */
    public function clearCache(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        try {
            $output = '';

            // Clear application cache
            Artisan::call('cache:clear');
            $output .= "=== Application Cache Cleared ===\n";
            $output .= Artisan::output();

            // Clear config cache
            Artisan::call('config:clear');
            $output .= "\n=== Config Cache Cleared ===\n";
            $output .= Artisan::output();

            // Clear route cache
            Artisan::call('route:clear');
            $output .= "\n=== Route Cache Cleared ===\n";
            $output .= Artisan::output();

            // Clear view cache
            Artisan::call('view:clear');
            $output .= "\n=== View Cache Cleared ===\n";
            $output .= Artisan::output();

            Log::info('All caches cleared by user: ' . auth()->user()->email);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tüm önbellekler başarıyla temizlendi.',
                    'output' => $output,
                ]);
            }

            return redirect()->route('admin.db-update')->with('success', 'Tüm önbellekler başarıyla temizlendi.');

        } catch (\Exception $e) {
            Log::error('Cache clearing failed: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Önbellekler temizlenirken hata oluştu: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->route('admin.db-update')->with('error', 'Önbellekler temizlenirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Update Composer packages
     */
    public function composerUpdate(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        // Check if shell_exec is available
        if (!function_exists('shell_exec')) {
            return redirect()->route('admin.db-update')
                ->with('error', 'shell_exec() fonksiyonu hosting tarafından devre dışı bırakılmış. Lütfen hosting desteği ile iletişime geçin veya SSH üzerinden manuel olarak güncelleyin.');
        }

        // Check if exec is disabled
        $disabledFunctions = explode(',', ini_get('disable_functions'));
        if (in_array('shell_exec', $disabledFunctions)) {
            return redirect()->route('admin.db-update')
                ->with('error', 'shell_exec() fonksiyonu hosting tarafından devre dışı bırakılmış. Lütfen hosting desteği ile iletişime geçin veya SSH üzerinden manuel olarak güncelleyin.');
        }

        try {
            // Increase execution time for long-running operations
            set_time_limit(300); // 5 minutes
            ini_set('max_execution_time', '300');
            
            $output = '';
            $basePath = base_path();

            // Try to find composer path
            $composerPath = $this->findComposerPath();
            if (!$composerPath) {
                return redirect()->route('admin.db-update')
                    ->with('error', 'Composer bulunamadı. Lütfen hosting desteği ile iletişime geçin veya SSH üzerinden manuel olarak güncelleyin.');
            }

            // Change to project directory and run composer update (without scripts to avoid dev dependency issues)
            $command = "cd {$basePath} && {$composerPath} update --no-dev --optimize-autoloader --no-scripts 2>&1";
            
            // Execute composer update
            $output .= "=== Composer Update Started ===\n";
            $output .= "Composer Path: {$composerPath}\n";
            $result = shell_exec($command);
            $output .= $result ?: "No output from composer command.\n";
            $output .= "\n=== Composer Update Completed ===\n";

            // Regenerate autoload files (without scripts)
            $output .= "\n=== Regenerating Autoload ===\n";
            $autoloadCommand = "cd {$basePath} && {$composerPath} dump-autoload --optimize --no-scripts 2>&1";
            $autoloadResult = shell_exec($autoloadCommand);
            $output .= $autoloadResult ?: "No output from composer dump-autoload.\n";
            $output .= "Autoload regenerated.\n";

            // Clear all caches first (including bootstrap cache files)
            $output .= "\n=== Clearing All Caches ===\n";
            Artisan::call('optimize:clear');
            $output .= "All caches cleared.\n";

            // Manually remove bootstrap cache files to ensure clean state
            $bootstrapCachePath = base_path('bootstrap/cache');
            $cacheFiles = ['config.php', 'services.php', 'packages.php', 'routes.php', 'events.php'];
            foreach ($cacheFiles as $file) {
                $filePath = $bootstrapCachePath . '/' . $file;
                if (file_exists($filePath)) {
                    @unlink($filePath);
                    $output .= "Removed: bootstrap/cache/{$file}\n";
                }
            }

            // Discover packages (this will regenerate packages.php and services.php without Collision)
            $output .= "\n=== Discovering Packages ===\n";
            try {
                Artisan::call('package:discover', ['--ansi' => true]);
                $output .= Artisan::output();
                $output .= "Packages discovered successfully.\n";
            } catch (\Exception $e) {
                $output .= "Warning: Package discovery had issues: " . $e->getMessage() . "\n";
            }

            // Publish exception renderer views if needed
            $output .= "\n=== Publishing Exception Renderer Views ===\n";
            try {
                Artisan::call('vendor:publish', ['--tag' => 'laravel-exceptions-renderer-views', '--force' => true]);
                $output .= Artisan::output();
            } catch (\Exception $e) {
                $output .= "Note: Exception renderer views may already be published or package not installed.\n";
            }

            // Rebuild caches (this will use the newly discovered packages without Collision)
            $output .= "\n=== Rebuilding Caches ===\n";
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            Artisan::call('optimize');
            $output .= "Caches rebuilt.\n";

            Log::info('Composer update executed by user: ' . auth()->user()->email);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Composer paketleri başarıyla güncellendi.',
                    'output' => $output,
                ]);
            }

            return redirect()->route('admin.db-update')->with('success', 'Composer paketleri başarıyla güncellendi. Cache\'ler yeniden oluşturuldu.');

        } catch (\Exception $e) {
            Log::error('Composer update failed: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Composer güncellemesi sırasında hata oluştu: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->route('admin.db-update')->with('error', 'Composer güncellemesi sırasında hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Find composer executable path
     */
    private function findComposerPath()
    {
        // Common composer paths
        $possiblePaths = [
            'composer', // If in PATH
            '/usr/bin/composer',
            '/usr/local/bin/composer',
            '/opt/cpanel/composer/bin/composer',
            base_path('composer.phar'), // Local composer.phar
        ];

        foreach ($possiblePaths as $path) {
            // Check if file exists and is executable
            if ($path === 'composer') {
                // Check if composer is in PATH
                $testCommand = 'composer --version 2>&1';
                $result = @shell_exec($testCommand);
                if ($result && strpos($result, 'Composer') !== false) {
                    return 'composer';
                }
            } else {
                // Check if file exists
                if (file_exists($path) && is_executable($path)) {
                    return $path;
                }
            }
        }

        return null;
    }
}
