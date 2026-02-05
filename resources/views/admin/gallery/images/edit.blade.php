@extends('admin.layouts.app')

@section('title', 'Galeri Resmi Düzenle')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Galeri Resmi Düzenle</h1>
    <a href="{{ route('admin.gallery-images.index', ['category_id' => $galleryImage->gallery_category_id]) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>Geri Dön
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.gallery-images.update', $galleryImage) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="gallery_category_id" class="block text-sm font-medium text-teal-800 mb-2">Kategori *</label>
                <select name="gallery_category_id" id="gallery_category_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">Kategori Seçin</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('gallery_category_id', $galleryImage->gallery_category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name_tr }}
                        </option>
                    @endforeach
                </select>
                @error('gallery_category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Türkçe Başlık -->
            <div>
                <label for="title_tr" class="block text-sm font-medium text-teal-800 mb-2">Türkçe Resim Başlığı *</label>
                <input type="text" name="title_tr" id="title_tr" value="{{ old('title_tr', $galleryImage->title_tr) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="Resim başlığını girin">
                @error('title_tr')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Almanca Başlık -->
            <div>
                <label for="title_de" class="block text-sm font-medium text-teal-800 mb-2">Almanca Resim Başlığı</label>
                <input type="text" name="title_de" id="title_de" value="{{ old('title_de', $galleryImage->title_de) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="Bildtitel eingeben">
                @error('title_de')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Türkçe Açıklama -->
            <div>
                <label for="description_tr" class="block text-sm font-medium text-teal-800 mb-2">Türkçe Açıklama</label>
                <textarea name="description_tr" id="description_tr" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                          placeholder="Resim açıklaması">{{ old('description_tr', $galleryImage->description_tr) }}</textarea>
                @error('description_tr')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Almanca Açıklama -->
            <div>
                <label for="description_de" class="block text-sm font-medium text-teal-800 mb-2">Almanca Açıklama</label>
                <textarea name="description_de" id="description_de" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                          placeholder="Bildbeschreibung">{{ old('description_de', $galleryImage->description_de) }}</textarea>
                @error('description_de')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="image" class="block text-sm font-medium text-teal-800 mb-2">Resim</label>
                
                @if($galleryImage->image_path)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $galleryImage->image_path) }}" alt="{{ $galleryImage->title_tr }}" class="w-48 h-32 object-cover rounded border">
                        <p class="text-sm text-gray-500 mt-1">Mevcut resim</p>
                    </div>
                @endif
                
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <p class="mt-1 text-sm text-gray-500">JPG, PNG, GIF formatında, maksimum 5MB. Boş bırakırsanız mevcut resim korunur.</p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="alt_text" class="block text-sm font-medium text-teal-800 mb-2">Alt Text</label>
                <input type="text" name="alt_text" id="alt_text" value="{{ old('alt_text', $galleryImage->alt_text) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="Resim alt metni">
                @error('alt_text')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-medium text-teal-800 mb-2">Sıralama</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $galleryImage->sort_order) }}" min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                @error('sort_order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $galleryImage->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-teal-800">Aktif</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('admin.gallery-images.index', ['category_id' => $galleryImage->gallery_category_id]) }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                İptal
            </a>
            <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Güncelle
            </button>
        </div>
    </form>
</div>

<script>
// Image preview for new uploads
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Remove any existing preview
            const existingPreview = document.getElementById('image-preview');
            if (existingPreview) {
                existingPreview.remove();
            }
            
            // Create new preview
            const preview = document.createElement('div');
            preview.id = 'image-preview';
            preview.className = 'mt-4';
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Preview" class="w-48 h-32 object-cover rounded border">
                <p class="text-sm text-gray-500 mt-1">Yeni resim önizlemesi</p>
            `;
            
            // Insert after the file input
            document.getElementById('image').parentNode.appendChild(preview);
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection