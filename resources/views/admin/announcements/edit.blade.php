@extends('admin.layouts.app')

@section('title', 'Duyuru Düzenle')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-edit mr-2 text-blue-500"></i>
                Duyuru Düzenle
            </h1>
            <p class="mt-2 text-gray-600">Mevcut duyuruyu düzenleyin.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-3">
            <a href="{{ route('admin.announcements.show', $announcement) }}" class="btn-secondary px-6 py-3 rounded-xl font-medium">
                <i class="fas fa-eye mr-2"></i>Görüntüle
            </a>
            <a href="{{ route('admin.announcements.index') }}" class="btn-secondary px-6 py-3 rounded-xl font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Geri Dön
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3 mt-0.5"></i>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-red-800 mb-2">Lütfen aşağıdaki hataları düzeltin:</h3>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Title - Türkçe ve Almanca -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Türkçe Başlık -->
                        <div>
                            <label for="title_tr" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-heading mr-2 text-blue-500"></i>
                                Türkçe Başlık *
                            </label>
                            <input type="text" name="title_tr" id="title_tr" value="{{ old('title_tr', $announcement->getRawOriginal('title_tr')) }}" required
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title_tr') border-red-500 @enderror"
                                   placeholder="Türkçe duyuru başlığını girin...">
                            @error('title_tr')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Almanca Başlık -->
                        <div>
                            <label for="title_de" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-heading mr-2 text-green-500"></i>
                                Almanca Başlık
                            </label>
                            <input type="text" name="title_de" id="title_de" value="{{ old('title_de', $announcement->getRawOriginal('title_de')) }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('title_de') border-red-500 @enderror"
                                   placeholder="Almanca duyuru başlığını girin...">
                            @error('title_de')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Content - Türkçe ve Almanca -->
                    <div class="space-y-4">
                        <!-- Türkçe İçerik -->
                        <div>
                            <label for="content_tr" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-2 text-blue-500"></i>
                                Türkçe İçerik
                            </label>
                            <textarea name="content_tr" id="content_tr" rows="8"
                                  class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('content_tr') border-red-500 @enderror"
                                  placeholder="Türkçe duyuru içeriğini yazın... (İsteğe bağlı)">{{ old('content_tr', $announcement->getRawOriginal('content_tr')) }}</textarea>
                            @error('content_tr')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Almanca İçerik -->
                        <div>
                            <label for="content_de" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-2 text-green-500"></i>
                                Almanca İçerik
                            </label>
                            <textarea name="content_de" id="content_de" rows="8"
                                  class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('content_de') border-red-500 @enderror"
                                  placeholder="Almanca duyuru içeriğini yazın... (İsteğe bağlı)">{{ old('content_de', $announcement->getRawOriginal('content_de')) }}</textarea>
                            @error('content_de')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Image Upload -->
                    <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                        <!-- Türkçe Kapak Görseli -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-image mr-2 text-purple-500"></i>
                                Kapak Görseli (Türkçe)
                            </label>
                            <div class="rounded-xl overflow-hidden border border-gray-200 bg-white">
                                <div class="aspect-video bg-gray-100 relative">
                                    @if($announcement->getOriginal('image_path'))
                                        <img id="imagePreview" src="{{ asset('storage/' . $announcement->getOriginal('image_path')) }}" alt="Mevcut görsel (Türkçe)" class="absolute inset-0 w-full h-full object-cover">
                                    @else
                                        <img id="imagePreview" src="" alt="Önizleme" class="hidden absolute inset-0 w-full h-full object-cover">
                                    @endif
                                    <div id="imagePlaceholder" class="{{ $announcement->getOriginal('image_path') ? 'hidden' : '' }} absolute inset-0 flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image text-4xl"></i>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <input type="file" name="image" id="image" accept="image/*" class="w-full text-sm" onchange="previewAnnouncementImage(event, 'imagePreview', 'imagePlaceholder')">
                                    <p class="text-xs text-gray-500 mt-2">Türkçe sayfalar için kapak görseli. Önerilen oran 16:9. Maksimum 4MB.</p>
                                    @if($announcement->getOriginal('image_path'))
                                        <button type="button" onclick="removeAnnouncementImage('{{ route('admin.announcements.remove-image', $announcement) }}', 'Türkçe')" class="mt-2 inline-flex items-center px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-sm font-medium">
                                            <i class="fas fa-trash mr-2"></i>Görseli Sil
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Almanca Kapak Görseli -->
                        <div>
                            <label for="image_de" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-image mr-2 text-green-500"></i>
                                Kapak Görseli (Almanca) <span class="text-xs text-gray-500">(İsteğe bağlı)</span>
                            </label>
                            <div class="rounded-xl overflow-hidden border border-gray-200 bg-white">
                                <div class="aspect-video bg-gray-100 relative">
                                    @if($announcement->image_path_de)
                                        <img id="imageDePreview" src="{{ asset('storage/' . $announcement->image_path_de) }}" alt="Mevcut görsel (Almanca)" class="absolute inset-0 w-full h-full object-cover">
                                    @else
                                        <img id="imageDePreview" src="" alt="Önizleme" class="hidden absolute inset-0 w-full h-full object-cover">
                                    @endif
                                    <div id="imageDePlaceholder" class="{{ $announcement->image_path_de ? 'hidden' : '' }} absolute inset-0 flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image text-4xl"></i>
                                        @if(!$announcement->image_path_de)
                                            <div class="text-center text-xs text-gray-500 mt-12">
                                                <p>Almanca görsel yok</p>
                                                <p class="mt-1">Türkçe görsel kullanılıyor</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-3">
                                    <input type="file" name="image_de" id="image_de" accept="image/*" class="w-full text-sm" onchange="previewAnnouncementImage(event, 'imageDePreview', 'imageDePlaceholder')">
                                    <p class="text-xs text-gray-500 mt-2">Almanca sayfalar için kapak görseli. Boş bırakılırsa Türkçe görsel kullanılır. Önerilen oran 16:9. Maksimum 4MB.</p>
                                    @if($announcement->image_path_de)
                                        <button type="button" onclick="removeAnnouncementImage('{{ route('admin.announcements.remove-image-de', $announcement) }}', 'Almanca')" class="mt-2 inline-flex items-center px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-sm font-medium">
                                            <i class="fas fa-trash mr-2"></i>Görseli Sil
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @error('image_de')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                    <!-- Status Options -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-cog mr-2 text-blue-500"></i>
                            Durum Ayarları
                        </h3>

                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="is_active" class="ml-2 text-sm text-gray-700">
                                    Aktif (Yayında)
                                </label>
                            </div>

                        </div>
                    </div>

                    <!-- Date Range -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-calendar mr-2 text-green-500"></i>
                            Yayın Tarihleri
                        </h3>

                        <div class="space-y-3">
                            <div>
                                <label for="start_date" class="block text-xs font-medium text-gray-600 mb-1">Başlangıç Tarihi</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $announcement->start_date ? $announcement->start_date->format('Y-m-d') : '') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_date') border-red-500 @enderror">
                                @error('start_date')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-xs font-medium text-gray-600 mb-1">Bitiş Tarihi</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $announcement->end_date ? $announcement->end_date->format('Y-m-d') : '') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_date') border-red-500 @enderror">
                                @error('end_date')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 mt-2">
                            Boş bırakılırsa sürekli yayında kalır.
                        </p>
                    </div>

                    <!-- Sort Order -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sort-numeric-down mr-2 text-orange-500"></i>
                            Sıralama
                        </label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $announcement->sort_order) }}" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sort_order') border-red-500 @enderror"
                               placeholder="0">
                        <p class="text-xs text-gray-500 mt-1">
                            Küçük sayılar önce gösterilir.
                        </p>
                        @error('sort_order')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.announcements.index') }}" class="btn-secondary px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-times mr-2"></i>İptal
                </a>
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-save mr-2"></i>Duyuru Güncelle
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewAnnouncementImage(e, previewId = 'imagePreview', placeholderId = 'imagePlaceholder'){
        const file = e.target.files && e.target.files[0];
        const img = document.getElementById(previewId);
        const ph = document.getElementById(placeholderId);
        if(!file){
            @if(!$announcement->getOriginal('image_path'))
                if(previewId === 'imagePreview') {
                    img.classList.add('hidden');
                    ph.classList.remove('hidden');
                }
            @endif
            @if(!$announcement->image_path_de)
                if(previewId === 'imageDePreview') {
                    img.classList.add('hidden');
                    ph.classList.remove('hidden');
                }
            @endif
            return;
        }
        const url = URL.createObjectURL(file);
        img.src = url;
        img.onload = () => URL.revokeObjectURL(url);
        img.classList.remove('hidden');
        ph.classList.add('hidden');
    }

    // Remove image via fetch DELETE
    function removeAnnouncementImage(url, imageType = 'Kapak görseli') {
        if (!confirm(imageType + ' kapak görselini silmek istediğinize emin misiniz?')) return;
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        }).then(() => {
            window.location.reload();
        }).catch(error => {
            console.error('Error:', error);
            alert('Görsel silinirken bir hata oluştu.');
        });
    }
</script>
@endsection
