@extends('admin.layouts.app')

@section('title', 'Yeni Etkinlik Oluştur')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Yeni Etkinlik Oluştur</h1>
            <p class="mt-1 text-sm text-gray-600">Topluluk etkinliği için yeni bir kayıt oluşturun</p>
        </div>
        <a href="{{ route('admin.events.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Geri Dön
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Etkinlik Bilgileri</h3>
            </div>

            <div class="px-6 py-4 space-y-6">
                <!-- Title - Türkçe ve Almanca -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Türkçe Başlık -->
                    <div>
                        <label for="title_tr" class="block text-sm font-medium text-gray-700 mb-2">
                            Türkçe Başlık <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="title_tr"
                               id="title_tr"
                               value="{{ old('title_tr') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('title_tr') border-red-500 @enderror"
                               placeholder="Örn: Ahmet ve Ayşe'nin Düğünü"
                               required>
                        @error('title_tr')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Almanca Başlık -->
                    <div>
                        <label for="title_de" class="block text-sm font-medium text-gray-700 mb-2">
                            Almanca Başlık
                        </label>
                        <input type="text"
                               name="title_de"
                               id="title_de"
                               value="{{ old('title_de') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('title_de') border-red-500 @enderror"
                               placeholder="z.B. Ahmet und Ayşes Hochzeit">
                        @error('title_de')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description - Türkçe ve Almanca -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Türkçe Açıklama -->
                    <div>
                        <label for="description_tr" class="block text-sm font-medium text-gray-700 mb-2">
                            Türkçe Açıklama
                        </label>
                        <textarea name="description_tr"
                                  id="description_tr"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description_tr') border-red-500 @enderror"
                                  placeholder="Etkinlik hakkında detaylı bilgi...">{{ old('description_tr') }}</textarea>
                        @error('description_tr')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Almanca Açıklama -->
                    <div>
                        <label for="description_de" class="block text-sm font-medium text-gray-700 mb-2">
                            Almanca Açıklama
                        </label>
                        <textarea name="description_de"
                                  id="description_de"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description_de') border-red-500 @enderror"
                                  placeholder="Detaillierte Informationen zur Veranstaltung...">{{ old('description_de') }}</textarea>
                        @error('description_de')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Event Date -->
                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tarih ve Saat <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local"
                           name="event_date"
                           id="event_date"
                           value="{{ old('event_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('event_date') border-red-500 @enderror"
                           required>
                    @error('event_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Etkinlik Görseli (Sadece Frontend için)
                    </label>
                    <input type="file"
                           name="image"
                           id="image"
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('image') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">JPEG, PNG, JPG, GIF, WEBP (Max: 6MB). Bu görsel sadece ana sayfada gösterilir, TV yansıtma sayfasında görünmez.</p>
                    <p class="mt-1 text-xs font-medium text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Önerilen görsel boyutu: 800x600px veya 16:9 oranında. Lütfen bu boyutlarda olmasına dikkat ediniz.
                    </p>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        Konum
                    </label>
                    <input type="text"
                           name="location"
                           id="location"
                           value="{{ old('location') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('location') border-red-500 @enderror"
                           placeholder="Örn: Cami Konferans Salonu">
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Display Settings -->
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Görüntüleme Ayarları</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="display_duration" class="block text-sm font-medium text-gray-700 mb-2">
                                Ekranda Gösterim Süresi (saniye) <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   name="display_duration"
                                   id="display_duration"
                                   value="{{ old('display_duration', 5) }}"
                                   min="1"
                                   max="60"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('display_duration') border-red-500 @enderror"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">Her etkinlik ekranda kaç saniye gösterilecek</p>
                            @error('display_duration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <div class="flex items-center">
                                <input type="checkbox"
                                       name="is_active"
                                       id="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Etkinliği aktif olarak işaretle
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.events.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                    İptal
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Etkinliği Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // URL'de success parametresi var mı kontrol et
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === '1') {
        // Display sayfasını yenile
        const displayWindow = window.open('{{ route("events.display") }}', '_blank');
        if (displayWindow) {
            displayWindow.location.reload();
        }

        // URL'den success parametresini kaldır
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }

    // Form submit işlemini dinle
    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        // Form submit edildiğinde success parametresi ekle
        const formAction = form.action;
        if (formAction.includes('?')) {
            form.action = formAction + '&success=1';
        } else {
            form.action = formAction + '?success=1';
        }
    });
});
</script>
@endsection
