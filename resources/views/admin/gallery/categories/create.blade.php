@extends('admin.layouts.app')

@section('title', 'Yeni Galeri Kategorisi')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Yeni Galeri Kategorisi</h1>
    <a href="{{ route('admin.gallery-categories.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>Geri Dön
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.gallery-categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Türkçe Kategori Adı -->
            <div>
                <label for="name_tr" class="block text-sm font-medium text-teal-800 mb-2">Türkçe Kategori Adı *</label>
                <input type="text" name="name_tr" id="name_tr" value="{{ old('name_tr') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="Kategori adını girin">
                @error('name_tr')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Almanca Kategori Adı -->
            <div>
                <label for="name_de" class="block text-sm font-medium text-teal-800 mb-2">Almanca Kategori Adı</label>
                <input type="text" name="name_de" id="name_de" value="{{ old('name_de') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="Kategoriename eingeben">
                @error('name_de')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-teal-800 mb-2">URL Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="Otomatik oluşturulacak">
                <p class="mt-1 text-sm text-gray-500">Boş bırakırsanız kategori adından otomatik oluşturulur.</p>
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-medium text-teal-800 mb-2">Sıralama</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
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
                          placeholder="Kategori açıklaması">{{ old('description_tr') }}</textarea>
                @error('description_tr')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Almanca Açıklama -->
            <div>
                <label for="description_de" class="block text-sm font-medium text-teal-800 mb-2">Almanca Açıklama</label>
                <textarea name="description_de" id="description_de" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                          placeholder="Kategoriebeschreibung">{{ old('description_de') }}</textarea>
                @error('description_de')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="cover_image" class="block text-sm font-medium text-teal-800 mb-2">Kapak Resmi</label>
                <input type="file" name="cover_image" id="cover_image" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <p class="mt-1 text-sm text-gray-500">JPG, PNG, GIF formatında, maksimum 2MB.</p>
                @error('cover_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
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
                <i class="fas fa-save mr-2"></i>Kaydet
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('name_tr').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/ğ/g, 'g')
        .replace(/ü/g, 'u')
        .replace(/ş/g, 's')
        .replace(/ı/g, 'i')
        .replace(/ö/g, 'o')
        .replace(/ç/g, 'c')
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    if (document.getElementById('slug').value === '') {
        document.getElementById('slug').value = slug;
    }
});
</script>
@endsection