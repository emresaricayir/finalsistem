@extends('admin.layouts.app')

@section('title', 'Yönetim Kurulu Üyesi Düzenle')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-teal-800">Yönetim Kurulu Üyesi Düzenle</h1>
            <a href="{{ route('admin.board-members.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-2"></i>Geri Dön
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('admin.board-members.update', $boardMember) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-teal-800 mb-2">Ad Soyad *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $boardMember->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                           placeholder="Ad Soyad">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title - Multilingual -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-teal-800 mb-2">Görev *</label>
                    <!-- Tab Navigation -->
                    <div class="flex border-b border-gray-200 mb-2">
                        <button type="button" onclick="switchTab('title', 'tr')" 
                                class="px-4 py-2 text-sm font-medium border-b-2 border-teal-600 text-teal-600 tab-button active" 
                                id="title-tab-tr">
                            Türkçe
                        </button>
                        <button type="button" onclick="switchTab('title', 'de')" 
                                class="px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 tab-button" 
                                id="title-tab-de">
                            Almanca
                        </button>
                    </div>
                    <!-- Turkish Title -->
                    <div id="title-tab-content-tr" class="tab-content">
                        <input type="text" name="title_tr" id="title_tr" value="{{ old('title_tr', $boardMember->getRawOriginal('title_tr')) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                               placeholder="Başkan">
                        @error('title_tr')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- German Title -->
                    <div id="title-tab-content-de" class="tab-content hidden">
                        <input type="text" name="title_de" id="title_de" value="{{ old('title_de', $boardMember->getRawOriginal('title_de')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                               placeholder="Vorsitzender">
                        @error('title_de')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="category_id" class="block text-sm font-medium text-teal-800 mb-2">Kategori</label>
                    <select name="category_id" id="category_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">Kategori Seçin</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                    {{ old('category_id', $boardMember->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-teal-800 mb-2">Fotoğraf</label>

                    @if($boardMember->image_path)
                        <div class="mb-3">
                            <p class="text-sm text-gray-600 mb-2">Mevcut Fotoğraf:</p>
                            <img src="{{ asset('storage/' . $boardMember->image_path) }}" alt="{{ $boardMember->name }}" class="w-24 h-24 rounded-lg object-cover border border-gray-200">
                        </div>
                    @endif

                    <input type="file" name="image" id="image" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">JPG, PNG, GIF formatında, maksimum 2MB. Boş bırakırsanız mevcut fotoğraf korunur.</p>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="sort_order" class="block text-sm font-medium text-teal-800 mb-2">Sıralama (Opsiyonel)</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $boardMember->sort_order) }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Görev "Başkan" ise otomatik en üstte görünür</p>
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $boardMember->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-teal-800">Aktif</span>
                    </label>
                </div>

                <!-- Bio - Multilingual -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-teal-800 mb-2">Kısa Özgeçmiş</label>
                    <!-- Tab Navigation -->
                    <div class="flex border-b border-gray-200 mb-2">
                        <button type="button" onclick="switchTab('bio', 'tr')" 
                                class="px-4 py-2 text-sm font-medium border-b-2 border-teal-600 text-teal-600 tab-button active" 
                                id="bio-tab-tr">
                            Türkçe
                        </button>
                        <button type="button" onclick="switchTab('bio', 'de')" 
                                class="px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 tab-button" 
                                id="bio-tab-de">
                            Almanca
                        </button>
                    </div>
                    <!-- Turkish Bio -->
                    <div id="bio-tab-content-tr" class="tab-content">
                        <textarea name="bio_tr" id="bio_tr" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Kısa özgeçmiş...">{{ old('bio_tr', $boardMember->getRawOriginal('bio_tr')) }}</textarea>
                        @error('bio_tr')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- German Bio -->
                    <div id="bio-tab-content-de" class="tab-content hidden">
                        <textarea name="bio_de" id="bio_de" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Kurze Biografie...">{{ old('bio_de', $boardMember->getRawOriginal('bio_de')) }}</textarea>
                        @error('bio_de')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-teal-800 mb-2">E-posta</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $boardMember->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="ornek@site.com">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-teal-800 mb-2">Telefon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $boardMember->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="+49 123 456 78 90">
                        @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="website" class="block text-sm font-medium text-teal-800 mb-2">Web Sitesi</label>
                        <input type="url" name="website" id="website" value="{{ old('website', $boardMember->website) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="https://...">
                        @error('website')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="linkedin" class="block text-sm font-medium text-teal-800 mb-2">LinkedIn</label>
                        <input type="url" name="linkedin" id="linkedin" value="{{ old('linkedin', $boardMember->linkedin) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="https://linkedin.com/in/...">
                        @error('linkedin')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label for="facebook" class="block text-sm font-medium text-teal-800 mb-2">Facebook</label>
                        <input type="url" name="facebook" id="facebook" value="{{ old('facebook', $boardMember->facebook) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="https://facebook.com/...">
                        @error('facebook')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="twitter" class="block text-sm font-medium text-teal-800 mb-2">X (Twitter)</label>
                        <input type="url" name="twitter" id="twitter" value="{{ old('twitter', $boardMember->twitter) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="https://x.com/...">
                        @error('twitter')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="instagram" class="block text-sm font-medium text-teal-800 mb-2">Instagram</label>
                        <input type="url" name="instagram" id="instagram" value="{{ old('instagram', $boardMember->instagram) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="https://instagram.com/...">
                        @error('instagram')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.board-members.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                        İptal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition-colors">
                        Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
// Tab switching function (global scope)
function switchTab(field, lang) {
    // Hide all tab contents for this field
    document.querySelectorAll(`#${field}-tab-content-tr, #${field}-tab-content-de`).forEach(el => {
        el.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll(`#${field}-tab-tr, #${field}-tab-de`).forEach(el => {
        el.classList.remove('active', 'border-teal-600', 'text-teal-600');
        el.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(`${field}-tab-content-${lang}`).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(`${field}-tab-${lang}`);
    activeTab.classList.add('active', 'border-teal-600', 'text-teal-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}
</script>
@endsection

