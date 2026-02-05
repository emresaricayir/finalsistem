@extends('admin.layouts.app')

@section('title', 'Veliler İçe Aktar')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Veliler İçe Aktar</h1>
                    <p class="text-slate-600 mt-1">Excel dosyası ile velileri toplu olarak ekleyin</p>
                </div>
                <a href="{{ route('admin.education-members.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Velilere Dön
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-600 mt-0.5 mr-3"></i>
                    <div class="text-sm text-green-800">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-600 mt-0.5 mr-3"></i>
                    <div class="text-sm text-red-800">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-600 mt-0.5 mr-3"></i>
                    <div class="text-sm text-red-800">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Import Form -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Excel Dosyası Yükle</h3>

                <form action="{{ route('admin.education-members.import.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label for="file" class="block text-sm font-medium text-slate-700 mb-2">
                                Excel Dosyası <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="file" id="file" required
                                   accept=".xlsx,.xls"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="text-xs text-slate-500 mt-1">
                                Desteklenen formatlar: .xlsx, .xls (Maksimum 10MB)
                            </p>
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.education-members.index') }}"
                               class="px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white text-sm font-medium rounded-lg transition-colors">
                                İptal
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-upload mr-2"></i>
                                İçe Aktar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Excel Template Info -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Excel Şablonu</h3>

                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-medium text-blue-900 mb-2">Gerekli Sütunlar:</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• <strong>veli_adi</strong> - Veli adı (zorunlu)</li>
                            <li>• <strong>veli_soyadi</strong> - Veli soyadı (zorunlu)</li>
                            <li>• <strong>ogrenci_adi</strong> - Öğrenci adı (zorunlu)</li>
                            <li>• <strong>ogrenci_soyadi</strong> - Öğrenci soyadı (zorunlu)</li>
                            <li>• <strong>email</strong> - E-posta (isteğe bağlı)</li>
                            <li>• <strong>telefon</strong> - Telefon (isteğe bağlı)</li>
                            <li>• <strong>aylik_aidat</strong> - Aylık aidat (isteğe bağlı)</li>
                            <li>• <strong>durum</strong> - Durum: active/inactive (isteğe bağlı)</li>
                        </ul>
                    </div>

                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="font-medium text-green-900 mb-2">Önemli Notlar:</h4>
                        <ul class="text-sm text-green-800 space-y-1">
                            <li>• İlk satır sütun başlıkları olmalıdır</li>
                            <li>• Aynı veli ve öğrenci adı/soyadı olan kayıtlar atlanır</li>
                            <li>• E-posta geçersizse boş bırakılır</li>
                            <li>• Telefon numarası sadece rakamlardan oluşmalıdır</li>
                            <li>• Durum belirtilmezse "active" olarak ayarlanır</li>
                        </ul>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="font-medium text-yellow-900 mb-2">Örnek Excel:</h4>
                        <div class="text-xs text-yellow-800 font-mono bg-white p-2 rounded border">
veli_adi | veli_soyadi | ogrenci_adi | ogrenci_soyadi | email | telefon | aylik_aidat | durum<br>
Ahmet | Yılmaz | Mehmet | Yılmaz | ahmet@email.com | 5551234567 | 500 | active<br>
Fatma | Demir | Ayşe | Demir | | 5559876543 | 400 | active
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Download Template -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Excel Şablonu İndir</h3>
            <p class="text-slate-600 mb-4">
                Doğru formatta Excel dosyası oluşturmak için şablonu indirin ve doldurun.
            </p>
            <a href="{{ route('admin.education-members.template') }}"
               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-download mr-2"></i>
                Excel Şablonu İndir (.xlsx)
            </a>
        </div>
    </div>
</div>

@endsection




