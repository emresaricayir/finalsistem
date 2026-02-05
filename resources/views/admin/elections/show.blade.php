@extends('admin.layouts.app')

@section('title', $election->title)

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('admin.elections.index') }}" class="hover:text-gray-700">Yazı Yönetimi</a>
                <span>/</span>
                <span>{{ $election->title_tr }}</span>
            </div>
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $election->title_tr }}</h1>
                    @if($election->title_de)
                        <h2 class="text-xl text-gray-700 mt-1">{{ $election->title_de }}</h2>
                    @endif
                    <p class="text-gray-600">Oluşturulma: {{ $election->created_at->format('d.m.Y H:i') }}</p>
                    <div class="mt-2">
                        @if($election->is_active)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                Pasif
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.elections.edit', $election) }}"
                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Düzenle
                    </a>
                </div>
            </div>
        </div>

        <!-- PDF Actions -->
        <div class="bg-white shadow-lg rounded-lg mb-6 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">PDF Oluşturma</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Türkçe PDF -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Türkçe Davetiye</h4>
                    <p class="text-sm text-gray-600 mb-3">Tüm aktif üyeler için Türkçe seçim davetiyesi oluştur</p>
                    <form action="{{ route('admin.elections.generate-bulk-pdf', $election) }}" method="POST">
                        @csrf
                        <input type="hidden" name="language" value="tr">
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Sayfa Başına (per_page)</label>
                                <input type="number" name="per_page" value="200" min="25" max="500" class="w-full border-gray-300 rounded px-2 py-1 text-sm" />
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Parti (batch)</label>
                                <input type="number" name="batch" value="1" min="1" class="w-full border-gray-300 rounded px-2 py-1 text-sm" />
                            </div>
                        </div>
                        <button type="submit"
                                class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-pdf mr-2"></i>
                            Türkçe PDF İndir (ZIP)
                        </button>
                    </form>
                </div>

                <!-- Almanca PDF -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Almanca Davetiye</h4>
                    <p class="text-sm text-gray-600 mb-3">Tüm aktif üyeler için Almanca seçim davetiyesi oluştur</p>
                    <form action="{{ route('admin.elections.generate-bulk-pdf', $election) }}" method="POST">
                        @csrf
                        <input type="hidden" name="language" value="de">
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Sayfa Başına (per_page)</label>
                                <input type="number" name="per_page" value="200" min="25" max="500" class="w-full border-gray-300 rounded px-2 py-1 text-sm" />
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Parti (batch)</label>
                                <input type="number" name="batch" value="1" min="1" class="w-full border-gray-300 rounded px-2 py-1 text-sm" />
                            </div>
                        </div>
                        <button type="submit"
                                class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-pdf mr-2"></i>
                            Almanca PDF İndir (ZIP)
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Content Preview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Türkçe İçerik -->
            <div class="bg-white shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Türkçe İçerik</h3>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none">
                        {!! nl2br(e($election->content_tr)) !!}
                    </div>
                </div>
            </div>

            <!-- Almanca İçerik -->
            <div class="bg-white shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Almanca İçerik</h3>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none">
                        {!! nl2br(e($election->content_de)) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
            <div class="flex">
                <i class="fas fa-info-circle text-blue-400 mt-0.5 mr-3"></i>
                <div>
                    <h4 class="text-sm font-medium text-blue-800">PDF Oluşturma Hakkında</h4>
                    <p class="text-sm text-blue-700 mt-1">
                        PDF oluşturduğunuzda, her üye için ayrı bir PDF dosyası oluşturulur.
                        Her PDF'de üyenin adı, soyadı ve adresi ile birlikte seçim davetiye metni yer alır.
                        Tüm PDF'ler ZIP dosyası olarak indirilir.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

