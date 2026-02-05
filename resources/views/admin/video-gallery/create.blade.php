@extends('admin.layouts.app')

@section('title', 'Yeni Video Ekle')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-video mr-2 text-red-500"></i>
                Yeni Video Ekle
            </h1>
            <p class="mt-2 text-gray-600">YouTube videosunu video galerisine ekleyin.</p>
        </div>
        <a href="{{ route('admin.video-gallery.index') }}"
           class="btn-secondary px-6 py-3 rounded-xl font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Geri Dön
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.video-gallery.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Video Title - Türkçe ve Almanca -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Türkçe Başlık -->
                <div>
                    <label for="title_tr" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading mr-2 text-blue-500"></i>
                        Türkçe Video Başlığı *
                    </label>
                    <input type="text" name="title_tr" id="title_tr" required
                           value="{{ old('title_tr') }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title_tr') border-red-500 @enderror"
                           placeholder="Video başlığını giriniz">
                    @error('title_tr')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Almanca Başlık -->
                <div>
                    <label for="title_de" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading mr-2 text-blue-500"></i>
                        Almanca Video Başlığı
                    </label>
                    <input type="text" name="title_de" id="title_de"
                           value="{{ old('title_de') }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title_de') border-red-500 @enderror"
                           placeholder="Videotitel eingeben">
                    @error('title_de')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Category -->
            <div>
                <label for="video_category_id" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tags mr-2 text-green-500"></i>
                    Kategori
                </label>
                <select name="video_category_id" id="video_category_id"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('video_category_id') border-red-500 @enderror">
                    <option value="">Kategori Seçiniz</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                                {{ old('video_category_id', $selectedCategory) == $category->id ? 'selected' : '' }}>
                            {{ $category->name_tr }}
                        </option>
                    @endforeach
                </select>
                @error('video_category_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- YouTube URL -->
            <div>
                <label for="youtube_url" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fab fa-youtube mr-2 text-red-500"></i>
                    YouTube URL *
                </label>
                <input type="url" name="youtube_url" id="youtube_url" required
                       value="{{ old('youtube_url') }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('youtube_url') border-red-500 @enderror"
                       placeholder="https://www.youtube.com/watch?v=...">
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    YouTube video linkini buraya yapıştırın (örn: https://www.youtube.com/watch?v=dQw4w9WgXcQ)
                </p>
                @error('youtube_url')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description - Türkçe ve Almanca -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Türkçe Açıklama -->
                <div>
                    <label for="description_tr" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-green-500"></i>
                        Türkçe Açıklama
                    </label>
                    <textarea name="description_tr" id="description_tr" rows="4"
                              class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description_tr') border-red-500 @enderror"
                              placeholder="Video hakkında kısa bir açıklama yazın (isteğe bağlı)">{{ old('description_tr') }}</textarea>
                    @error('description_tr')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Almanca Açıklama -->
                <div>
                    <label for="description_de" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-green-500"></i>
                        Almanca Açıklama
                    </label>
                    <textarea name="description_de" id="description_de" rows="4"
                              class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description_de') border-red-500 @enderror"
                              placeholder="Kurze Beschreibung zum Video (optional)">{{ old('description_de') }}</textarea>
                    @error('description_de')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sort Order -->
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sort-numeric-up mr-2 text-purple-500"></i>
                    Sıra Numarası
                </label>
                <input type="number" name="sort_order" id="sort_order" min="0"
                       value="{{ old('sort_order', 0) }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sort_order') border-red-500 @enderror"
                       placeholder="0">
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Küçük sayılar önce gösterilir (0 = en üstte)
                </p>
                @error('sort_order')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">
                        <i class="fas fa-eye mr-1 text-green-500"></i>
                        Video aktif olsun
                    </span>
                </label>
                <p class="text-xs text-gray-500 mt-1">
                    Pasif videolar galeriде gösterilmez
                </p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.video-gallery.index') }}"
                   class="btn-secondary px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-times mr-2"></i>
                    İptal
                </a>
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Video Ekle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
