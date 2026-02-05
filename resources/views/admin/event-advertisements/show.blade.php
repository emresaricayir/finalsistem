@extends('admin.layouts.app')

@section('title', 'Etkinlik Reklamı Detayı')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-eye mr-3"></i>
                        Etkinlik Reklamı Detayı
                    </h3>
                    <div class="flex items-center space-x-2">
                        @if($eventAdvertisement->is_active)
                            <span class="bg-green-500 text-white text-sm px-3 py-1 rounded-full">Aktif</span>
                        @else
                            <span class="bg-gray-500 text-white text-sm px-3 py-1 rounded-full">Pasif</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Reklam Adı</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $eventAdvertisement->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Sıralama</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $eventAdvertisement->sort_order }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Başlık</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $eventAdvertisement->title }}</p>
                </div>

                @if($eventAdvertisement->content)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500 mb-2">İçerik</label>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900 leading-relaxed">{{ $eventAdvertisement->content }}</p>
                    </div>
                </div>
                @endif

                @if($eventAdvertisement->image)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500 mb-2">Resim</label>
                    <img src="{{ asset($eventAdvertisement->image) }}" alt="Reklam resmi" class="w-64 h-64 object-cover rounded-lg border">
                </div>
                @endif

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Alt Yazı</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $eventAdvertisement->footer_text }}</p>
                </div>

                @if($eventAdvertisement->display_positions && count($eventAdvertisement->display_positions) > 0)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500 mb-3">Gösterilecek Pozisyonlar</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($eventAdvertisement->display_positions as $position)
                            <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">{{ $position }}. Etkinlik Sonrası</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('admin.event-advertisements.edit', $eventAdvertisement) }}"
                       class="bg-gradient-to-r from-green-600 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-green-700 hover:to-blue-700 transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>
                        Düzenle
                    </a>

                    <a href="{{ route('admin.event-advertisements.index') }}"
                       class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Geri Dön
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
