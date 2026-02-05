@extends('admin.layouts.app')

@section('title', 'Sistem Yönetimi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-cogs mr-2 text-blue-500"></i>
                Sistem Yönetimi
            </h1>
            <p class="mt-2 text-gray-600">Sistem optimizasyonu ve önbellek yönetimi işlemlerini gerçekleştirin.</p>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- System Operations -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Database Migrations -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-database text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Veritabanı Migrationları</h3>
                    <p class="text-sm text-gray-600">Migrationları çalıştır</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Bekleyen veritabanı migrationlarını çalıştırır. Yeni tablolar ve değişiklikler uygulanır.
            </p>
            <form action="{{ route('admin.migrate') }}" method="POST" class="migrate-form">
                @csrf
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-xl transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-database mr-2"></i>
                    <span class="button-text">Migrationları Çalıştır</span>
                    <div class="loading-spinner hidden ml-2">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </button>
            </form>
        </div>

        <!-- Application Optimization -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-rocket text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Uygulama Optimizasyonu</h3>
                    <p class="text-sm text-gray-600">Performansı artır</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Config, route ve view cache'lerini oluşturur ve uygulamayı optimize eder. Üretim ortamında performansı artırır.
            </p>
            <form action="{{ route('admin.optimize') }}" method="POST" class="optimize-form">
                @csrf
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-4 rounded-xl transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-rocket mr-2"></i>
                    <span class="button-text">Uygulamayı Optimize Et</span>
                    <div class="loading-spinner hidden ml-2">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </button>
            </form>
        </div>

        <!-- Clear Cache -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-broom text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Önbellek Temizle</h3>
                    <p class="text-sm text-gray-600">Tüm cache'leri temizle</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Tüm uygulama, config, route ve view cache'lerini temizler. Geliştirme sırasında değişikliklerin görünmesi için kullanılır.
            </p>
            <form action="{{ route('admin.clear-cache') }}" method="POST" class="clear-cache-form">
                @csrf
                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-4 rounded-xl transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-broom mr-2"></i>
                    <span class="button-text">Önbellekleri Temizle</span>
                    <div class="loading-spinner hidden ml-2">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </button>
            </form>
        </div>

        <!-- Composer Update -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-sync-alt text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Composer Güncelle</h3>
                    <p class="text-sm text-gray-600">Paketleri güncelle</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Tüm Composer paketlerini (Laravel, DomPDF, Excel vb.) yeni sürümlere günceller. Güvenlik düzeltmeleri ve yeni özellikler için önemlidir.
            </p>
            <form action="{{ route('admin.composer-update') }}" method="POST" class="composer-update-form" onsubmit="return confirm('Composer paketlerini güncellemek istediğinizden emin misiniz? Bu işlem birkaç dakika sürebilir.');">
                @csrf
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-xl transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-sync-alt mr-2"></i>
                    <span class="button-text">Paketleri Güncelle</span>
                    <div class="loading-spinner hidden ml-2">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </button>
            </form>
        </div>

    </div>

    <!-- Warning Notice -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">Önemli Uyarılar</h3>
                <ul class="text-yellow-700 text-sm space-y-1">
                    <li>• Bu işlemler sadece süper admin kullanıcıları tarafından gerçekleştirilebilir.</li>
                    <li>• Migration işlemi sırasında veritabanı geçici olarak kilitlenebilir.</li>
                    <li>• Optimizasyon işlemi üretim ortamında performansı artırır.</li>
                    <li>• Cache temizleme işleminden sonra ilk sayfa yüklemeleri biraz daha yavaş olabilir.</li>
                    <li>• <strong>Composer Update:</strong> Bu işlem birkaç dakika sürebilir. Güncellemeden önce yedek alınması önerilir.</li>
                    <li>• Composer güncellemesi sonrası sistem otomatik olarak cache'leri yeniden oluşturur.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
            Sistem Bilgileri
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="text-sm text-gray-600">Laravel Sürümü</div>
                <div class="text-lg font-semibold text-gray-900">{{ app()->version() }}</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="text-sm text-gray-600">PHP Sürümü</div>
                <div class="text-lg font-semibold text-gray-900">{{ PHP_VERSION }}</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="text-sm text-gray-600">Ortam</div>
                <div class="text-lg font-semibold text-gray-900">{{ app()->environment() }}</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="text-sm text-gray-600">Debug Modu</div>
                <div class="text-lg font-semibold text-gray-900">{{ config('app.debug') ? 'Açık' : 'Kapalı' }}</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submissions with loading states
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            const buttonText = button.querySelector('.button-text');
            const spinner = button.querySelector('.loading-spinner');

            // Disable button and show loading
            button.disabled = true;
            button.classList.add('opacity-75', 'cursor-not-allowed');
            buttonText.textContent = 'İşlem yapılıyor...';
            spinner.classList.remove('hidden');

            // Re-enable after 30 seconds as failsafe
            setTimeout(() => {
                button.disabled = false;
                button.classList.remove('opacity-75', 'cursor-not-allowed');
                spinner.classList.add('hidden');

                // Reset button text based on form class
                if (form.classList.contains('optimize-form')) {
                    buttonText.textContent = 'Uygulamayı Optimize Et';
                } else if (form.classList.contains('clear-cache-form')) {
                    buttonText.textContent = 'Önbellekleri Temizle';
                } else if (form.classList.contains('migrate-form')) {
                    buttonText.textContent = 'Migrationları Çalıştır';
                } else if (form.classList.contains('composer-update-form')) {
                    buttonText.textContent = 'Paketleri Güncelle';
                }
            }, 30000);
        });
    });
});
</script>
@endsection

