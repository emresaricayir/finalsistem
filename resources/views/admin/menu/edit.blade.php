@extends('admin.layouts.app')

@section('title', 'Menü Düzenle')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-green-600">Menü Düzenle: {{ $menu->title }}</h1>
        <a href="{{ route('admin.menu.index') }}" class="text-blue-600 hover:text-blue-800">← Geri Dön</a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.menu.update', $menu) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Menü Başlığı -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Menü Başlığı *
                </label>
                <input type="text"
                       name="title"
                       id="title"
                       value="{{ old('title', $menu->title) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                       required>
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Üst Menü -->
            <div class="mb-6">
                <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Üst Menü (Opsiyonel)
                </label>
                <select name="parent_id"
                        id="parent_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Ana Menü</option>
                    @foreach($parentMenus as $parentMenu)
                        <option value="{{ $parentMenu->id }}" {{ old('parent_id', $menu->parent_id) == $parentMenu->id ? 'selected' : '' }}>
                            {{ $parentMenu->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Link Tipi -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Link Tipi *</label>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="radio"
                               name="link_type"
                               value="internal"
                               {{ $link_type == 'internal' ? 'checked' : '' }}
                               class="mr-2"
                               required>
                        <span class="text-sm">İç Link (Sitemizdeki bir sayfa)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio"
                               name="link_type"
                               value="external"
                               {{ $link_type == 'external' ? 'checked' : '' }}
                               class="mr-2"
                               required>
                        <span class="text-sm">Dış Link (Başka bir site)</span>
                    </label>
                </div>
            </div>

            <!-- İç Link Seçimi -->
            <div id="internal-link-section" class="mb-6 {{ $link_type == 'internal' ? '' : 'hidden' }}">
                <label for="internal_link" class="block text-sm font-medium text-gray-700 mb-2">
                    Hangi Sayfayı Açsın? *
                </label>
                                <select name="internal_link"
                        id="internal_link"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Sayfa seçin...</option>

                    <!-- Sabit sayfalar -->
                    <option value="welcome" {{ old('internal_link', $menu->slug) == 'welcome' ? 'selected' : '' }}>
                        🏠 Ana Sayfa
                    </option>
                    <option value="news.all" {{ old('internal_link', $menu->slug) == 'news.all' ? 'selected' : '' }}>
                        📰 Tüm Haberler
                    </option>
                    <option value="announcements.all" {{ old('internal_link', $menu->slug) == 'announcements.all' ? 'selected' : '' }}>
                        📢 Tüm Duyurular
                    </option>
                    <option value="board-members.index" {{ old('internal_link', $menu->route_name) == 'board-members.index' ? 'selected' : '' }}>
                        🏛️ Yönetim Kurulu
                    </option>
                    <option value="member.application" {{ old('internal_link', $menu->slug) == 'member.application' ? 'selected' : '' }}>
                        📝 Üyelik Başvurusu
                    </option>
                    <option value="member.login" {{ old('internal_link', $menu->slug) == 'member.login' ? 'selected' : '' }}>
                        🔑 Üye Girişi
                    </option>
                    <option value="gallery.index" {{ old('internal_link', $menu->route_name) == 'gallery.index' ? 'selected' : '' }}>
                        🖼️ Resim Galerisi
                    </option>
                    <option value="video-gallery.index" {{ old('internal_link', $menu->route_name) == 'video-gallery.index' ? 'selected' : '' }}>
                        🎥 Video Galeri
                    </option>
                    <option value="contact.index" {{ old('internal_link', $menu->route_name) == 'contact.index' ? 'selected' : '' }}>
                        📞 İletişim
                    </option>
                    <option value="board-members.index" {{ old('internal_link', $menu->route_name) == 'board-members.index' ? 'selected' : '' }}>
                        👥 Yönetim Kurulu
                    </option>

                    <!-- Dinamik sayfalar -->
                    @foreach(\App\Models\Page::active()->orderBy('title')->get() as $page)
                        <option value="{{ $page->slug }}" {{ old('internal_link', $menu->slug) == $page->slug ? 'selected' : '' }}>
                            {{ $page->title }}
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 mt-1">Sitemizdeki mevcut sayfalardan birini seçin</p>
            </div>

            <!-- Dış Link URL -->
            <div id="external-link-section" class="mb-6 {{ $link_type == 'external' ? '' : 'hidden' }}">
                <label for="external_url" class="block text-sm font-medium text-gray-700 mb-2">
                    Dış Site URL'si *
                </label>
                <input type="url"
                       name="external_url"
                       id="external_url"
                       value="{{ old('external_url', $menu->url) }}"
                       placeholder="https://example.com"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                <p class="text-sm text-gray-500 mt-1">Başka bir siteye link vermek için kullanın</p>
            </div>

            <!-- Sıralama -->
            <div class="mb-6">
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                    Sıralama
                </label>
                <input type="number"
                       name="sort_order"
                       id="sort_order"
                       value="{{ old('sort_order', $menu->sort_order) }}"
                       min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                <p class="text-sm text-gray-500 mt-1">Menüdeki sıralama (küçük sayılar üstte)</p>
            </div>

            <!-- Dropdown Menü -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox"
                           name="has_dropdown"
                           value="1"
                           {{ old('has_dropdown', $menu->has_dropdown) ? 'checked' : '' }}
                           class="mr-2">
                    <span class="text-sm text-gray-700">Alt menüler olsun (Dropdown)</span>
                </label>
                <p class="text-sm text-gray-500 mt-1">Bu menü altında dropdown menü olacak</p>
            </div>

            <!-- Aktif/Pasif -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           {{ old('is_active', $menu->is_active) ? 'checked' : '' }}
                           class="mr-2">
                    <span class="text-sm text-gray-700">Menü aktif olsun</span>
                </label>
            </div>

            <!-- Butonlar -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.menu.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    İptal
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Güncelle
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const linkTypeRadios = document.querySelectorAll('input[name="link_type"]');
    const internalSection = document.getElementById('internal-link-section');
    const externalSection = document.getElementById('external-link-section');
    const internalLinkSelect = document.getElementById('internal_link');
    const externalUrlInput = document.getElementById('external_url');

    function toggleLinkSections() {
        const selectedType = document.querySelector('input[name="link_type"]:checked').value;

        if (selectedType === 'internal') {
            internalSection.classList.remove('hidden');
            externalSection.classList.add('hidden');
            externalUrlInput.removeAttribute('required');
            internalLinkSelect.setAttribute('required', 'required');
        } else {
            internalSection.classList.add('hidden');
            externalSection.classList.remove('hidden');
            internalLinkSelect.removeAttribute('required');
            externalUrlInput.setAttribute('required', 'required');
        }
    }

    linkTypeRadios.forEach(radio => {
        radio.addEventListener('change', toggleLinkSections);
    });
});
</script>
@endsection
