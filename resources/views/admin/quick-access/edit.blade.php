@extends('admin.layouts.app')

@section('title', 'Hızlı Erişim Düzenle')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-teal-800">Hızlı Erişim Düzenle</h1>
            <a href="{{ route('admin.quick-access.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-2"></i>Geri Dön
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Form Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Hızlı Erişim Bilgileri</h2>
                <form action="{{ route('admin.quick-access.update', $quickAccess) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Title - Multilingual -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-teal-800 mb-2">Başlık *</label>
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
                            <input type="text" name="title_tr" id="title_tr" value="{{ old('title_tr', $quickAccess->getRawOriginal('title_tr')) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                   oninput="updatePreview()" placeholder="Türkçe başlık giriniz">
                            @error('title_tr')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- German Title -->
                        <div id="title-tab-content-de" class="tab-content hidden">
                            <input type="text" name="title_de" id="title_de" value="{{ old('title_de', $quickAccess->getRawOriginal('title_de')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                   oninput="updatePreview()" placeholder="Almanca başlık giriniz">
                            @error('title_de')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description - Multilingual -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-teal-800 mb-2">Açıklama</label>
                        <!-- Tab Navigation -->
                        <div class="flex border-b border-gray-200 mb-2">
                            <button type="button" onclick="switchTab('description', 'tr')" 
                                    class="px-4 py-2 text-sm font-medium border-b-2 border-teal-600 text-teal-600 tab-button active" 
                                    id="description-tab-tr">
                                Türkçe
                            </button>
                            <button type="button" onclick="switchTab('description', 'de')" 
                                    class="px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 tab-button" 
                                    id="description-tab-de">
                                Almanca
                            </button>
                        </div>
                        <!-- Turkish Description -->
                        <div id="description-tab-content-tr" class="tab-content">
                            <textarea name="description_tr" id="description_tr" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                      oninput="updatePreview()" placeholder="Türkçe açıklama giriniz">{{ old('description_tr', $quickAccess->getRawOriginal('description_tr')) }}</textarea>
                            @error('description_tr')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- German Description -->
                        <div id="description-tab-content-de" class="tab-content hidden">
                            <textarea name="description_de" id="description_de" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                      oninput="updatePreview()" placeholder="Almanca açıklama giriniz">{{ old('description_de', $quickAccess->getRawOriginal('description_de')) }}</textarea>
                            @error('description_de')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="icon" class="block text-sm font-medium text-teal-800 mb-2">İkon *</label>
                        <div class="flex items-center space-x-4">
                            <input type="text" name="icon" id="icon" value="{{ old('icon', $quickAccess->icon) }}" required
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                   placeholder="fa-cog" oninput="updatePreview()">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white" id="simple-icon-preview" style="background-color: {{ old('icon_color', $quickAccess->icon_color ?? '#14b8a6') }}">
                                <i class="fas {{ $quickAccess->icon }}"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Font Awesome ikon adını girin (örn: fa-cog, fa-home, fa-user)</p>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="icon_color" class="block text-sm font-medium text-teal-800 mb-2">İkon Rengi *</label>
                        <div class="flex items-center space-x-4">
                            <input type="color" name="icon_color" id="icon_color" value="{{ old('icon_color', $quickAccess->icon_color ?? '#14b8a6') }}" required
                                   class="w-16 h-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                   oninput="updatePreview()">
                            <input type="text" value="{{ old('icon_color', $quickAccess->icon_color ?? '#14b8a6') }}" readonly
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                                   id="color-text">
                        </div>
                        <!-- Gradient Preview -->
                        <div class="mt-2 p-3 rounded-lg border" id="gradient-preview">
                            <div class="text-sm text-gray-600 mb-2">Gradyan Önizleme:</div>
                            <div class="h-8 rounded-md" id="gradient-sample"></div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">İkon için renk seçin</p>
                        @error('icon_color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="url" class="block text-sm font-medium text-teal-800 mb-2">URL *</label>
                        <input type="url" name="url" id="url" value="{{ old('url', $quickAccess->url) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                               placeholder="https://example.com" oninput="updatePreview()">
                        <p class="mt-1 text-sm text-gray-500">Kartın yönlendireceği link adresini girin</p>
                        @error('url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="sort_order" class="block text-sm font-medium text-teal-800 mb-2">Sıralama</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $quickAccess->sort_order) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $quickAccess->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-teal-800">Aktif</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.quick-access.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                            İptal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition-colors">
                            Güncelle
                        </button>
                    </div>
                </form>
            </div>

            <!-- Live Preview Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Canlı Önizleme</h2>
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="text-sm text-gray-600 mb-4">Kartınız ana sayfada şu şekilde görünecek:</div>
                    
                    <!-- Live Preview Card -->
                    <div class="max-w-sm mx-auto">
                        <div class="group relative transform transition-all duration-500">
                            <!-- Glass morphism card with backdrop blur -->
                            <div class="relative overflow-hidden rounded-2xl bg-white/70 backdrop-blur-md border border-white/20 shadow-xl transition-all duration-500 p-6 text-center h-[200px] flex flex-col justify-between" id="preview-card">
                                
                                <!-- Subtle pattern overlay -->
                                <div class="absolute inset-0 opacity-5">
                                    <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <pattern id="preview-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                                <circle cx="10" cy="10" r="2" fill="currentColor"/>
                                            </pattern>
                                        </defs>
                                        <rect width="100%" height="100%" fill="url(#preview-pattern)" id="pattern-rect"/>
                                    </svg>
                                </div>

                                <!-- Dynamic gradient background -->
                                <div class="absolute inset-0 opacity-20 transition-all duration-500 rounded-2xl" id="preview-gradient"></div>

                                <!-- Top section with icon -->
                                <div class="relative z-10">
                                    <!-- Icon with enhanced styling -->
                                    <div class="relative w-16 h-16 mx-auto mb-4">
                                        <!-- Animated ring -->
                                        <div class="absolute inset-0 rounded-full opacity-20 transition-all duration-500" id="preview-ring"></div>
                                        
                                        <!-- Main icon container -->
                                        <div class="relative w-full h-full rounded-full flex items-center justify-center text-white shadow-lg transition-all duration-300" id="preview-icon-container">
                                            <i class="fas fa-cog text-xl transition-transform duration-300" id="preview-icon"></i>
                                        </div>
                                        
                                        <!-- Pulse effect -->
                                        <div class="absolute inset-0 rounded-full opacity-30 transition-all duration-700" id="preview-pulse"></div>
                                    </div>

                                    <!-- Title -->
                                    <h4 class="text-lg font-bold text-gray-800 mb-2 leading-tight transition-colors duration-300" id="preview-title">{{ $quickAccess->getRawOriginal('title_tr') ?? 'Örnek Başlık' }}</h4>
                                    
                                    <!-- Description -->
                                    <p class="text-xs text-gray-600 leading-relaxed mb-2 transition-colors duration-300 hidden" id="preview-description"></p>
                                </div>

                                <!-- Bottom section -->
                                <div class="relative z-10">
                                    <!-- Link indicator -->
                                    <div class="transition-all duration-300" id="preview-button-container">
                                        <div class="inline-flex items-center px-4 py-2 rounded-full text-white font-medium text-sm shadow-lg transition-all duration-300" id="preview-button">
                                            <span>{{ __('common.go_to_site') }}</span>
                                            <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Decorative corner elements -->
                                <div class="absolute top-0 right-0 w-20 h-20 opacity-5">
                                    <div class="w-full h-full rounded-bl-full" id="preview-corner1"></div>
                                </div>
                                <div class="absolute bottom-0 left-0 w-16 h-16 opacity-5">
                                    <div class="w-full h-full rounded-tr-full" id="preview-corner2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Helper function to generate darker color
function generateDarkerColor(hex) {
    // Remove # if present
    hex = hex.replace('#', '');
    
    // Parse RGB
    const r = parseInt(hex.substr(0, 2), 16);
    const g = parseInt(hex.substr(2, 2), 16);
    const b = parseInt(hex.substr(4, 2), 16);
    
    // Generate darker shade
    const darkerR = Math.max(0, r - 30);
    const darkerG = Math.max(0, g - 30);
    const darkerB = Math.max(0, b - 30);
    
    // Convert back to hex
    const toHex = (c) => {
        const hex = c.toString(16);
        return hex.length === 1 ? '0' + hex : hex;
    };
    
    return '#' + toHex(darkerR) + toHex(darkerG) + toHex(darkerB);
}

// Update live preview
function updatePreview() {
    try {
        const titleTrEl = document.getElementById('title_tr');
        const titleDeEl = document.getElementById('title_de');
        const iconEl = document.getElementById('icon');
        const colorEl = document.getElementById('icon_color');
        const urlEl = document.getElementById('url');
        
        if (!iconEl || !colorEl) {
            console.error('Form elements not found');
            return;
        }
        
        // Get title from Turkish or German (prefer Turkish)
        const titleTr = titleTrEl ? titleTrEl.value : '';
        const titleDe = titleDeEl ? titleDeEl.value : '';
        const title = titleTr || titleDe || '{{ $quickAccess->getRawOriginal('title_tr') ?? 'Örnek Başlık' }}';
        
        const icon = iconEl.value || '{{ $quickAccess->icon }}';
        const color = colorEl.value || '{{ $quickAccess->icon_color ?? "#14b8a6" }}';
        const url = urlEl ? urlEl.value : '';
    
    const darkerColor = generateDarkerColor(color);
    
    // Update preview elements
        const previewTitle = document.getElementById('preview-title');
        const previewIcon = document.getElementById('preview-icon');
        const previewDesc = document.getElementById('preview-description');
        
        if (previewTitle) previewTitle.textContent = title;
        if (previewIcon) previewIcon.className = 'fas ' + icon + ' text-xl transition-transform duration-300';
        
        // Update description
        const descTrEl = document.getElementById('description_tr');
        const descDeEl = document.getElementById('description_de');
        if (previewDesc && (descTrEl || descDeEl)) {
            const descTr = descTrEl ? descTrEl.value : '';
            const descDe = descDeEl ? descDeEl.value : '';
            const description = descTr || descDe || '';
            if (description) {
                previewDesc.textContent = description;
                previewDesc.classList.remove('hidden');
            } else {
                previewDesc.classList.add('hidden');
            }
        }
    
    // Update colors
    const gradient = `linear-gradient(135deg, ${color} 0%, ${darkerColor} 100%)`;
        
        const elements = [
            'preview-gradient',
            'preview-ring', 
            'preview-icon-container',
            'preview-button',
            'gradient-sample',
            'simple-icon-preview'
        ];
        
        elements.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                if (id === 'simple-icon-preview') {
                    el.style.backgroundColor = color;
                } else {
                    el.style.background = gradient;
                }
            }
        });
        
        // Update pulse and corners with solid color
        const solidColorElements = ['preview-pulse', 'preview-corner1', 'preview-corner2', 'pattern-rect'];
        solidColorElements.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                if (id === 'pattern-rect') {
                    el.style.color = color;
                } else {
                    el.style.background = color;
                }
            }
        });
    
        // Update simple icon preview icon
        const simpleIconEl = document.querySelector('#simple-icon-preview i');
        if (simpleIconEl) {
            simpleIconEl.className = 'fas ' + icon;
        }
    
    // Update color text
        const colorTextEl = document.getElementById('color-text');
        if (colorTextEl) {
            colorTextEl.value = color;
        }
    
    // Show/hide button based on URL
    const buttonContainer = document.getElementById('preview-button-container');
        if (buttonContainer) {
    if (url && url.trim() !== '') {
        buttonContainer.style.display = 'block';
        buttonContainer.style.opacity = '1';
    } else {
                buttonContainer.style.display = 'block';
                buttonContainer.style.opacity = '0.5';
            }
        }
        
    } catch (error) {
        console.error('Preview update error:', error);
    }
}

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
    
    // Update preview when switching tabs
    updatePreview();
}

// Initialize preview on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing edit preview...');
    
    // Initial preview update
    setTimeout(updatePreview, 100);
    
    // Add event listeners with error handling
    const elements = [
        { id: 'title_tr', event: 'input' },
        { id: 'title_de', event: 'input' },
        { id: 'description_tr', event: 'input' },
        { id: 'description_de', event: 'input' },
        { id: 'icon', event: 'input' },
        { id: 'icon_color', event: 'input' },
        { id: 'url', event: 'input' }
    ];
    
    elements.forEach(({ id, event }) => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener(event, updatePreview);
            console.log(`Event listener added to ${id}`);
        } else {
            console.error(`Element ${id} not found`);
        }
    });
    
    // Also listen for color input change
    const colorInput = document.getElementById('icon_color');
    if (colorInput) {
        colorInput.addEventListener('change', updatePreview);
    }
});
</script>
@endsection
