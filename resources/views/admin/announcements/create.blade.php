@extends('admin.layouts.app')

@section('title', 'Yeni Duyuru Ekle')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-plus mr-2 text-green-500"></i>
                Yeni Duyuru Ekle
            </h1>
            <p class="mt-2 text-gray-600">Yeni bir duyuru oluşturun ve yayınlayın.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.announcements.index') }}" class="btn-secondary px-6 py-3 rounded-xl font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Geri Dön
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Title - Türkçe ve Almanca -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Türkçe Başlık -->
                        <div>
                            <label for="title_tr" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-heading mr-2 text-blue-500"></i>
                                Türkçe Başlık *
                            </label>
                            <input type="text" name="title_tr" id="title_tr" value="{{ old('title_tr') }}" required
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title_tr') border-red-500 @enderror"
                                   placeholder="Türkçe duyuru başlığını girin...">
                            @error('title_tr')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Almanca Başlık -->
                        <div>
                            <label for="title_de" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-heading mr-2 text-green-500"></i>
                                Almanca Başlık
                            </label>
                            <input type="text" name="title_de" id="title_de" value="{{ old('title_de') }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('title_de') border-red-500 @enderror"
                                   placeholder="Almanca duyuru başlığını girin...">
                            @error('title_de')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Content - Türkçe ve Almanca -->
                    <div class="space-y-4">
                        <!-- Türkçe İçerik -->
                        <div>
                            <label for="content_tr" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-2 text-blue-500"></i>
                                Türkçe İçerik
                            </label>
                            <textarea name="content_tr" id="content_tr" rows="8"
                                  class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('content_tr') border-red-500 @enderror"
                                  placeholder="Türkçe duyuru içeriğini yazın... (İsteğe bağlı)">{{ old('content_tr') }}</textarea>
                            @error('content_tr')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Almanca İçerik -->
                        <div>
                            <label for="content_de" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-2 text-green-500"></i>
                                Almanca İçerik
                            </label>
                            <textarea name="content_de" id="content_de" rows="8"
                                  class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('content_de') border-red-500 @enderror"
                                  placeholder="Almanca duyuru içeriğini yazın... (İsteğe bağlı)">{{ old('content_de') }}</textarea>
                            @error('content_de')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">

                    <!-- Image Upload -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image mr-2 text-pink-500"></i>
                            Kapak Görseli
                        </label>
                        <div class="rounded-xl overflow-hidden border border-gray-200 bg-white">
                            <div class="aspect-video bg-gray-100 relative">
                                <img id="imagePreview" src="" alt="Önizleme" class="hidden absolute inset-0 w-full h-full object-cover">
                                <div id="imagePlaceholder" class="absolute inset-0 flex items-center justify-center text-gray-400">
                                    <i class="fas fa-image text-4xl"></i>
                                </div>
                            </div>
                            <div class="p-3">
                                <input type="file" name="image" id="image" accept="image/*" class="w-full text-sm" onchange="previewAnnouncementImage(event)">
                                <p class="text-xs text-gray-500 mt-2">Önerilen oran 16:9. Maksimum 4MB. JPG/PNG/WEBP.</p>
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Options -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-cog mr-2 text-blue-500"></i>
                            Durum Ayarları
                        </h3>

                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="is_active" class="ml-2 text-sm text-gray-700">
                                    Aktif (Yayında)
                                </label>
                            </div>

                        </div>
                    </div>

                    <!-- Date Range -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-calendar mr-2 text-green-500"></i>
                            Yayın Tarihleri
                        </h3>

                        <div class="space-y-3">
                            <div>
                                <label for="start_date" class="block text-xs font-medium text-gray-600 mb-1">Başlangıç Tarihi</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_date') border-red-500 @enderror">
                                @error('start_date')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-xs font-medium text-gray-600 mb-1">Bitiş Tarihi</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_date') border-red-500 @enderror">
                                @error('end_date')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 mt-2">
                            Boş bırakılırsa sürekli yayında kalır.
                        </p>
                    </div>

                    <!-- Sort Order -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sort-numeric-down mr-2 text-orange-500"></i>
                            Sıralama
                        </label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sort_order') border-red-500 @enderror"
                               placeholder="0">
                        <p class="text-xs text-gray-500 mt-1">
                            Küçük sayılar önce gösterilir.
                        </p>
                        @error('sort_order')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.announcements.index') }}" class="btn-secondary px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-times mr-2"></i>İptal
                </a>
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-save mr-2"></i>Duyuru Oluştur
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-set start date to today if not set
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        if (!startDateInput.value) {
            const today = new Date().toISOString().split('T')[0];
            startDateInput.value = today;
        }

    });

    function previewAnnouncementImage(e){
        const file = e.target.files && e.target.files[0];
        const img = document.getElementById('imagePreview');
        const ph = document.getElementById('imagePlaceholder');
        if(!file){
            img.classList.add('hidden');
            ph.classList.remove('hidden');
            return;
        }
        const url = URL.createObjectURL(file);
        img.src = url;
        img.onload = () => URL.revokeObjectURL(url);
        img.classList.remove('hidden');
        ph.classList.add('hidden');
    }
</script>
@endsection
