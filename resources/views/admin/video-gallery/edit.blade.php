@extends('admin.layouts.app')

@section('title', 'Video Düzenle')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-video mr-2 text-red-500"></i>
                Video Düzenle
            </h1>
            <p class="mt-2 text-gray-600">{{ $videoGallery->title }} videosunu düzenleyin.</p>
        </div>
        <a href="{{ route('admin.video-gallery.index') }}"
           class="btn-secondary px-6 py-3 rounded-xl font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Geri Dön
        </a>
    </div>

    <!-- Current Video Preview -->
    <div class="bg-gray-50 rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-eye mr-2 text-blue-500"></i>
            Mevcut Video
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
            <div class="aspect-video rounded-xl overflow-hidden bg-gray-200">
                <img src="{{ $videoGallery->thumbnail_url }}"
                     alt="{{ $videoGallery->title }}"
                     class="w-full h-full object-cover">
            </div>
            <div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">{{ $videoGallery->title_tr }}</h4>
                @if($videoGallery->description_tr)
                    <p class="text-gray-600 mb-3">{{ $videoGallery->description_tr }}</p>
                @endif
                <a href="{{ $videoGallery->youtube_url }}" target="_blank"
                   class="inline-flex items-center text-red-600 hover:text-red-800 transition-colors">
                    <i class="fab fa-youtube mr-2"></i>
                    YouTube'da İzle
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.video-gallery.update', $videoGallery) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Video Title - Türkçe ve Almanca -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Türkçe Başlık -->
                <div>
                    <label for="title_tr" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading mr-2 text-blue-500"></i>
                        Türkçe Video Başlığı *
                    </label>
                    <input type="text" name="title_tr" id="title_tr" required
                           value="{{ old('title_tr', $videoGallery->title_tr) }}"
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
                           value="{{ old('title_de', $videoGallery->title_de) }}"
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
                                {{ old('video_category_id', $videoGallery->video_category_id) == $category->id ? 'selected' : '' }}>
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
                       value="{{ old('youtube_url', $videoGallery->youtube_url) }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('youtube_url') border-red-500 @enderror"
                       placeholder="https://www.youtube.com/watch?v=...">
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    YouTube video linkini buraya yapıştırın
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
                              placeholder="Video hakkında kısa bir açıklama yazın (isteğe bağlı)">{{ old('description_tr', $videoGallery->description_tr) }}</textarea>
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
                              placeholder="Kurze Beschreibung zum Video (optional)">{{ old('description_de', $videoGallery->description_de) }}</textarea>
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
                       value="{{ old('sort_order', $videoGallery->sort_order) }}"
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
                           {{ old('is_active', $videoGallery->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">
                        <i class="fas fa-eye mr-1 text-green-500"></i>
                        Video aktif olsun
                    </span>
                </label>
                <p class="text-xs text-gray-500 mt-1">
                    Pasif videolar galeride gösterilmez
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
                    Değişiklikleri Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
