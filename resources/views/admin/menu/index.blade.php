@extends('admin.layouts.app')

@section('title', 'Menü Yönetimi')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Menü Yönetimi</h1>
            <p class="text-gray-600 text-sm mt-1">Web sitenizdeki menüleri düzenleyin</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle mr-3 mt-0.5"></i>
                <div>
                    <h4 class="font-semibold mb-2">Lütfen aşağıdaki hataları düzeltin:</h4>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Add/Edit Menu Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">MENÜ EKLE / DÜZENLE</h2>

            <form id="menuForm" method="POST" action="{{ isset($editingMenu) ? route('admin.menu.update', $editingMenu) : route('admin.menu.store') }}">
                @csrf
                @if(isset($editingMenu))
                    @method('PUT')
                @endif

                <!-- Parent Menu Selection -->
                <div class="mb-4">
                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">Üst Menü Seç</label>
                    <select name="parent_id" id="parent_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Üst Menü</option>
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}" {{ (isset($editingMenu) && $editingMenu->parent_id == $menu->id) ? 'selected' : '' }}>
                                {{ $menu->getRawOriginal('title_tr') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Menu Order -->
                <div class="mb-4">
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Menü Sıra</label>
                    <input type="number" name="sort_order" id="sort_order"
                           value="{{ isset($editingMenu) ? $editingMenu->sort_order : '' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="0">
                </div>

                <!-- Menu Name - Türkçe ve Almanca -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Menü Adı</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Türkçe Menü Adı -->
                        <div>
                            <label for="title_tr" class="block text-xs font-medium text-gray-600 mb-1">Türkçe *</label>
                            <input type="text" name="title_tr" id="title_tr"
                                   value="{{ old('title_tr', isset($editingMenu) ? $editingMenu->getRawOriginal('title_tr') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title_tr') border-red-500 @enderror"
                                   placeholder="Türkçe menü adı" required>
                            @error('title_tr')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Almanca Menü Adı -->
                        <div>
                            <label for="title_de" class="block text-xs font-medium text-gray-600 mb-1">Almanca</label>
                            <input type="text" name="title_de" id="title_de"
                                   value="{{ old('title_de', isset($editingMenu) ? $editingMenu->getRawOriginal('title_de') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('title_de') border-red-500 @enderror"
                                   placeholder="Almanca menü adı">
                            @error('title_de')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dropdown Menu Checkbox -->
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="has_dropdown" id="has_dropdown" value="1"
                               {{ old('has_dropdown', (isset($editingMenu) && $editingMenu->has_dropdown)) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">
                            <strong>Dropdown Menü</strong> (Alt menüleri olacak, link gerekmez)
                        </span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500 ml-6">
                        <i class="fas fa-info-circle"></i> İşaretlenirse bu menü altında başka menüler açılacaktır. Link girmenize gerek yoktur.
                    </p>
                </div>

                <!-- Link Type Selection -->
                <div id="link_type_section" class="mb-4">
                    <label for="link_type" class="block text-sm font-medium text-gray-700 mb-2">Bağlantı Sayfası</label>
                    <select name="link_type" id="link_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('link_type') border-red-500 @enderror">
                        <option value="internal" {{ old('link_type', (isset($editingMenu) && $editingMenu->route_name) ? 'internal' : '') == 'internal' ? 'selected' : '' }}>İç Sayfa</option>
                        <option value="external" {{ old('link_type', (isset($editingMenu) && $editingMenu->url) ? 'external' : '') == 'external' ? 'selected' : '' }}>Diğer (Manuel Link Ekle)</option>
                    </select>
                    @error('link_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Internal Link Selection -->
                <div id="internal_link_section" class="mb-4 {{ old('link_type', (isset($editingMenu) && $editingMenu->url) ? 'external' : 'internal') == 'external' ? 'hidden' : '' }}">
                    <label for="internal_link" class="block text-sm font-medium text-gray-700 mb-2">İç Sayfa Seç</label>
                    <select name="internal_link" id="internal_link" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('internal_link') border-red-500 @enderror">
                        <option value="">Sayfa seçin</option>

                        <optgroup label="📜 Ana Sayfalar">
                            <option value="welcome" {{ old('internal_link', (isset($editingMenu) && $editingMenu->route_name == 'welcome') ? 'welcome' : '') == 'welcome' ? 'selected' : '' }}>Anasayfa</option>
                            <option value="news.all" {{ old('internal_link', (isset($editingMenu) && $editingMenu->route_name == 'news.all') ? 'news.all' : '') == 'news.all' ? 'selected' : '' }}>Haberler</option>
                            <option value="announcements.all" {{ old('internal_link', (isset($editingMenu) && $editingMenu->route_name == 'announcements.all') ? 'announcements.all' : '') == 'announcements.all' ? 'selected' : '' }}>Duyurular</option>
                            <option value="personnel-category" {{ old('internal_link', (isset($editingMenu) && $editingMenu->route_name == 'personnel-category') ? 'personnel-category' : '') == 'personnel-category' ? 'selected' : '' }}>Kişi Kategorisi</option>
                            <option value="gallery.index" {{ old('internal_link', (isset($editingMenu) && $editingMenu->route_name == 'gallery.index') ? 'gallery.index' : '') == 'gallery.index' ? 'selected' : '' }}>Galeri</option>
                            <option value="video-gallery.index" {{ old('internal_link', (isset($editingMenu) && $editingMenu->route_name == 'video-gallery.index') ? 'video-gallery.index' : '') == 'video-gallery.index' ? 'selected' : '' }}>Video Galeri</option>
                            <option value="contact.index" {{ old('internal_link', (isset($editingMenu) && $editingMenu->route_name == 'contact.index') ? 'contact.index' : '') == 'contact.index' ? 'selected' : '' }}>İletişim</option>
                            <option value="member.application" {{ old('internal_link', (isset($editingMenu) && $editingMenu->route_name == 'member.application') ? 'member.application' : '') == 'member.application' ? 'selected' : '' }}>Üyelik Başvurusu</option>
                            <option value="member.login" {{ old('internal_link', (isset($editingMenu) && $editingMenu->route_name == 'member.login') ? 'member.login' : '') == 'member.login' ? 'selected' : '' }}>Üye Girişi</option>
                        </optgroup>

                        @if($pages->count() > 0)
                            <optgroup label="📄 Özel Sayfalar">
                                @foreach($pages as $page)
                                    <option value="{{ $page->slug }}" {{ old('internal_link', (isset($editingMenu) && $editingMenu->slug == $page->slug) ? $page->slug : '') == $page->slug ? 'selected' : '' }}>
                                        📄 {{ $page->title }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @else
                            <optgroup label="📄 Özel Sayfalar">
                                <option value="" disabled>Henüz özel sayfa oluşturulmamış</option>
                            </optgroup>
                        @endif

                        @if($events->count() > 0)
                            <optgroup label="📅 Etkinlikler">
                                @foreach($events as $event)
                                    <option value="event-{{ $event->id }}" {{ old('internal_link', (isset($editingMenu) && $editingMenu->slug == 'event-'.$event->id) ? 'event-'.$event->id : '') == 'event-'.$event->id ? 'selected' : '' }}>
                                        📅 {{ $event->getRawTitleAttribute() }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                    @error('internal_link')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if($pages->count() > 0)
                        <div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded text-xs">
                            <i class="fas fa-info-circle text-blue-600 mr-1"></i>
                            <strong>{{ $pages->count() }} adet</strong> özel sayfa mevcut. Yukarıdaki "📄 Özel Sayfalar" bölümünden seçebilirsiniz.
                        </div>
                    @else
                        <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-xs">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-1"></i>
                            Henüz özel sayfa oluşturulmamış. <a href="{{ route('admin.pages.create') }}" class="underline hover:no-underline">Özel sayfa oluşturmak</a> için tıklayın.
                        </div>
                    @endif
                </div>

                <!-- Personnel Category Selection -->
                <div id="personnel_category_section" class="mb-4 {{ (isset($editingMenu) && $editingMenu->route_name == 'personnel-category') ? '' : 'hidden' }}">
                    <label for="personnel_category_id" class="block text-sm font-medium text-gray-700 mb-2">Kişi Kategorisi Seç</label>
                    <select name="personnel_category_id" id="personnel_category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Kategori seçin</option>
                        @foreach($personnelCategories as $category)
                            <option value="{{ $category->id }}" {{ (isset($editingMenu) && $editingMenu->category_id == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- External URL Input -->
                <div id="external_url_section" class="mb-4 {{ (isset($editingMenu) && $editingMenu->route_name) ? 'hidden' : '' }}">
                    <label for="external_url" class="block text-sm font-medium text-gray-700 mb-2">Bağlantı Adresi (Manuel Link Ekle)</label>
                    <input type="url" name="external_url" id="external_url"
                           value="{{ (isset($editingMenu) && $editingMenu->url && $editingMenu->url !== '#') ? $editingMenu->url : '' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="https://example.com">
                            </div>

                <!-- Open in New Tab -->
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="open_new_tab" id="open_new_tab" value="1"
                               {{ old('open_new_tab', (isset($editingMenu) && $editingMenu->open_new_tab)) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Tıklandığında yeni sekmeye gitsin mi?</span>
                    </label>
                            </div>

                <!-- Status Toggle -->
                <div class="mb-6">
                    <label class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Durumu</span>
                        <div class="relative">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ old('is_active', (isset($editingMenu) && $editingMenu->is_active) || !isset($editingMenu)) ? 'checked' : '' }}
                                   class="sr-only">
                            <div class="w-11 h-6 bg-gray-200 rounded-full shadow-inner toggle-bg"></div>
                            <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full shadow toggle-button transition-transform duration-200 ease-in-out"></div>
                        </div>
                    </label>
                    </div>

                <!-- Save Button -->
                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center">
                    <i class="fas fa-check mr-2"></i>
                    Kaydet
                            </button>
                        </form>
        </div>

        <!-- Right Column: Active Menus -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">AKTİF MENÜLER</h2>

            <div class="space-y-3">
                @forelse($menus as $menu)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                        <!-- Ana Menü -->
                        <div class="flex items-center justify-between mb-2">
                            <div class="font-medium text-gray-900">{{ $menu->getRawOriginal('title_tr') }}</div>
                            <div class="flex items-center space-x-1">
                                <a href="{{ route('admin.menu.edit', $menu) }}" class="text-blue-600 hover:text-blue-800 p-2 rounded hover:bg-blue-50" title="Düzenle">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                        <form action="{{ route('admin.menu.destroy', $menu) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                                    <button type="submit" onclick="return confirm('Bu menüyü silmek istediğinizden emin misiniz?')" class="text-red-600 hover:text-red-800 p-2 rounded hover:bg-red-50" title="Sil">
                                        <i class="fas fa-trash text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>

                        <!-- Alt Menüler -->
                @if($menu->children->count() > 0)
                            <div class="ml-4 space-y-2">
                        @foreach($menu->children as $child)
                                    <div class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2 border border-gray-100">
                                        <div class="flex items-center">
                                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                                            <span class="text-gray-700 text-sm">{{ $child->title }}</span>
                                        </div>
                                    <div class="flex items-center space-x-1">
                                            <a href="{{ route('admin.menu.edit', $child) }}" class="text-blue-600 hover:text-blue-800 p-1.5 rounded hover:bg-blue-50" title="Düzenle">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form action="{{ route('admin.menu.destroy', $child) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                                <button type="submit" onclick="return confirm('Bu menüyü silmek istediğinizden emin misiniz?')" class="text-red-600 hover:text-red-800 p-1.5 rounded hover:bg-red-50" title="Sil">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-bars text-3xl mb-2"></i>
                        <p>Henüz menü eklenmemiş</p>
                    </div>
                @endforelse
            </div>
            </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const linkTypeSelect = document.getElementById('link_type');
    const internalSection = document.getElementById('internal_link_section');
    const externalSection = document.getElementById('external_url_section');
    const linkTypeSection = document.getElementById('link_type_section');
    const personnelCategorySection = document.getElementById('personnel_category_section');
    const internalLinkSelect = document.getElementById('internal_link');
    const hasDropdownCheckbox = document.getElementById('has_dropdown');
    const isActiveCheckbox = document.getElementById('is_active');
    const toggleBg = document.querySelector('.toggle-bg');
    const toggleButton = document.querySelector('.toggle-button');

    // Dropdown checkbox handler
    function toggleLinkFields() {
        const externalUrlInput = document.getElementById('external_url');
        
        if (hasDropdownCheckbox.checked) {
            // Dropdown menü - link alanlarını gizle
            linkTypeSection.classList.add('hidden');
            internalSection.classList.add('hidden');
            externalSection.classList.add('hidden');
            personnelCategorySection.classList.add('hidden');
            // Form validation için required kaldır ve disabled yap
            linkTypeSelect.removeAttribute('required');
            internalLinkSelect.removeAttribute('required');
            if (externalUrlInput) {
                externalUrlInput.removeAttribute('required');
                externalUrlInput.disabled = true;
            }
        } else {
            // Normal menü - link alanlarını göster
            linkTypeSection.classList.remove('hidden');
            if (linkTypeSelect.value === 'internal') {
                internalSection.classList.remove('hidden');
                externalSection.classList.add('hidden');
                internalLinkSelect.setAttribute('required', 'required');
                if (externalUrlInput) {
                    externalUrlInput.removeAttribute('required');
                    externalUrlInput.disabled = true;
                }
            } else {
                internalSection.classList.add('hidden');
                externalSection.classList.remove('hidden');
                if (externalUrlInput) {
                    externalUrlInput.setAttribute('required', 'required');
                    externalUrlInput.disabled = false;
                }
                internalLinkSelect.removeAttribute('required');
            }
            linkTypeSelect.setAttribute('required', 'required');
        }
    }

    hasDropdownCheckbox.addEventListener('change', toggleLinkFields);

    // Sayfa yüklendiğinde kontrol et
    toggleLinkFields();
    
    // Sayfa yüklendiğinde external_url alanının durumunu ayarla
    const externalUrlInput = document.getElementById('external_url');
    if (externalUrlInput) {
        // Geçersiz URL değerlerini temizle (# gibi)
        if (externalUrlInput.value === '#' || externalUrlInput.value.trim() === '') {
            externalUrlInput.value = '';
        }
        
        // Eğer alan gizliyse, required ve disabled yap
        if (externalSection.classList.contains('hidden')) {
            externalUrlInput.removeAttribute('required');
            externalUrlInput.disabled = true;
        }
    }

    // Link type change handler
    linkTypeSelect.addEventListener('change', function() {
        if (hasDropdownCheckbox.checked) return; // Dropdown ise değişiklik yapma

        const externalUrlInput = document.getElementById('external_url');
        
        if (this.value === 'internal') {
            internalSection.classList.remove('hidden');
            externalSection.classList.add('hidden');
            personnelCategorySection.classList.add('hidden');
            internalLinkSelect.setAttribute('required', 'required');
            if (externalUrlInput) {
                externalUrlInput.removeAttribute('required');
                externalUrlInput.disabled = true;
            }
        } else {
            internalSection.classList.add('hidden');
            externalSection.classList.remove('hidden');
            personnelCategorySection.classList.add('hidden');
            if (externalUrlInput) {
                externalUrlInput.setAttribute('required', 'required');
                externalUrlInput.disabled = false;
            }
            internalLinkSelect.removeAttribute('required');
        }
    });

    // Internal link change handler
    internalLinkSelect.addEventListener('change', function() {
        if (this.value === 'personnel-category') {
            personnelCategorySection.classList.remove('hidden');
        } else {
            personnelCategorySection.classList.add('hidden');
        }
    });

    // Toggle switch handler
    isActiveCheckbox.addEventListener('change', function() {
        if (this.checked) {
            toggleBg.classList.remove('bg-gray-200');
            toggleBg.classList.add('bg-green-600');
            toggleButton.classList.add('translate-x-5');
        } else {
            toggleBg.classList.remove('bg-green-600');
            toggleBg.classList.add('bg-gray-200');
            toggleButton.classList.remove('translate-x-5');
        }
    });

    // Toggle switch click handler
    const toggleContainer = document.querySelector('.relative');
    if (toggleContainer) {
        toggleContainer.addEventListener('click', function(e) {
            e.preventDefault();
            isActiveCheckbox.checked = !isActiveCheckbox.checked;
            isActiveCheckbox.dispatchEvent(new Event('change'));
        });
    }

    // Initialize toggle state
    if (isActiveCheckbox.checked) {
        toggleBg.classList.add('bg-green-600');
        toggleButton.classList.add('translate-x-5');
    }

    // Form submit handler - Gizli alanların required özelliklerini kaldır
    const menuForm = document.getElementById('menuForm');
    if (menuForm) {
        menuForm.addEventListener('submit', function(e) {
            // Tüm gizli alanların required özelliklerini kaldır
            const externalUrlInput = document.getElementById('external_url');
            const internalLinkSelect = document.getElementById('internal_link');
            
            if (externalSection.classList.contains('hidden') && externalUrlInput) {
                externalUrlInput.removeAttribute('required');
                externalUrlInput.disabled = true;
            }
            
            if (internalSection.classList.contains('hidden') && internalLinkSelect) {
                internalLinkSelect.removeAttribute('required');
            }
            
            if (linkTypeSection.classList.contains('hidden') && linkTypeSelect) {
                linkTypeSelect.removeAttribute('required');
            }
        });
    }
});
</script>
@endsection
