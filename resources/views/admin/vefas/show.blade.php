@extends('admin.layouts.app')

@section('title', 'Vefa Fotoğrafı Detayı')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Vefa Fotoğrafı Detayı</h1>
            <p class="mt-1 text-sm text-gray-600">Fotoğraf bilgilerini görüntüleyin</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.vefas.edit', $vefa) }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                <i class="fas fa-edit mr-2"></i>
                Düzenle
            </a>
            <a href="{{ route('admin.vefas.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Geri Dön
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Photo -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="aspect-w-16 aspect-h-12">
                <img src="{{ $vefa->image_url }}"
                     alt="{{ $vefa->image_alt ?? $vefa->title }}"
                     class="w-full h-96 object-cover">
            </div>

            <!-- TV Display Link -->
            <div class="p-6 border-t border-gray-200">
                <a href="{{ route('vefas.display') }}" target="_blank"
                   class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white text-sm font-medium rounded-lg hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                    <i class="fas fa-tv mr-2"></i>
                    TV'de Görüntüle
                </a>
            </div>
        </div>

        <!-- Details -->
        <div class="space-y-6">
            <!-- Basic Info -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Temel Bilgiler</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Başlık</label>
                        <p class="text-lg text-gray-900">{{ $vefa->title }}</p>
                    </div>

                    @if($vefa->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Açıklama</label>
                        <p class="text-gray-900">{{ $vefa->description }}</p>
                    </div>
                    @endif

                    @if($vefa->image_alt)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Alt Metin</label>
                        <p class="text-gray-900">{{ $vefa->image_alt }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Display Settings -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Gösterim Ayarları</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Durum</span>
                        @if($vefa->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                Pasif
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Gösterim Süresi</span>
                        <span class="text-sm text-gray-900">{{ $vefa->display_duration }} saniye</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Sıralama</span>
                        <span class="text-sm text-gray-900">{{ $vefa->sort_order }}</span>
                    </div>
                </div>
            </div>

            <!-- File Info -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Dosya Bilgileri</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Dosya Yolu</label>
                        <p class="text-sm text-gray-900 font-mono bg-gray-100 px-3 py-2 rounded">{{ $vefa->image_path }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Oluşturulma Tarihi</label>
                        <p class="text-sm text-gray-900">{{ $vefa->created_at->format('d.m.Y H:i') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Son Güncelleme</label>
                        <p class="text-sm text-gray-900">{{ $vefa->updated_at->format('d.m.Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">İşlemler</h3>

                <div class="flex flex-col space-y-3">
                    <a href="{{ route('admin.vefas.edit', $vefa) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        Düzenle
                    </a>

                    <form action="{{ route('admin.vefas.destroy', $vefa) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200"
                                onclick="return confirm('Bu vefa fotoğrafını silmek istediğinizden emin misiniz?')">
                            <i class="fas fa-trash mr-2"></i>
                            Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
