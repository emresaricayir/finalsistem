@extends('admin.layouts.app')

@section('title', 'TV Yansıtma Ayarları')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-tv mr-3 text-purple-600"></i>
                        TV Yansıtma Ayarları
                    </h1>
                    <p class="text-gray-600 mt-1">TV ekranı görüntüleme ayarlarını yönetin</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.tv-display.index') }}" target="_blank"
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                        <i class="fas fa-eye mr-2"></i>
                        TV Ekranını Görüntüle
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Settings Form -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4">
                <h3 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-cog mr-3"></i>
                    Ayarlar
                </h3>
            </div>

            <form action="{{ route('admin.settings.tv-display-settings.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Sayfa Geçiş Hızı -->
                    <div class="space-y-2">
                        <label for="page_transition_speed" class="block text-sm font-medium text-gray-700">
                            Sayfa Geçiş Hızı (Saniye)
                        </label>
                        <div class="relative">
                            <input type="number"
                                   id="page_transition_speed"
                                   name="page_transition_speed"
                                   value="{{ old('page_transition_speed', $settings->page_transition_speed) }}"
                                   min="1"
                                   max="60"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('page_transition_speed') border-red-500 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">sn</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500">TV ekranında sayfalar arası geçiş süresi (1-60 saniye)</p>
                        @error('page_transition_speed')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Otomatik Yenileme Aralığı -->
                    <div class="space-y-2">
                        <label for="auto_refresh_interval" class="block text-sm font-medium text-gray-700">
                            Otomatik Yenileme Aralığı (Saniye)
                        </label>
                        <div class="relative">
                            <input type="number"
                                   id="auto_refresh_interval"
                                   name="auto_refresh_interval"
                                   value="{{ old('auto_refresh_interval', $settings->auto_refresh_interval) }}"
                                   min="5"
                                   max="300"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('auto_refresh_interval') border-red-500 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">sn</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500">Sayfa otomatik yenileme süresi (5-300 saniye)</p>
                        @error('auto_refresh_interval')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sayfa Başına Üye Sayısı -->
                    <div class="space-y-2">
                        <label for="member_display_limit" class="block text-sm font-medium text-gray-700">
                            Sayfa Başına Üye Sayısı
                        </label>
                        <div class="relative">
                            <input type="number"
                                   id="member_display_limit"
                                   name="member_display_limit"
                                   value="{{ old('member_display_limit', $settings->member_display_limit) }}"
                                   min="4"
                                   max="32"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('member_display_limit') border-red-500 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">üye</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500">Her sayfada gösterilecek üye sayısı (4-32 üye)</p>
                        @error('member_display_limit')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Varsayılan Yıl -->
                    <div class="space-y-2">
                        <label for="default_year" class="block text-sm font-medium text-gray-700">
                            Varsayılan Yıl
                        </label>
                        <div class="relative">
                            <select id="default_year"
                                    name="default_year"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('default_year') border-red-500 @enderror">
                                @for($year = 2026; $year <= 2030; $year++)
                                    <option value="{{ $year }}" {{ old('default_year', $settings->default_year ?? 2026) == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <p class="text-xs text-gray-500">TV ekranında varsayılan olarak gösterilecek yıl</p>
                        @error('default_year')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Checkbox Ayarları -->
                <div class="mt-8 space-y-6">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Görüntüleme Seçenekleri</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Aidatları Göster -->
                        <div class="flex items-center space-x-3">
                            <input type="checkbox"
                                   id="show_dues"
                                   name="show_dues"
                                   value="1"
                                   {{ old('show_dues', $settings->show_dues) ? 'checked' : '' }}
                                   class="w-5 h-5 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                            <label for="show_dues" class="text-sm font-medium text-gray-700">
                                Aidatları Göster
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 md:col-span-2">Üyelerin aylık aidat ödeme durumlarını göster</p>

                        <!-- Otomatik Yenileme -->
                        <div class="flex items-center space-x-3">
                            <input type="checkbox"
                                   id="auto_refresh_enabled"
                                   name="auto_refresh_enabled"
                                   value="1"
                                   {{ old('auto_refresh_enabled', $settings->auto_refresh_enabled) ? 'checked' : '' }}
                                   class="w-5 h-5 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                            <label for="auto_refresh_enabled" class="text-sm font-medium text-gray-700">
                                Otomatik Yenileme
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 md:col-span-2">Sayfayı belirlenen aralıklarla otomatik yenile</p>

                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex justify-between items-center">
                    <div class="flex space-x-3">
                        <button type="submit"
                                class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200 flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Ayarları Kaydet
                        </button>

                        <a href="{{ route('admin.settings.tv-display-settings.reset') }}"
                           onclick="return confirm('Ayarları varsayılan değerlere sıfırlamak istediğinizden emin misiniz?')"
                           class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors flex items-center">
                            <i class="fas fa-undo mr-2"></i>
                            Varsayılana Sıfırla
                        </a>
                    </div>

                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Değişiklikler TV ekranında anında etkili olur
                    </div>
                </div>
            </form>
        </div>

        <!-- Mevcut Ayarlar Özeti -->
        <div class="mt-8 bg-gray-50 rounded-xl p-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                Mevcut Ayarlar
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="text-sm text-gray-500">Sayfa Geçiş Hızı</div>
                    <div class="text-xl font-bold text-purple-600">{{ $settings->page_transition_speed }} saniye</div>
                </div>
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="text-sm text-gray-500">Aidat Gösterimi</div>
                    <div class="text-xl font-bold {{ $settings->show_dues ? 'text-green-600' : 'text-red-600' }}">
                        {{ $settings->show_dues ? 'Aktif' : 'Pasif' }}
                    </div>
                </div>
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="text-sm text-gray-500">Otomatik Yenileme</div>
                    <div class="text-xl font-bold {{ $settings->auto_refresh_enabled ? 'text-green-600' : 'text-red-600' }}">
                        {{ $settings->auto_refresh_enabled ? 'Aktif' : 'Pasif' }}
                    </div>
                </div>
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="text-sm text-gray-500">Yenileme Aralığı</div>
                    <div class="text-xl font-bold text-blue-600">{{ $settings->auto_refresh_interval }} saniye</div>
                </div>
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="text-sm text-gray-500">Sayfa Başına Üye</div>
                    <div class="text-xl font-bold text-indigo-600">{{ $settings->member_display_limit }} üye</div>
                </div>
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="text-sm text-gray-500">Varsayılan Yıl</div>
                    <div class="text-xl font-bold text-orange-600">{{ $settings->default_year ?? 2026 }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
