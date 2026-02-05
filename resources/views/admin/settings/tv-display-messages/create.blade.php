@extends('admin.layouts.app')

@section('title', 'Yeni Reklam')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto"></div>
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4">
                <h3 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-plus mr-3"></i>
                    Yeni Reklam
                </h3>
            </div>

            <div class="p-6">
                <form action="{{ route('admin.settings.tv-display-messages.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf


                    <div class="mt-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('title') border-red-500 @enderror"
                               id="title" name="title" value="{{ old('title') }}" maxlength="80" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            <span class="text-red-600 font-semibold">Maksimum 80 karakter girebilirsiniz.</span>
                        </p>
                        <div class="mt-1 text-sm text-gray-400">
                            <span id="title-counter">0</span>/80 karakter
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Ana İçerik</label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('content') border-red-500 @enderror"
                                  id="content" name="content" rows="6" maxlength="300" placeholder="Mesaj içeriğini buraya yazın... (Maksimum 300 karakter)">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            <span class="text-red-600 font-semibold">Maksimum 300 karakter girebilirsiniz.</span>
                            TV ekranında scroll olmayacağı için metin uzunluğu sınırlıdır.
                        </p>
                        <div class="mt-1 text-sm text-gray-400">
                            <span id="content-counter">0</span>/300 karakter
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Resim (Opsiyonel)</label>
                        <input type="file" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('image') border-red-500 @enderror"
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">JPEG, PNG, JPG, GIF formatları desteklenir. Maksimum 5MB.</p>
                    </div>

                    <div class="mt-6">
                        <label for="footer_text" class="block text-sm font-medium text-gray-700 mb-2">Alt Yazı</label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('footer_text') border-red-500 @enderror"
                               id="footer_text" name="footer_text" value="{{ old('footer_text', 'YÖNETİM KURULU') }}">
                        @error('footer_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Gösterilecek Sayfalar</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                            @foreach($availablePages as $page)
                                <div class="flex items-center">
                                    @if(in_array($page, $occupiedPages))
                                        <!-- Dolu sayfa - devre dışı -->
                                        <input type="checkbox"
                                               class="h-4 w-4 text-gray-400 border-gray-300 rounded cursor-not-allowed"
                                               id="page_{{ $page }}"
                                               disabled>
                                        <label for="page_{{ $page }}" class="ml-2 text-sm text-gray-400 cursor-not-allowed">
                                            {{ $page }}. Sayfa (Dolu)
                                        </label>
                                    @else
                                        <!-- Boş sayfa - seçilebilir -->
                                        <input type="checkbox"
                                               class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                                               id="page_{{ $page }}"
                                               name="display_pages[]"
                                               value="{{ $page }}"
                                               {{ in_array($page, old('display_pages', [])) ? 'checked' : '' }}>
                                        <label for="page_{{ $page }}" class="ml-2 text-sm text-gray-700">
                                            {{ $page }}. Sayfa
                                        </label>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-2 text-sm text-red-600">
                            <span class="font-medium">Dolu sayfalar seçilemez.</span> Reklam ekranı seçilen sayfa sonrasında sadece bir kez gösterilecektir.
                            <span class="font-medium">Örnek: 3. sayfa seçilirse, sadece 4. sayfada reklam gösterilir, sonra üyeler devam eder.</span>
                        </p>
                    </div>

                    <div class="mt-6">
                        <div class="flex items-center">
                            <input type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                                   id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Reklamı aktif et
                            </label>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <button type="submit" class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i>
                            Mesajı Kaydet
                        </button>

                        <a href="{{ route('admin.settings.tv-display-messages.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Geri Dön
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Başlık karakter sayacı
    const titleInput = document.getElementById('title');
    const titleCounter = document.getElementById('title-counter');

    titleInput.addEventListener('input', function() {
        titleCounter.textContent = this.value.length;
    });

    // İçerik karakter sayacı
    const contentInput = document.getElementById('content');
    const contentCounter = document.getElementById('content-counter');

    contentInput.addEventListener('input', function() {
        contentCounter.textContent = this.value.length;
    });

    // Sayfa yüklendiğinde mevcut değerleri say
    titleCounter.textContent = titleInput.value.length;
    contentCounter.textContent = contentInput.value.length;
});
</script>
@endsection
