@extends('admin.layouts.app')

@section('title', 'Yeni Video Kategorisi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-plus mr-2 text-green-500"></i>
                Yeni Video Kategorisi
            </h1>
            <p class="mt-2 text-gray-600">Video galeri için yeni kategori oluşturun.</p>
        </div>
        <a href="{{ route('admin.video-categories.index') }}"
           class="btn-secondary px-6 py-3 rounded-xl font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Geri Dön
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.video-categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form Fields -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Category Name - Türkçe ve Almanca -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Türkçe Kategori Adı -->
                        <div>
                            <label for="name_tr" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag mr-2 text-blue-500"></i>
                                Türkçe Kategori Adı <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="name_tr"
                                   id="name_tr"
                                   required
                                   value="{{ old('name_tr') }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name_tr') border-red-500 @enderror"
                                   placeholder="Kategori adını giriniz">
                            @error('name_tr')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Almanca Kategori Adı -->
                        <div>
                            <label for="name_de" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag mr-2 text-blue-500"></i>
                                Almanca Kategori Adı
                            </label>
                            <input type="text"
                                   name="name_de"
                                   id="name_de"
                                   value="{{ old('name_de') }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name_de') border-red-500 @enderror"
                                   placeholder="Kategoriename eingeben">
                            @error('name_de')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-link mr-2 text-purple-500"></i>
                            Slug
                        </label>
                        <input type="text"
                               name="slug"
                               id="slug"
                               value="{{ old('slug') }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                               placeholder="kategori-adi">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Boş bırakılırsa kategori adından otomatik oluşturulur.
                        </p>
                        @error('slug')
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
                            <textarea name="description_tr"
                                      id="description_tr"
                                      rows="4"
                                      class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description_tr') border-red-500 @enderror"
                                      placeholder="Kategori açıklamasını giriniz">{{ old('description_tr') }}</textarea>
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
                            <textarea name="description_de"
                                      id="description_de"
                                      rows="4"
                                      class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description_de') border-red-500 @enderror"
                                      placeholder="Kategoriebeschreibung eingeben">{{ old('description_de') }}</textarea>
                            @error('description_de')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sidebar Fields -->
                <div class="space-y-6">
                    <!-- Cover Image -->
                    <div>
                        <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image mr-2 text-orange-500"></i>
                            Kapak Resmi
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-gray-400 transition-colors">
                            <input type="file"
                                   name="cover_image"
                                   id="cover_image"
                                   accept="image/*"
                                   class="hidden"
                                   onchange="handleFileSelect(this)">
                            <label for="cover_image" class="cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600 mb-1">Dosya seçin</p>
                                <p class="text-xs text-gray-500" id="file-name">JPEG, PNG, JPG, GIF (Max 2MB)</p>
                            </label>
                        </div>
                        @error('cover_image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sort-numeric-up mr-2 text-indigo-500"></i>
                            Sıralama
                        </label>
                        <input type="number"
                               name="sort_order"
                               id="sort_order"
                               value="{{ old('sort_order', 0) }}"
                               min="0"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sort_order') border-red-500 @enderror">
                        @error('sort_order')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                            <span class="ml-2 text-sm font-medium text-gray-700">
                                <i class="fas fa-check-circle mr-1 text-green-500"></i>
                                Aktif
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.video-categories.index') }}"
                   class="btn-secondary px-6 py-3 rounded-xl font-medium">
                    <i class="fas fa-times mr-2"></i>
                    İptal
                </a>
                <button type="submit" class="btn-primary text-white px-6 py-3 rounded-xl font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-generate slug from name
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

// Handle file selection
function handleFileSelect(input) {
    const fileName = input.files[0]?.name || 'JPEG, PNG, JPG, GIF (Max 2MB)';
    document.getElementById('file-name').textContent = fileName;
}
</script>
@endpush
