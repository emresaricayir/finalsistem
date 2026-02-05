@extends('admin.layouts.app')

@section('title', 'Yeni Kategori')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Yeni Kategori</h1>
            <p class="text-gray-600 text-sm mt-1">Yeni personel kategorisi oluşturun</p>
        </div>
        <a href="{{ route('admin.personnel-categories.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Geri Dön
        </a>
    </div>

    <div class="max-w-2xl">
        <form action="{{ route('admin.personnel-categories.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Dil Seçimi Tabs -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button type="button"
                            onclick="switchTab('tr')"
                            id="tab-tr"
                            class="tab-button active py-4 px-1 border-b-2 font-medium text-sm text-blue-600 border-blue-600">
                        <i class="fas fa-language mr-2"></i>
                        Türkçe
                    </button>
                    <button type="button"
                            onclick="switchTab('de')"
                            id="tab-de"
                            class="tab-button py-4 px-1 border-b-2 font-medium text-sm text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-language mr-2"></i>
                        Almanca
                    </button>
                </nav>
            </div>

            <!-- Türkçe İçerik -->
            <div id="content-tr" class="tab-content">
                <!-- Kategori Adı -->
                <div>
                    <label for="name_tr" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori Adı (Türkçe) <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="name_tr"
                           name="name_tr"
                           value="{{ old('name_tr') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name_tr') border-red-500 @enderror"
                           placeholder="Örn: Yönetim Kurulu, Eski Başkanlar">
                    @error('name_tr')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Açıklama -->
                <div>
                    <label for="description_tr" class="block text-sm font-medium text-gray-700 mb-2">
                        Açıklama (Türkçe)
                    </label>
                    <textarea id="description_tr"
                              name="description_tr"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description_tr') border-red-500 @enderror"
                              placeholder="Kategori hakkında kısa açıklama...">{{ old('description_tr') }}</textarea>
                    @error('description_tr')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Almanca İçerik -->
            <div id="content-de" class="tab-content hidden">
                <!-- Kategori Adı -->
                <div>
                    <label for="name_de" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori Adı (Almanca)
                    </label>
                    <input type="text"
                           id="name_de"
                           name="name_de"
                           value="{{ old('name_de') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name_de') border-red-500 @enderror"
                           placeholder="z.B. Vorstand, Ehemalige Vorsitzende">
                    @error('name_de')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Açıklama -->
                <div>
                    <label for="description_de" class="block text-sm font-medium text-gray-700 mb-2">
                        Açıklama (Almanca)
                    </label>
                    <textarea id="description_de"
                              name="description_de"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description_de') border-red-500 @enderror"
                              placeholder="Kurze Beschreibung der Kategorie...">{{ old('description_de') }}</textarea>
                    @error('description_de')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Renk Seçimi -->
            <div>
                <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                    Kategori Rengi <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center space-x-4">
                    <input type="color"
                           id="color"
                           name="color"
                           value="{{ old('color', '#3B82F6') }}"
                           class="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer @error('color') border-red-500 @enderror">
                    <input type="text"
                           id="color-text"
                           value="{{ old('color', '#3B82F6') }}"
                           class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="#3B82F6">
                </div>
                @error('color')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sıralama -->
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                    Sıralama
                </label>
                <input type="number"
                       id="sort_order"
                       name="sort_order"
                       value="{{ old('sort_order', 0) }}"
                       min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sort_order') border-red-500 @enderror"
                       placeholder="0">
                @error('sort_order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Düşük sayılar önce gösterilir</p>
            </div>

            <!-- Durum -->
            <div class="flex items-center">
                <input type="checkbox"
                       id="is_active"
                       name="is_active"
                       value="1"
                       {{ old('is_active', true) ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                    Kategori aktif olsun
                </label>
            </div>

            <!-- Butonlar -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.personnel-categories.index') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    İptal
                </a>
                <button type="submit"
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Kategori Oluştur
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(lang) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'text-blue-600', 'border-blue-600');
        button.classList.add('text-gray-500', 'border-transparent');
    });

    // Show selected tab content
    document.getElementById('content-' + lang).classList.remove('hidden');

    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + lang);
    activeTab.classList.add('active', 'text-blue-600', 'border-blue-600');
    activeTab.classList.remove('text-gray-500', 'border-transparent');
}

document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('color');
    const colorText = document.getElementById('color-text');

    colorInput.addEventListener('change', function() {
        colorText.value = this.value;
    });

    colorText.addEventListener('input', function() {
        if (this.value.match(/^#[0-9A-F]{6}$/i)) {
            colorInput.value = this.value;
        }
    });
});
</script>
@endsection
