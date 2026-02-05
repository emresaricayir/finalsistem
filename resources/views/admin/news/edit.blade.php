@extends('admin.layouts.app')

@section('title', 'Haber Düzenle')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-edit mr-2 text-blue-500"></i>
                Haber Düzenle
            </h1>
            <p class="mt-2 text-gray-600">Haber bilgilerini düzenleyin ve güncelleyin.</p>
        </div>
        <a href="{{ route('admin.news.index') }}"
           class="btn-secondary px-6 py-3 rounded-xl font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Geri Dön
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.news.update', $news) }}" method="POST" class="space-y-8" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form Fields -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- News Title - Türkçe ve Almanca -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Türkçe Başlık -->
                        <div>
                            <label for="title_tr" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-heading mr-2 text-blue-500"></i>
                                Türkçe Başlık <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="title_tr"
                                   id="title_tr"
                                   required
                                   value="{{ old('title_tr', $news->getRawOriginal('title_tr')) }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title_tr') border-red-500 @enderror"
                                   placeholder="Türkçe haber başlığını giriniz">
                            @error('title_tr')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Almanca Başlık -->
                        <div>
                            <label for="title_de" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-heading mr-2 text-green-500"></i>
                                Almanca Başlık
                            </label>
                            <input type="text"
                                   name="title_de"
                                   id="title_de"
                                   value="{{ old('title_de', $news->getRawOriginal('title_de')) }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('title_de') border-red-500 @enderror"
                                   placeholder="Almanca haber başlığını giriniz">
                            @error('title_de')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Cover Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image mr-2 text-purple-500"></i>
                            Kapak Görseli
                        </label>
                        @if($news->image_path)
                            <div class="mb-3">
                                <img src="{{ asset($news->image_path) }}" alt="Mevcut kapak görseli" class="h-32 w-auto rounded-lg shadow-sm border">
                                <p class="text-xs text-gray-500 mt-1">Mevcut kapak görseli</p>
                            </div>
                        @endif
                        <input type="file"
                               name="image"
                               id="image"
                               accept="image/*"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror">
                        @error('image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sidebar Fields -->
                <div class="space-y-6">
                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sort-numeric-up mr-2 text-indigo-500"></i>
                            Sıralama
                        </label>
                        <input type="number"
                               name="sort_order"
                               id="sort_order"
                               value="{{ old('sort_order', $news->sort_order) }}"
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
                                   {{ old('is_active', $news->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                            <span class="ml-2 text-sm font-medium text-gray-700">
                                <i class="fas fa-check-circle mr-1 text-green-500"></i>
                                Haber aktif olsun
                            </span>
                        </label>
                    </div>

                    <!-- Featured Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="is_featured"
                                   value="1"
                                   {{ old('is_featured', $news->is_featured) ? 'checked' : '' }}
                                   class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                            <span class="ml-2 text-sm font-medium text-gray-700">
                                <i class="fas fa-star mr-1 text-yellow-500"></i>
                                Öne çıkan haber
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Content Editor - Türkçe ve Almanca -->
            <div class="space-y-6">
                <!-- Türkçe İçerik -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-edit mr-2 text-blue-500"></i>
                        Türkçe İçerik
                    </label>
                    <div class="border border-gray-300 rounded-xl overflow-hidden">
                        <textarea name="content_tr" id="content_tr">{{ old('content_tr', $news->getRawOriginal('content_tr')) }}</textarea>
                    </div>
                    @error('content_tr')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Almanca İçerik -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-edit mr-2 text-green-500"></i>
                        Almanca İçerik
                    </label>
                    <div class="border border-gray-300 rounded-xl overflow-hidden">
                        <textarea name="content_de" id="content_de">{{ old('content_de', $news->getRawOriginal('content_de')) }}</textarea>
                    </div>
                    @error('content_de')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.news.index') }}" class="btn-secondary px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-times mr-2"></i>İptal
                </a>
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-save mr-2"></i>Haberi Güncelle
                </button>
            </div>
        </form>
    </div>

    <!-- Add New Photos Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-plus mr-2 text-green-500"></i>
            Yeni Fotoğraf Ekle
        </h2>

        <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Hidden fields to preserve existing data -->
            <input type="hidden" name="title_tr" value="{{ $news->getRawOriginal('title_tr') }}">
            <input type="hidden" name="title_de" value="{{ $news->getRawOriginal('title_de') }}">
            <input type="hidden" name="content_tr" value="{{ $news->getRawOriginal('content_tr') }}">
            <input type="hidden" name="content_de" value="{{ $news->getRawOriginal('content_de') }}">
            <input type="hidden" name="sort_order" value="{{ $news->sort_order }}">
            @if($news->is_active)
                <input type="hidden" name="is_active" value="1">
            @endif
            @if($news->is_featured)
                <input type="hidden" name="is_featured" value="1">
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-images mr-2 text-blue-500"></i>
                    Haber Fotoğrafları (İsteğe bağlı)
                </label>
                <input type="file"
                       name="photos[]"
                       accept="image/*"
                       multiple
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('photos') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Birden fazla fotoğraf seçebilirsiniz. Mevcut fotoğraflar korunacak ve yenileri eklenecektir.
                </p>
                @error('photos')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('photos.*')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-upload mr-2"></i>Fotoğrafları Ekle
                </button>
            </div>
        </form>
    </div>

    <!-- Existing Photos Section -->
    @if($news->photos && $news->photos->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">
                <i class="fas fa-images mr-2 text-indigo-500"></i>
                Mevcut Haber Fotoğrafları
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($news->photos as $photo)
                    <div class="relative group">
                        <img src="{{ asset($photo->image_path) }}"
                             alt="Haber fotoğrafı"
                             class="w-full h-32 object-cover rounded-lg shadow-sm">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                            <form action="{{ route('admin.news.photos.destroy', [$news, $photo]) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Bu fotoğrafı silmek istediğinizden emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-white hover:text-red-300">
                                    <i class="fas fa-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Henüz fotoğraf eklenmemiş.</p>
                @endforelse
            </div>
        </div>
    @endif
</div>

<!-- TinyMCE CDN -->
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.7.2/tinymce.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // TinyMCE base URL ayarı - dil dosyalarını doğru yüklemek için
    if (typeof tinymce !== 'undefined') {
        tinymce.baseURL = 'https://cdn.jsdelivr.net/npm/tinymce@6.7.2';
        tinymce.suffix = '.min';
    }
    
    // Initialize TinyMCE editor for Turkish content
    tinymce.init({
        selector: '#content_tr',
        height: 500,
        menubar: true,
        base_url: 'https://cdn.jsdelivr.net/npm/tinymce@6.7.2',
        suffix: '.min',
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons',
            'template', 'codesample', 'pagebreak', 'nonbreaking', 'quickbars'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic forecolor backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help | image media link | code | fullscreen | ' +
            'emoticons | table | searchreplace | wordcount | preview | ' +
            'imageeffects',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
        branding: false,
        promotion: false,
        license_key: 'gpl',
        images_upload_handler: function (blobInfo, progress) {
            return new Promise(function (resolve, reject) {
                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                const xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '/admin/upload-image');

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken.getAttribute('content'));
                }

                xhr.upload.onprogress = function (e) {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onload = function () {
                    if (xhr.status === 403) {
                        reject({ message: 'Bu işlem için yetkiniz yok.', remove: true });
                        return;
                    }

                    if (xhr.status < 200 || xhr.status >= 300) {
                        reject('HTTP Error: ' + xhr.statusText);
                        return;
                    }

                    const json = JSON.parse(xhr.responseText);

                    if (!json || typeof json.url != 'string') {
                        reject('Geçersiz JSON: ' + xhr.responseText);
                        return;
                    }

                    resolve({ location: json.url });
                };

                xhr.onerror = function () {
                    reject('Görsel yükleme hatası: Ağ hatası oluştu.');
                };

                xhr.send(formData);
            });
        },
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });

            // Özel resim efekt butonları
            editor.ui.registry.addButton('imageeffects', {
                text: '🖼️ Efektler',
                tooltip: 'Resim Efektleri',
                onAction: function () {
                    editor.windowManager.open({
                        title: 'Resim Efektleri',
                        body: {
                            type: 'panel',
                            items: [
                                {
                                    type: 'selectbox',
                                    name: 'shadow',
                                    label: 'Gölge Efekti',
                                    items: [
                                        { text: 'Varsayılan', value: '' },
                                        { text: 'Yumuşak Gölge', value: 'shadow-soft' },
                                        { text: 'Orta Gölge', value: 'shadow-medium' },
                                        { text: 'Güçlü Gölge', value: 'shadow-strong' }
                                    ]
                                },
                                {
                                    type: 'selectbox',
                                    name: 'border',
                                    label: 'Çerçeve',
                                    items: [
                                        { text: 'Çerçevesiz', value: '' },
                                        { text: 'İnce Çerçeve', value: 'border-thin' },
                                        { text: 'Orta Çerçeve', value: 'border-medium' },
                                        { text: 'Kalın Çerçeve', value: 'border-thick' }
                                    ]
                                },
                                {
                                    type: 'selectbox',
                                    name: 'rounded',
                                    label: 'Köşe Yuvarlaklığı',
                                    items: [
                                        { text: 'Varsayılan', value: '' },
                                        { text: 'Köşesiz', value: 'rounded-none' },
                                        { text: 'Küçük', value: 'rounded-small' },
                                        { text: 'Orta', value: 'rounded-medium' },
                                        { text: 'Büyük', value: 'rounded-large' },
                                        { text: 'Tam Yuvarlak', value: 'rounded-full' }
                                    ]
                                }
                            ]
                        },
                        buttons: [
                            {
                                type: 'submit',
                                text: 'Uygula',
                                primary: true
                            },
                            {
                                type: 'cancel',
                                text: 'İptal'
                            }
                        ],
                        onSubmit: function (api) {
                            const data = api.getData();
                            const selectedNode = editor.selection.getNode();

                            if (selectedNode.tagName === 'IMG') {
                                let className = selectedNode.className;

                                // Mevcut efektleri temizle
                                className = className.replace(/shadow-\w+|border-\w+|rounded-\w+/g, '');

                                // Yeni efektleri ekle
                                if (data.shadow) className += ' ' + data.shadow;
                                if (data.border) className += ' ' + data.border;
                                if (data.rounded) className += ' ' + data.rounded;

                                selectedNode.className = className.trim();
                                editor.nodeChanged();
                            }

                            api.close();
                        }
                    });
                }
            });
        }
    });

    // Initialize TinyMCE editor for German content
    tinymce.init({
        selector: '#content_de',
        height: 500,
        menubar: true,
        base_url: 'https://cdn.jsdelivr.net/npm/tinymce@6.7.2',
        suffix: '.min',
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons',
            'template', 'codesample', 'pagebreak', 'nonbreaking', 'quickbars'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic forecolor backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help | image media link | code | fullscreen | ' +
            'emoticons | table | searchreplace | wordcount | preview | ' +
            'imageeffects',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
        branding: false,
        promotion: false,
        license_key: 'gpl',
        images_upload_handler: function (blobInfo, progress) {
            return new Promise(function (resolve, reject) {
                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                const xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '/admin/upload-image');

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken.getAttribute('content'));
                }

                xhr.upload.onprogress = function (e) {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onload = function () {
                    if (xhr.status === 403) {
                        reject({ message: 'Bu işlem için yetkiniz yok.', remove: true });
                        return;
                    }

                    if (xhr.status < 200 || xhr.status >= 300) {
                        reject('HTTP Error: ' + xhr.statusText);
                        return;
                    }

                    const json = JSON.parse(xhr.responseText);

                    if (!json || typeof json.url != 'string') {
                        reject('Geçersiz JSON: ' + xhr.responseText);
                        return;
                    }

                    resolve({ location: json.url });
                };

                xhr.onerror = function () {
                    reject('Görsel yükleme hatası: Ağ hatası oluştu.');
                };

                xhr.send(formData);
            });
        },
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });
});
</script>
@endsection
