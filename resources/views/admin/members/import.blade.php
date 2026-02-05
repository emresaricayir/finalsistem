@extends('admin.layouts.app')

@section('title', 'Excel\'den Üye Aktarma')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
                         <h1 class="text-2xl font-semibold text-gray-900">
                 <i class="fas fa-file-excel mr-2 text-green-600"></i>
                 Excel'den Üye Aktarma
             </h1>
             <p class="text-gray-600 mt-1">Excel dosyasından toplu üye ekleme yapın</p>
        </div>
        <a href="{{ route('admin.members.index') }}" class="btn-secondary px-4 py-2 rounded-xl font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Geri Dön
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Import Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                                         <h3 class="text-lg font-semibold text-gray-900">
                         <i class="fas fa-upload mr-2 text-blue-600"></i>
                         Excel Dosyası Yükle
                     </h3>
                </div>

                <div class="p-6">
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-lg mb-6">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle mr-3 mt-0.5"></i>
                                <div class="flex-1">
                                    <h4 class="font-semibold mb-2">Lütfen aşağıdaki hataları düzeltin:</h4>
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('excel_errors'))
                        <div class="bg-orange-50 border border-orange-200 text-orange-800 px-6 py-4 rounded-lg mb-6">
                            <div class="flex items-start">
                                <i class="fas fa-list-alt mr-3 mt-0.5"></i>
                                <div class="flex-1">
                                    <h4 class="font-semibold mb-3">Excel Dosyasındaki Hata Detayları:</h4>
                                    <div class="space-y-3 max-h-96 overflow-y-auto">
                                        @foreach (session('excel_errors') as $error)
                                            <div class="bg-white border border-orange-200 rounded-lg p-3">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center mb-1">
                                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full mr-2">
                                                                Satır {{ $error['row'] }}
                                                            </span>
                                                            <span class="text-sm font-medium text-gray-700">
                                                                {{ ucfirst($error['attribute']) }} Alanı
                                                            </span>
                                                        </div>
                                                        <p class="text-sm text-red-700 mb-1">{{ $error['error'] }}</p>
                                                        @if($error['row_info'])
                                                            <p class="text-xs text-gray-600">{{ $error['row_info'] }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <div class="flex items-start">
                                            <i class="fas fa-lightbulb text-blue-600 mr-2 mt-0.5"></i>
                                            <div class="text-sm text-blue-800">
                                                <p class="font-semibold mb-1">Hataları Düzeltmek İçin:</p>
                                                <ul class="space-y-1 text-xs">
                                                    <li>• Hatalı satırları Excel dosyasında düzeltin</li>
                                                    <li>• Gerekli alanları (ad, soyad) doldurun</li>
                                                    <li>• E-mail formatını kontrol edin</li>
                                                    <li>• Aylık aidat sayısal değer olmalıdır</li>
                                                    <li>• Dosyayı tekrar yükleyin</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('admin.members.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- File Upload Area -->
                        <div class="mb-6">
                                                         <label for="excel_file" class="block text-sm font-semibold text-gray-700 mb-3">
                                 <i class="fas fa-file-excel mr-2 text-green-600"></i>
                                 Excel Dosyası Seçin
                             </label>

                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-green-400 transition-colors">
                                <div class="mb-4">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                                </div>
                                                                 <input type="file"
                                        class="hidden"
                                        id="excel_file"
                                        name="excel_file"
                                        accept=".xlsx,.xls,.csv"
                                        required>
                                <label for="excel_file" class="cursor-pointer">
                                    <div class="text-lg font-medium text-gray-700 mb-2">
                                        Dosya seçmek için tıklayın
                                    </div>
                                    <div class="text-sm text-gray-500 mb-4">
                                        veya dosyayı buraya sürükleyin
                                    </div>
                                    <div class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-folder-open mr-2"></i>
                                        Dosya Seç
                                    </div>
                                </label>
                                                                 <div class="mt-4 text-sm text-gray-500">
                                     Desteklenen formatlar: .xlsx, .xls, .csv (Maksimum 10MB)
                                 </div>
                            </div>

                            <!-- File Info Display -->
                            <div id="file-info" class="hidden mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                                    <div>
                                        <div class="font-medium text-green-800" id="file-name"></div>
                                        <div class="text-sm text-green-600" id="file-size"></div>
                                    </div>
                                </div>
                            </div>

                            @error('excel_file')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-medium transition-colors">
                                <i class="fas fa-upload mr-2"></i>
                                Üyeleri İçe Aktar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Template Download -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-download mr-2 text-green-600"></i>
                        Şablon İndir
                    </h3>
                </div>
                <div class="p-6">
                                         <p class="text-gray-600 mb-4">
                         Excel dosyanızı hazırlamak için önce şablon dosyasını indirin ve örnek formatı inceleyin.
                     </p>
                    <a href="{{ route('admin.members.template.download') }}"
                       class="w-full btn-secondary px-4 py-3 rounded-xl font-medium text-center">
                        <i class="fas fa-file-download mr-2"></i>
                        Şablon İndir
                    </a>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        Kullanım Talimatları
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Required Fields -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-asterisk text-red-500 mr-2 text-xs"></i>
                            Gerekli Alanlar
                        </h4>
                        <div class="space-y-2">
                            <div class="flex items-center text-sm">
                                <span class="font-medium text-gray-700 w-24">ad:</span>
                                <span class="text-gray-600">Üye adı</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <span class="font-medium text-gray-700 w-24">soyad:</span>
                                <span class="text-gray-600">Üye soyadı</span>
                            </div>
                        </div>
                    </div>

                    <!-- Optional Fields -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-circle text-gray-400 mr-2 text-xs"></i>
                            İsteğe Bağlı Alanlar
                        </h4>
                        <div class="space-y-2">
                            <div class="flex items-center text-sm">
                                <span class="font-medium text-gray-700 w-24">aylik_aidat:</span>
                                <span class="text-gray-600">Aylık aidat (varsayılan: 5.00)</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <span class="font-medium text-gray-700 w-24">uyelik_tarihi:</span>
                                <span class="text-gray-600">Üyelik tarihi (varsayılan: 2025-01-01)</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <span class="font-medium text-gray-700 w-24">odeme_yontemi:</span>
                                <span class="text-gray-600">Ödeme yöntemi (nakit/banka, varsayılan: banka)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Simple Instructions -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-lightbulb text-blue-600 mr-3 mt-0.5"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-2">Basit Kullanım:</p>
                                <ul class="space-y-1">
                                    <li>• Sadece <strong>ad</strong> ve <strong>soyad</strong> yazmanız yeterli</li>
                                    <li>• Diğer bilgiler otomatik doldurulur</li>
                                    <li>• 10 yıllık aidatlar otomatik oluşturulur</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Large File Information -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-clock text-yellow-600 mr-3 mt-0.5"></i>
                            <div class="text-sm text-yellow-800">
                                <p class="font-semibold mb-2">Büyük Dosyalar İçin:</p>
                                <ul class="space-y-1">
                                    <li>• <strong>600+ kayıt</strong> için 5-10 dakika sürebilir</li>
                                    <li>• İşlem sırasında sayfayı kapatmayın</li>
                                    <li>• Her üye için 10 yıllık aidat oluşturulur</li>
                                    <li>• Toplam işlem süresi: ~30 dakika limit</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Information -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-green-600 mr-3 mt-0.5"></i>
                            <div class="text-sm text-green-800">
                                <p class="font-semibold mb-2">Güvenli İşlem:</p>
                                <ul class="space-y-1">
                                    <li>• <strong>Tüm işlem</strong> tek seferde yapılır</li>
                                    <li>• Hata olursa <strong>hiçbir üye eklenmez</strong></li>
                                    <li>• Üyeler ve aidatlar birlikte oluşturulur</li>
                                    <li>• Yarım kalan işlemler otomatik geri alınır</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Options -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-credit-card text-green-600 mr-3 mt-0.5"></i>
                            <div class="text-sm text-green-800">
                                <p class="font-semibold mb-2">Ödeme Yöntemi Seçenekleri:</p>
                                <ul class="space-y-1">
                                    <li>• <strong>nakit</strong> → Nakit ödeme</li>
                                    <li>• <strong>banka</strong> → Banka transferi</li>
                                    <li>• <strong>lastschrift</strong> → Lastschrift (SEPA)</li>
                                    <li>• Boş bırakılırsa → Banka transferi</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Turkish Character Support -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mr-3 mt-0.5"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-2">Türkçe Karakter Desteği:</p>
                                <ul class="space-y-1">
                                    <li>• Excel dosyanızı <strong>UTF-8</strong> encoding ile kaydedin</li>
                                    <li>• CSV dosyası kullanıyorsanız <strong>UTF-8 (BOM)</strong> formatını seçin</li>
                                    <li>• Türkçe karakterler (ç, ğ, ı, ö, ş, ü, Ç, Ğ, İ, Ö, Ş, Ü) tam destek</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('excel_file');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');

    // Drag and drop functionality
    const dropZone = document.querySelector('.border-dashed');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-green-400', 'bg-green-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-green-400', 'bg-green-50');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        handleFileSelect(files[0]);
    }

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            handleFileSelect(file);
        }
    });

    function handleFileSelect(file) {
        if (file) {
            // Check file size (10MB = 10485760 bytes)
            if (file.size > 10485760) {
                showError('Dosya boyutu 10MB\'dan büyük olamaz!');
                fileInput.value = '';
                hideFileInfo();
                return;
            }

            // Check file type
                         const allowedTypes = [
                 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                 'application/vnd.ms-excel',
                 'text/csv',
                 'application/csv'
             ];

             const fileExtension = file.name.split('.').pop().toLowerCase();
             const allowedExtensions = ['xlsx', 'xls', 'csv'];

                             if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
                     showError('Sadece Excel (.xlsx, .xls) ve CSV dosyaları desteklenmektedir!');
                fileInput.value = '';
                hideFileInfo();
                return;
            }

            // Show file info
            showFileInfo(file);
        }
    }

    function showFileInfo(file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileInfo.classList.remove('hidden');
    }

    function hideFileInfo() {
        fileInfo.classList.add('hidden');
    }

    function showError(message) {
        // Create error notification
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        errorDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(errorDiv);

        // Remove after 3 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 3000);
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>
@endpush
