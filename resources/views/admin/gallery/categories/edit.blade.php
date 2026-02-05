@extends('admin.layouts.app')

@section('title', 'Galeri Kategorisi Düzenle')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Galeri Kategorisi Düzenle</h1>
    <a href="{{ route('admin.gallery-categories.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>Geri Dön
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.gallery-categories.update', $galleryCategory) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Türkçe Kategori Adı -->
            <div>
                <label for="name_tr" class="block text-sm font-medium text-teal-800 mb-2">Türkçe Kategori Adı *</label>
                <input type="text" name="name_tr" id="name_tr" value="{{ old('name_tr', $galleryCategory->name_tr) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="Kategori adını girin">
                @error('name_tr')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Almanca Kategori Adı -->
            <div>
                <label for="name_de" class="block text-sm font-medium text-teal-800 mb-2">Almanca Kategori Adı</label>
                <input type="text" name="name_de" id="name_de" value="{{ old('name_de', $galleryCategory->name_de) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="Kategoriename eingeben">
                @error('name_de')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-teal-800 mb-2">URL Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $galleryCategory->slug) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-medium text-teal-800 mb-2">Sıralama</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $galleryCategory->sort_order) }}" min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                @error('sort_order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Türkçe Açıklama -->
            <div>
                <label for="description_tr" class="block text-sm font-medium text-teal-800 mb-2">Türkçe Açıklama</label>
                <textarea name="description_tr" id="description_tr" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                          placeholder="Kategori açıklaması">{{ old('description_tr', $galleryCategory->description_tr) }}</textarea>
                @error('description_tr')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Almanca Açıklama -->
            <div>
                <label for="description_de" class="block text-sm font-medium text-teal-800 mb-2">Almanca Açıklama</label>
                <textarea name="description_de" id="description_de" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                          placeholder="Kategoriebeschreibung">{{ old('description_de', $galleryCategory->description_de) }}</textarea>
                @error('description_de')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="cover_image" class="block text-sm font-medium text-teal-800 mb-2">Kapak Resmi</label>
                
                @if($galleryCategory->cover_image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $galleryCategory->cover_image) }}" alt="{{ $galleryCategory->name_tr }}" class="w-32 h-24 object-cover rounded">
                        <p class="text-sm text-gray-500 mt-1">Mevcut kapak resmi</p>
                    </div>
                @endif
                
                <input type="file" name="cover_image" id="cover_image" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <p class="mt-1 text-sm text-gray-500">JPG, PNG, GIF formatında, maksimum 2MB. Boş bırakırsanız mevcut resim korunur.</p>
                @error('cover_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $galleryCategory->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-teal-800">Aktif</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('admin.gallery-categories.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                İptal
            </a>
            <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Güncelle
            </button>
        </div>
    </form>
</div>
@endsection