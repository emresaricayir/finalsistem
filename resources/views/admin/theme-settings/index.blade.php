@extends('admin.layouts.app')

@section('title', 'Tema Ayarları')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mr-3 shadow-lg">
                    <i class="fas fa-palette text-white"></i>
                </div>
                Tema Ayarları
            </h1>
            <p class="mt-2 text-gray-600">Sitenizin görünümünü özelleştirin ve marka kimliğinizi yansıtın.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="/" target="_blank" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors flex items-center">
                <i class="fas fa-external-link-alt mr-2"></i>
                Siteyi Görüntüle
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center" role="alert">
        <i class="fas fa-check-circle mr-2 text-green-500"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Sol Panel: Ayarlar -->
        <div class="xl:col-span-2 space-y-6">
            <form action="{{ route('admin.theme-settings.update') }}" method="POST" id="themeForm">
                @csrf
                @method('PUT')

                <!-- Hazır Temalar -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-swatchbook mr-2 text-indigo-500"></i>
                        Hazır Temalar
                    </h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        <!-- Varsayılan Tema -->
                        <button type="button" onclick="window.applyPreset('default', this)" class="preset-btn group relative p-3 rounded-xl border-2 border-gray-200 hover:border-teal-400 transition-all duration-200 bg-white hover:shadow-md">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-6 h-6 rounded-full" style="background: #085952;"></div>
                                <div class="w-6 h-6 rounded-full" style="background: #0a7b73;"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Varsayılan</span>
                        </button>
                        
                        <!-- Ocean Blue -->
                        <button type="button" onclick="window.applyPreset('ocean', this)" class="preset-btn group relative p-3 rounded-xl border-2 border-gray-200 hover:border-blue-400 transition-all duration-200 bg-white hover:shadow-md">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-6 h-6 rounded-full" style="background: #1e40af;"></div>
                                <div class="w-6 h-6 rounded-full" style="background: #3b82f6;"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Ocean Blue</span>
                        </button>
                        
                        <!-- Forest Green -->
                        <button type="button" onclick="window.applyPreset('forest', this)" class="preset-btn group relative p-3 rounded-xl border-2 border-gray-200 hover:border-green-400 transition-all duration-200 bg-white hover:shadow-md">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-6 h-6 rounded-full" style="background: #166534;"></div>
                                <div class="w-6 h-6 rounded-full" style="background: #22c55e;"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Forest Green</span>
                        </button>
                        
                        <!-- Royal Purple -->
                        <button type="button" onclick="window.applyPreset('royal', this)" class="preset-btn group relative p-3 rounded-xl border-2 border-gray-200 hover:border-purple-400 transition-all duration-200 bg-white hover:shadow-md">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-6 h-6 rounded-full" style="background: #7c3aed;"></div>
                                <div class="w-6 h-6 rounded-full" style="background: #a78bfa;"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Royal Purple</span>
                        </button>
                        
                        <!-- Sunset Orange -->
                        <button type="button" onclick="window.applyPreset('sunset', this)" class="preset-btn group relative p-3 rounded-xl border-2 border-gray-200 hover:border-orange-400 transition-all duration-200 bg-white hover:shadow-md">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-6 h-6 rounded-full" style="background: #ea580c;"></div>
                                <div class="w-6 h-6 rounded-full" style="background: #fb923c;"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Sunset Orange</span>
                        </button>
                        
                        <!-- Rose Pink -->
                        <button type="button" onclick="window.applyPreset('rose', this)" class="preset-btn group relative p-3 rounded-xl border-2 border-gray-200 hover:border-pink-400 transition-all duration-200 bg-white hover:shadow-md">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-6 h-6 rounded-full" style="background: #be185d;"></div>
                                <div class="w-6 h-6 rounded-full" style="background: #ec4899;"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Rose Pink</span>
                        </button>
                        
                        <!-- Slate Gray -->
                        <button type="button" onclick="window.applyPreset('slate', this)" class="preset-btn group relative p-3 rounded-xl border-2 border-gray-200 hover:border-slate-400 transition-all duration-200 bg-white hover:shadow-md">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-6 h-6 rounded-full" style="background: #334155;"></div>
                                <div class="w-6 h-6 rounded-full" style="background: #64748b;"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Slate Gray</span>
                        </button>
                        
                        <!-- Amber Gold -->
                        <button type="button" onclick="window.applyPreset('amber', this)" class="preset-btn group relative p-3 rounded-xl border-2 border-gray-200 hover:border-amber-400 transition-all duration-200 bg-white hover:shadow-md">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-6 h-6 rounded-full" style="background: #b45309;"></div>
                                <div class="w-6 h-6 rounded-full" style="background: #fbbf24;"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Amber Gold</span>
                        </button>
                    </div>
                </div>

                <!-- Ana Renkler -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-fill-drip mr-2 text-blue-500"></i>
                        Ana Renkler
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Ana Renk -->
                        <div class="color-input-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-circle mr-1 text-xs" style="color: {{ $themeSettings['primary_color'] }};"></i>
                                Ana Renk (Primary)
                            </label>
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <input type="color" 
                                           name="primary_color" 
                                           id="primary_color" 
                                           value="{{ $themeSettings['primary_color'] }}"
                                           class="w-14 h-14 rounded-xl border-2 border-gray-200 cursor-pointer shadow-inner hover:border-gray-300 transition-colors">
                                </div>
                                <div class="flex-1">
                                    <input type="text" 
                                           id="primary_color_text"
                                           value="{{ $themeSettings['primary_color'] }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm uppercase"
                                           pattern="^#[0-9A-Fa-f]{6}$">
                                    <p class="mt-1 text-xs text-gray-500">Başlıklar, menüler ve ana bileşenler</p>
                                </div>
                            </div>
                        </div>

                        <!-- İkincil Renk -->
                        <div class="color-input-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-circle mr-1 text-xs" style="color: {{ $themeSettings['secondary_color'] }};"></i>
                                İkincil Renk (Secondary)
                            </label>
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <input type="color" 
                                           name="secondary_color" 
                                           id="secondary_color" 
                                           value="{{ $themeSettings['secondary_color'] }}"
                                           class="w-14 h-14 rounded-xl border-2 border-gray-200 cursor-pointer shadow-inner hover:border-gray-300 transition-colors">
                                </div>
                                <div class="flex-1">
                                    <input type="text" 
                                           id="secondary_color_text"
                                           value="{{ $themeSettings['secondary_color'] }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm uppercase"
                                           pattern="^#[0-9A-Fa-f]{6}$">
                                    <p class="mt-1 text-xs text-gray-500">Vurgular ve ikincil elementler</p>
                                </div>
                            </div>
                        </div>

                        <!-- Hover Rengi -->
                        <div class="color-input-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-mouse-pointer mr-1 text-xs text-gray-500"></i>
                                Hover Rengi
                            </label>
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <input type="color" 
                                           name="hover_color" 
                                           id="hover_color" 
                                           value="{{ $themeSettings['hover_color'] }}"
                                           class="w-14 h-14 rounded-xl border-2 border-gray-200 cursor-pointer shadow-inner hover:border-gray-300 transition-colors">
                                </div>
                                <div class="flex-1">
                                    <input type="text" 
                                           id="hover_color_text"
                                           value="{{ $themeSettings['hover_color'] }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm uppercase"
                                           pattern="^#[0-9A-Fa-f]{6}$">
                                    <p class="mt-1 text-xs text-gray-500">Fare üzerine geldiğinde</p>
                                </div>
                            </div>
                        </div>

                        <!-- Buton Rengi -->
                        <div class="color-input-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-hand-pointer mr-1 text-xs text-gray-500"></i>
                                Buton Rengi
                            </label>
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <input type="color" 
                                           name="button_color" 
                                           id="button_color" 
                                           value="{{ $themeSettings['button_color'] }}"
                                           class="w-14 h-14 rounded-xl border-2 border-gray-200 cursor-pointer shadow-inner hover:border-gray-300 transition-colors">
                                </div>
                                <div class="flex-1">
                                    <input type="text" 
                                           id="button_color_text"
                                           value="{{ $themeSettings['button_color'] }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm uppercase"
                                           pattern="^#[0-9A-Fa-f]{6}$">
                                    <p class="mt-1 text-xs text-gray-500">Tüm butonlar için</p>
                                </div>
                            </div>
                        </div>

                        <!-- Link Rengi -->
                        <div class="color-input-group md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-link mr-1 text-xs text-gray-500"></i>
                                Link Rengi
                            </label>
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <input type="color" 
                                           name="link_color" 
                                           id="link_color" 
                                           value="{{ $themeSettings['link_color'] }}"
                                           class="w-14 h-14 rounded-xl border-2 border-gray-200 cursor-pointer shadow-inner hover:border-gray-300 transition-colors">
                                </div>
                                <div class="flex-1">
                                    <input type="text" 
                                           id="link_color_text"
                                           value="{{ $themeSettings['link_color'] }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm uppercase"
                                           pattern="^#[0-9A-Fa-f]{6}$">
                                    <p class="mt-1 text-xs text-gray-500">Tüm linkler ve metin bağlantıları</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gradyan Ayarları -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-sliders-h mr-2 text-purple-500"></i>
                            Gradyan Ayarları
                        </h2>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="use_gradient" 
                                   value="1"
                                   {{ $themeSettings['use_gradient'] == '1' ? 'checked' : '' }}
                                   class="sr-only peer"
                                   id="use_gradient_checkbox">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Gradyan Kullan</span>
                        </label>
                    </div>

                    <div id="gradientSettings" class="space-y-6 transition-opacity duration-300">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Başlangıç Rengi -->
                            <div class="color-input-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-play mr-1 text-xs text-gray-500"></i>
                                    Başlangıç Rengi
                                </label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" 
                                           name="gradient_start" 
                                           id="gradient_start" 
                                           value="{{ $themeSettings['gradient_start'] }}"
                                           class="w-14 h-14 rounded-xl border-2 border-gray-200 cursor-pointer shadow-inner hover:border-gray-300 transition-colors">
                                    <input type="text" 
                                           id="gradient_start_text"
                                           value="{{ $themeSettings['gradient_start'] }}"
                                           class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm uppercase"
                                           pattern="^#[0-9A-Fa-f]{6}$">
                                </div>
                            </div>

                            <!-- Bitiş Rengi -->
                            <div class="color-input-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-stop mr-1 text-xs text-gray-500"></i>
                                    Bitiş Rengi
                                </label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" 
                                           name="gradient_end" 
                                           id="gradient_end" 
                                           value="{{ $themeSettings['gradient_end'] }}"
                                           class="w-14 h-14 rounded-xl border-2 border-gray-200 cursor-pointer shadow-inner hover:border-gray-300 transition-colors">
                                    <input type="text" 
                                           id="gradient_end_text"
                                           value="{{ $themeSettings['gradient_end'] }}"
                                           class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm uppercase"
                                           pattern="^#[0-9A-Fa-f]{6}$">
                                </div>
                            </div>
                        </div>

                        <!-- Gradyan Yönü -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Gradyan Yönü</label>
                            <div class="grid grid-cols-4 gap-2">
                                <button type="button" onclick="window.setGradientDirection('to right')" class="gradient-dir-btn p-3 rounded-lg border-2 border-gray-200 hover:border-purple-400 transition-all flex flex-col items-center" data-direction="to right">
                                    <i class="fas fa-arrow-right text-gray-500 mb-1"></i>
                                    <span class="text-xs text-gray-600">Sağa</span>
                                </button>
                                <button type="button" onclick="window.setGradientDirection('to bottom')" class="gradient-dir-btn p-3 rounded-lg border-2 border-gray-200 hover:border-purple-400 transition-all flex flex-col items-center" data-direction="to bottom">
                                    <i class="fas fa-arrow-down text-gray-500 mb-1"></i>
                                    <span class="text-xs text-gray-600">Aşağı</span>
                                </button>
                                <button type="button" onclick="window.setGradientDirection('to bottom right')" class="gradient-dir-btn p-3 rounded-lg border-2 border-gray-200 hover:border-purple-400 transition-all flex flex-col items-center" data-direction="to bottom right">
                                    <i class="fas fa-arrow-down text-gray-500 mb-1 transform rotate-45"></i>
                                    <span class="text-xs text-gray-600">Çapraz</span>
                                </button>
                                <button type="button" onclick="window.setGradientDirection('to top right')" class="gradient-dir-btn p-3 rounded-lg border-2 border-gray-200 hover:border-purple-400 transition-all flex flex-col items-center" data-direction="to top right">
                                    <i class="fas fa-arrow-up text-gray-500 mb-1 transform rotate-45"></i>
                                    <span class="text-xs text-gray-600">Yukarı Çapraz</span>
                                </button>
                            </div>
                            <input type="hidden" name="gradient_direction" id="gradient_direction" value="{{ $themeSettings['gradient_direction'] }}">
                        </div>

                        <!-- Gradyan Önizleme -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Gradyan Önizleme</label>
                            <div id="gradientPreview" 
                                 class="w-full h-20 rounded-xl shadow-inner border border-gray-200 transition-all duration-300"
                                 style="background: linear-gradient({{ $themeSettings['gradient_direction'] }}, {{ $themeSettings['gradient_start'] }}, {{ $themeSettings['gradient_end'] }});">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between">
                    <button type="button" 
                            onclick="window.resetToDefaults()"
                            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors flex items-center">
                        <i class="fas fa-undo mr-2"></i>
                        Varsayılanlara Dön
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>

        <!-- Sağ Panel: Canlı Önizleme -->
        <div class="xl:col-span-1">
            <div class="sticky top-6 space-y-4">
                <!-- Önizleme Başlığı -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <h3 class="font-bold text-gray-900 flex items-center">
                        <i class="fas fa-eye mr-2 text-green-500"></i>
                        Canlı Önizleme
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Değişiklikler anlık olarak görüntülenir</p>
                </div>

                <!-- Bileşen Önizlemeleri -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-5">
                    <!-- Header/Menu Preview -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Menü & Başlık</label>
                        <div id="preview-header" class="rounded-lg p-3 text-white text-sm font-medium flex items-center justify-between" style="background: linear-gradient({{ $themeSettings['gradient_direction'] }}, {{ $themeSettings['gradient_start'] }}, {{ $themeSettings['gradient_end'] }});">
                            <span><i class="fas fa-mosque mr-2"></i>Site Adı</span>
                            <div class="flex space-x-2 text-xs">
                                <span class="opacity-80">Ana Sayfa</span>
                                <span class="opacity-80">Hakkımızda</span>
                                <span class="opacity-80">İletişim</span>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons Preview -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Butonlar</label>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" id="preview-btn-primary" class="px-4 py-2 rounded-lg text-white text-sm font-medium transition-all hover:shadow-lg" style="background: linear-gradient({{ $themeSettings['gradient_direction'] }}, {{ $themeSettings['gradient_start'] }}, {{ $themeSettings['gradient_end'] }});">
                                Ana Buton
                            </button>
                            <button type="button" id="preview-btn-secondary" class="px-4 py-2 rounded-lg text-white text-sm font-medium transition-all hover:shadow-lg" style="background-color: {{ $themeSettings['secondary_color'] }};">
                                İkincil Buton
                            </button>
                        </div>
                    </div>

                    <!-- Links Preview -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Linkler</label>
                        <div class="flex items-center space-x-4">
                            <a href="#" id="preview-link" class="text-sm font-medium hover:underline transition-colors" style="color: {{ $themeSettings['link_color'] }};">
                                Örnek Link
                            </a>
                            <a href="#" id="preview-link-2" class="text-sm font-medium hover:underline transition-colors" style="color: {{ $themeSettings['link_color'] }};">
                                Tüm Duyurular →
                            </a>
                        </div>
                    </div>

                    <!-- Card Preview -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Kart Başlığı</label>
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div id="preview-card-header" class="px-4 py-2 text-white text-sm font-medium" style="background: linear-gradient({{ $themeSettings['gradient_direction'] }}, {{ $themeSettings['gradient_start'] }}, {{ $themeSettings['gradient_end'] }});">
                                <i class="fas fa-bullhorn mr-2"></i>Duyurular
                            </div>
                            <div class="p-3 bg-gray-50">
                                <div class="text-sm text-gray-600">Kart içeriği buraya gelir...</div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Preview -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Footer</label>
                        <div id="preview-footer" class="rounded-lg p-3 text-white text-xs" style="background: linear-gradient(135deg, #1e293b 0%, {{ $themeSettings['primary_color'] }} 50%, #1e293b 100%);">
                            <div class="flex items-center justify-between">
                                <span>© 2026 Site Adı</span>
                                <div class="flex space-x-2">
                                    <i class="fab fa-facebook text-xs opacity-70"></i>
                                    <i class="fab fa-instagram text-xs opacity-70"></i>
                                    <i class="fab fa-youtube text-xs opacity-70"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Icons Preview -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">İkon Renkleri</label>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center" id="preview-icon-bg" style="background-color: rgba(13, 148, 136, 0.1);">
                                <i class="fas fa-mosque" id="preview-icon" style="color: {{ $themeSettings['link_color'] }};"></i>
                            </div>
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(13, 148, 136, 0.1);">
                                <i class="fas fa-calendar" style="color: {{ $themeSettings['link_color'] }};"></i>
                            </div>
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(13, 148, 136, 0.1);">
                                <i class="fas fa-envelope" style="color: {{ $themeSettings['link_color'] }};"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Etkilenen Alanlar -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100 p-5">
                    <h4 class="font-bold text-gray-800 text-sm mb-3 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                        Temadan Etkilenen Alanlar
                    </h4>
                    <ul class="text-xs text-gray-600 space-y-2">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Ana sayfa header ve menü</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Tüm butonlar ve linkler</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Duyuru ve haber kartları</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Footer arka planı</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>İkonlar ve vurgu renkleri</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Detay sayfaları başlıkları</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Namaz vakitleri kartı</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inline script for immediate availability -->
<script>
// Hazır tema presetleri - Global scope
window.presets = {
    default: {
        primary_color: '#085952',
        secondary_color: '#0a7b73',
        gradient_start: '#076961',
        gradient_end: '#0a7b6e',
        hover_color: '#0f766e',
        button_color: '#0d9488',
        link_color: '#0d9488'
    },
    ocean: {
        primary_color: '#1e40af',
        secondary_color: '#3b82f6',
        gradient_start: '#1e40af',
        gradient_end: '#3b82f6',
        hover_color: '#1d4ed8',
        button_color: '#2563eb',
        link_color: '#2563eb'
    },
    forest: {
        primary_color: '#166534',
        secondary_color: '#22c55e',
        gradient_start: '#166534',
        gradient_end: '#22c55e',
        hover_color: '#15803d',
        button_color: '#16a34a',
        link_color: '#16a34a'
    },
    royal: {
        primary_color: '#7c3aed',
        secondary_color: '#a78bfa',
        gradient_start: '#7c3aed',
        gradient_end: '#a78bfa',
        hover_color: '#6d28d9',
        button_color: '#8b5cf6',
        link_color: '#8b5cf6'
    },
    sunset: {
        primary_color: '#ea580c',
        secondary_color: '#fb923c',
        gradient_start: '#ea580c',
        gradient_end: '#fb923c',
        hover_color: '#c2410c',
        button_color: '#f97316',
        link_color: '#f97316'
    },
    rose: {
        primary_color: '#be185d',
        secondary_color: '#ec4899',
        gradient_start: '#be185d',
        gradient_end: '#ec4899',
        hover_color: '#9d174d',
        button_color: '#db2777',
        link_color: '#db2777'
    },
    slate: {
        primary_color: '#334155',
        secondary_color: '#64748b',
        gradient_start: '#334155',
        gradient_end: '#64748b',
        hover_color: '#1e293b',
        button_color: '#475569',
        link_color: '#475569'
    },
    amber: {
        primary_color: '#b45309',
        secondary_color: '#fbbf24',
        gradient_start: '#b45309',
        gradient_end: '#fbbf24',
        hover_color: '#92400e',
        button_color: '#d97706',
        link_color: '#d97706'
    }
};

// Preset uygula - Global function
window.applyPreset = function(presetName, buttonElement) {
    console.log('applyPreset called:', presetName);
    const preset = window.presets[presetName];
    if (!preset) {
        console.error('Preset not found:', presetName);
        return;
    }
    
    Object.keys(preset).forEach(function(key) {
        const colorInput = document.getElementById(key);
        const textInput = document.getElementById(key + '_text');
        
        if (colorInput) {
            colorInput.value = preset[key];
            console.log('Set', key, 'to', preset[key]);
        }
        if (textInput) {
            textInput.value = preset[key];
        }
    });
    
    // Önizlemeleri güncelle
    if (typeof window.updateAllPreviews === 'function') {
        window.updateAllPreviews();
    }
    
    // Seçili preset'i işaretle
    document.querySelectorAll('.preset-btn').forEach(function(btn) {
        btn.classList.remove('border-purple-500', 'ring-2', 'ring-purple-300');
    });
    
    if (buttonElement) {
        buttonElement.classList.add('border-purple-500', 'ring-2', 'ring-purple-300');
    }
    
    console.log('Preset applied successfully');
};

// Varsayılanlara dön - Global function
window.resetToDefaults = function() {
    console.log('resetToDefaults called');
    if (!confirm('Tüm tema ayarlarını varsayılan değerlere sıfırlamak istediğinize emin misiniz?')) {
        return;
    }
    
    // Default preset'i uygula (buttonElement olmadan)
    const preset = window.presets['default'];
    Object.keys(preset).forEach(function(key) {
        const colorInput = document.getElementById(key);
        const textInput = document.getElementById(key + '_text');
        
        if (colorInput) {
            colorInput.value = preset[key];
        }
        if (textInput) {
            textInput.value = preset[key];
        }
    });
    
    // Gradyan checkbox'ı işaretle
    const useGradientCheckbox = document.getElementById('use_gradient_checkbox');
    if (useGradientCheckbox) {
        useGradientCheckbox.checked = true;
        if (typeof window.toggleGradientSettings === 'function') {
            window.toggleGradientSettings(true);
        }
    }
    
    // Gradyan yönünü sıfırla
    if (typeof window.setGradientDirection === 'function') {
        window.setGradientDirection('to right');
    }
    
    // Önizlemeleri güncelle
    if (typeof window.updateAllPreviews === 'function') {
        window.updateAllPreviews();
    }
    
    // Seçili preset'i işaretle
    document.querySelectorAll('.preset-btn').forEach(function(btn) {
        btn.classList.remove('border-purple-500', 'ring-2', 'ring-purple-300');
    });
    
    console.log('Reset to defaults completed');
};
</script>

@push('scripts')
<script>

// Gradyan yönü seç - Global scope
window.setGradientDirection = function(direction) {
    document.getElementById('gradient_direction').value = direction;
    
    // Seçili yönü işaretle
    document.querySelectorAll('.gradient-dir-btn').forEach(function(btn) {
        if (btn.dataset.direction === direction) {
            btn.classList.add('border-purple-500', 'bg-purple-50');
        } else {
            btn.classList.remove('border-purple-500', 'bg-purple-50');
        }
    });
    
    window.updateAllPreviews();
};

// Gradyan ayarlarını göster/gizle - Global scope
window.toggleGradientSettings = function(enabled) {
    const gradientSettings = document.getElementById('gradientSettings');
    if (gradientSettings) {
        gradientSettings.style.opacity = enabled ? '1' : '0.5';
        gradientSettings.style.pointerEvents = enabled ? 'auto' : 'none';
    }
};

// Tüm önizlemeleri güncelle - Global scope
window.updateAllPreviews = function() {
    const useGradientEl = document.getElementById('use_gradient_checkbox');
    const useGradient = useGradientEl ? useGradientEl.checked : true;
    const primaryColor = document.getElementById('primary_color')?.value || '#085952';
    const secondaryColor = document.getElementById('secondary_color')?.value || '#0a7b73';
    const gradientStart = document.getElementById('gradient_start')?.value || '#076961';
    const gradientEnd = document.getElementById('gradient_end')?.value || '#0a7b6e';
    const gradientDirection = document.getElementById('gradient_direction')?.value || 'to right';
    const hoverColor = document.getElementById('hover_color')?.value || '#0f766e';
    const buttonColor = document.getElementById('button_color')?.value || '#0d9488';
    const linkColor = document.getElementById('link_color')?.value || '#0d9488';
    
    const gradient = useGradient 
        ? 'linear-gradient(' + gradientDirection + ', ' + gradientStart + ', ' + gradientEnd + ')'
        : primaryColor;
    
    // Gradyan önizleme
    const gradientPreview = document.getElementById('gradientPreview');
    if (gradientPreview) {
        gradientPreview.style.background = 'linear-gradient(' + gradientDirection + ', ' + gradientStart + ', ' + gradientEnd + ')';
    }
    
    // Header önizleme
    const previewHeader = document.getElementById('preview-header');
    if (previewHeader) {
        previewHeader.style.background = gradient;
    }
    
    // Buton önizlemeleri
    const previewBtnPrimary = document.getElementById('preview-btn-primary');
    if (previewBtnPrimary) {
        previewBtnPrimary.style.background = gradient;
        previewBtnPrimary.onmouseenter = function() { this.style.background = hoverColor; };
        previewBtnPrimary.onmouseleave = function() { this.style.background = gradient; };
    }
    
    const previewBtnSecondary = document.getElementById('preview-btn-secondary');
    if (previewBtnSecondary) {
        previewBtnSecondary.style.backgroundColor = secondaryColor;
        previewBtnSecondary.onmouseenter = function() { this.style.backgroundColor = hoverColor; };
        previewBtnSecondary.onmouseleave = function() { this.style.backgroundColor = secondaryColor; };
    }
    
    // Link önizlemeleri
    var linkIds = ['preview-link', 'preview-link-2'];
    linkIds.forEach(function(id) {
        var link = document.getElementById(id);
        if (link) {
            link.style.color = linkColor;
            link.onmouseenter = function() { this.style.color = hoverColor; };
            link.onmouseleave = function() { this.style.color = linkColor; };
        }
    });
    
    // Kart header önizleme
    const previewCardHeader = document.getElementById('preview-card-header');
    if (previewCardHeader) {
        previewCardHeader.style.background = gradient;
    }
    
    // Footer önizleme
    const previewFooter = document.getElementById('preview-footer');
    if (previewFooter) {
        previewFooter.style.background = 'linear-gradient(135deg, #1e293b 0%, ' + primaryColor + ' 50%, #1e293b 100%)';
    }
    
    // İkon önizlemeleri
    const previewIcon = document.getElementById('preview-icon');
    if (previewIcon) {
        previewIcon.style.color = linkColor;
    }
    
    // İkon arka planları
    const iconBgs = document.querySelectorAll('[id^="preview-icon-bg"], .preview-icon-container');
    iconBgs.forEach(function(bg) {
        bg.style.backgroundColor = window.hexToRgba(linkColor, 0.1);
    });
    
    // Tüm ikonları güncelle
    document.querySelectorAll('#preview-footer ~ div i, .preview-icons i').forEach(function(icon) {
        icon.style.color = linkColor;
    });
};

// Hex to RGBA - Global scope
window.hexToRgba = function(hex, alpha) {
    const r = parseInt(hex.slice(1, 3), 16);
    const g = parseInt(hex.slice(3, 5), 16);
    const b = parseInt(hex.slice(5, 7), 16);
    return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
};

// Renk input senkronizasyonu
function syncColorInputs() {
    const colorInputs = ['primary_color', 'secondary_color', 'hover_color', 'button_color', 'link_color', 'gradient_start', 'gradient_end'];
    
    colorInputs.forEach(function(name) {
        const colorInput = document.getElementById(name);
        const textInput = document.getElementById(name + '_text');
        
        if (colorInput && textInput) {
            colorInput.addEventListener('input', function() {
                textInput.value = this.value.toUpperCase();
                window.updateAllPreviews();
            });
            
            textInput.addEventListener('input', function() {
                if (/^#[0-9A-Fa-f]{6}$/i.test(this.value)) {
                    colorInput.value = this.value;
                    window.updateAllPreviews();
                }
            });
            
            textInput.addEventListener('blur', function() {
                this.value = this.value.toUpperCase();
            });
        }
    });
}

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    console.log('Theme settings DOM loaded');
    syncColorInputs();
    
    // Gradyan checkbox
    const useGradientCheckbox = document.getElementById('use_gradient_checkbox');
    if (useGradientCheckbox) {
        window.toggleGradientSettings(useGradientCheckbox.checked);
        useGradientCheckbox.addEventListener('change', function() {
            window.toggleGradientSettings(this.checked);
            window.updateAllPreviews();
        });
    }
    
    // Mevcut gradyan yönünü işaretle
    const gradientDirEl = document.getElementById('gradient_direction');
    const currentDirection = gradientDirEl ? gradientDirEl.value : 'to right';
    document.querySelectorAll('.gradient-dir-btn').forEach(function(btn) {
        if (btn.dataset.direction === currentDirection) {
            btn.classList.add('border-purple-500', 'bg-purple-50');
        }
    });
    
    // İlk önizlemeyi güncelle
    window.updateAllPreviews();
    
    // Form submit
    var themeForm = document.getElementById('themeForm');
    if (themeForm) {
        themeForm.addEventListener('submit', function(e) {
            const colorInputs = ['primary_color', 'secondary_color', 'hover_color', 'button_color', 'link_color', 'gradient_start', 'gradient_end'];
            colorInputs.forEach(function(name) {
                const textInput = document.getElementById(name + '_text');
                const colorInput = document.getElementById(name);
                if (textInput && colorInput && /^#[0-9A-Fa-f]{6}$/i.test(textInput.value)) {
                    colorInput.value = textInput.value;
                }
            });
        });
    }
    
    console.log('Theme settings initialized successfully');
});
</script>
@endpush
@endsection
