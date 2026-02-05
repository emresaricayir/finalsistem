@extends('admin.layouts.app')

@section('title', 'Etkinlik Detayı')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Etkinlik Detayı</h1>
            <p class="mt-1 text-sm text-gray-600">{{ $event->title }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.events.edit', $event) }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                <i class="fas fa-edit mr-2"></i>
                Düzenle
            </a>
            <a href="{{ route('admin.events.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Geri Dön
            </a>
        </div>
    </div>

    <!-- Event Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Event Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Event Card -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="text-4xl mr-4">{{ $event->event_type_icon }}</div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ $event->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $event->event_type_label }}</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 space-y-4">
                    @if($event->description)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Açıklama</h4>
                        <p class="text-sm text-gray-900">{{ $event->description }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-1">Tarih</h4>
                            <p class="text-sm text-gray-900">{{ $event->event_date->format('d.m.Y') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-1">Saat</h4>
                            <p class="text-sm text-gray-900">{{ $event->event_date->format('H:i') }}</p>
                        </div>
                    </div>

                    @if($event->location)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-1">Konum</h4>
                        <p class="text-sm text-gray-900">{{ $event->location }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-1">Durum</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $event->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $event->is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-1">Gösterim Süresi</h4>
                            <p class="text-sm text-gray-900">{{ $event->display_duration }} saniye</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Display Preview -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Ekran Önizlemesi</h3>
                    <p class="text-sm text-gray-600">Bu etkinlik dijital ekranda nasıl görünecek</p>
                </div>
                <div class="p-6">
                    <div class="bg-gradient-to-br from-purple-600 to-blue-600 rounded-lg p-8 text-white text-center">
                        <div class="text-6xl mb-4">{{ $event->event_type_icon }}</div>
                        <h2 class="text-3xl font-bold mb-2">{{ $event->title }}</h2>
                        <p class="text-xl mb-4">{{ $event->event_date->format('d.m.Y H:i') }}</p>
                        @if($event->location)
                        <p class="text-lg opacity-90">{{ $event->location }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Hızlı İşlemler</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.events.edit', $event) }}"
                       class="w-full flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-all duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        Düzenle
                    </a>

                    <a href="{{ route('events.display') }}"
                       target="_blank"
                       class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-all duration-200">
                        <i class="fas fa-tv mr-2"></i>
                        Tam Ekran Görüntüle
                    </a>

                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                          onsubmit="return confirm('Bu etkinliği silmek istediğinizden emin misiniz?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-all duration-200">
                            <i class="fas fa-trash mr-2"></i>
                            Sil
                        </button>
                    </form>
                </div>
            </div>

            <!-- Event Stats -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Etkinlik İstatistikleri</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Oluşturulma Tarihi</span>
                        <span class="text-sm font-medium text-gray-900">{{ $event->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Son Güncelleme</span>
                        <span class="text-sm font-medium text-gray-900">{{ $event->updated_at->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Etkinlik Durumu</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $event->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $event->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Event Type Info -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Etkinlik Türü</h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <div class="text-4xl mb-2">{{ $event->event_type_icon }}</div>
                        <p class="text-lg font-medium text-gray-900">{{ $event->event_type_label }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection












