@extends('admin.layouts.app')

@section('title', 'Vefa Fotoğrafını Düzenle')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Vefa Fotoğrafını Düzenle</h1>
            <p class="mt-1 text-sm text-gray-600">Vefa fotoğrafı bilgilerini güncelleyin</p>
        </div>
        <a href="{{ route('admin.vefas.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Geri Dön
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.vefas.update', $vefa) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Temel Bilgiler -->
            <div class="px-6 py-4 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Ad Soyad <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="title"
                               id="title"
                               value="{{ old('title', $vefa->title) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('title') border-red-500 @enderror"
                               placeholder="Örn: Merhum Hacı Ahmet Efendi"
                               required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Yeni Vesikalık Fotoğraf
                        </label>
                        <input type="file"
                               name="image"
                               id="image"
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('image') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Boş bırakırsanız mevcut fotoğraf korunur.</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Kişisel Bilgiler -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Kişisel Bilgiler</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Birth Date -->
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Doğum Tarihi
                        </label>
                        <input type="date"
                               name="birth_date"
                               id="birth_date"
                               value="{{ old('birth_date', $vefa->birth_date?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('birth_date') border-red-500 @enderror">
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Death Date -->
                    <div>
                        <label for="death_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Vefat Tarihi
                        </label>
                        <input type="date"
                               name="death_date"
                               id="death_date"
                               value="{{ old('death_date', $vefa->death_date?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('death_date') border-red-500 @enderror">
                        @error('death_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hometown -->
                    <div>
                        <label for="hometown" class="block text-sm font-medium text-gray-700 mb-2">
                            Memleketi
                        </label>
                        <input type="text"
                               name="hometown"
                               id="hometown"
                               value="{{ old('hometown', $vefa->hometown) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('hometown') border-red-500 @enderror"
                               placeholder="Örn: İstanbul, Ankara">
                        @error('hometown')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Burial Place -->
                    <div>
                        <label for="burial_place" class="block text-sm font-medium text-gray-700 mb-2">
                            Defin Yeri
                        </label>
                        <input type="text"
                               name="burial_place"
                               id="burial_place"
                               value="{{ old('burial_place', $vefa->burial_place) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('burial_place') border-red-500 @enderror"
                               placeholder="Örn: Karacaahmet Mezarlığı">
                        @error('burial_place')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Görüntüleme Ayarları -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Görüntüleme Ayarları</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Display Duration -->
                    <div>
                        <label for="display_duration" class="block text-sm font-medium text-gray-700 mb-2">
                            Gösterim Süresi (Saniye) <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               name="display_duration"
                               id="display_duration"
                               value="{{ old('display_duration', $vefa->display_duration) }}"
                               min="1"
                               max="60"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('display_duration') border-red-500 @enderror"
                               required>
                        @error('display_duration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Sıralama <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               name="sort_order"
                               id="sort_order"
                               value="{{ old('sort_order', $vefa->sort_order) }}"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('sort_order') border-red-500 @enderror"
                               required>
                        <p class="mt-1 text-sm text-gray-500">Küçük sayılar önce gösterilir.</p>
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
                        <div class="flex items-center">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $vefa->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm font-medium text-gray-700">Aktif</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Aktif fotoğraflar TV'de gösterilir.</p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.vefas.index') }}"
                   class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                    İptal
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white text-sm font-medium rounded-lg hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Değişiklikleri Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
