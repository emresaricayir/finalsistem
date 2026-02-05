@extends('admin.layouts.app')

@section('title', 'Duyuru Detayı')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-eye mr-2 text-blue-500"></i>
                Duyuru Detayı
            </h1>
            <p class="mt-2 text-gray-600">{{ $announcement->title }}</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-3">
            <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn-primary text-white px-6 py-3 rounded-xl font-medium">
                <i class="fas fa-edit mr-2"></i>Düzenle
            </a>
            <a href="{{ route('admin.announcements.index') }}" class="btn-secondary px-6 py-3 rounded-xl font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Geri Dön
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <!-- Cover Image -->
                @if($announcement->image_url)
                    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200">
                        <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}" class="w-full aspect-video object-cover">
                    </div>
                @endif

                <!-- Title and Badges -->
                <div class="mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <h2 class="text-3xl font-bold text-gray-900">{{ $announcement->title }}</h2>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $announcement->type_badge_class }}">
                            @if($announcement->type === 'obituary')
                                <i class="fas fa-dove mr-2"></i>
                            @else
                                <i class="fas fa-bullhorn mr-2"></i>
                            @endif
                            {{ $announcement->type_display }}
                        </span>

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $announcement->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-{{ $announcement->is_active ? 'check' : 'times' }} mr-2"></i>
                            {{ $announcement->is_active ? 'Aktif' : 'Pasif' }}
                        </span>

                        @if($announcement->is_featured)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-star mr-2"></i>Öne Çıkan
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                @if($announcement->content)
                    <div class="prose max-w-none">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>
                @endif

                <!-- Vefat Duyurusu Özel Bilgileri -->
                @if($announcement->type === 'obituary' && ($announcement->obituary_name || $announcement->obituary_date || $announcement->funeral_time || $announcement->funeral_place || $announcement->burial_place))
                <div class="mt-8 bg-gradient-to-br from-slate-50 to-gray-100 rounded-2xl p-8 border border-gray-200 shadow-sm">
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                            @if(\App\Models\Settings::hasLogo())
                                <img src="{{ \App\Models\Settings::getLogoUrl() }}" alt="{{ \App\Models\Settings::get('organization_name', 'Organizasyon') }}" class="w-16 h-16 object-contain">
                            @else
                                <i class="fas fa-mosque text-3xl text-slate-600"></i>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">
                            Vefat Duyurusu Bilgileri
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($announcement->obituary_name)
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-user text-slate-500 mr-3 text-lg"></i>
                                <span class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Vefat Edenin Adı Soyadı</span>
                            </div>
                            <p class="text-xl font-bold text-slate-800">{{ $announcement->obituary_name }}</p>
                        </div>
                        @endif

                        @if($announcement->obituary_date)
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-calendar text-slate-500 mr-3 text-lg"></i>
                                <span class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Vefat Tarihi</span>
                            </div>
                            <p class="text-xl font-bold text-slate-800">{{ $announcement->obituary_date->format('d.m.Y') }}</p>
                        </div>
                        @endif

                        @if($announcement->funeral_time)
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-clock text-slate-500 mr-3 text-lg"></i>
                                <span class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Cenaze Namazı Saati</span>
                            </div>
                            <p class="text-xl font-bold text-slate-800">{{ $announcement->funeral_time->format('H:i') }}</p>
                        </div>
                        @endif

                        @if($announcement->funeral_place)
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-mosque text-slate-500 mr-3 text-lg"></i>
                                <span class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Cenaze Namazı Yeri</span>
                            </div>
                            <p class="text-xl font-bold text-slate-800">{{ $announcement->funeral_place }}</p>
                        </div>
                        @endif

                        @if($announcement->burial_place)
                        <div class="md:col-span-2 bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-map-marker-alt text-slate-500 mr-3 text-lg"></i>
                                <span class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Defin Yeri</span>
                            </div>
                            <p class="text-xl font-bold text-slate-800">{{ $announcement->burial_place }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Duyuru Bilgileri
                </h3>

                <div class="space-y-4">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Oluşturulma Tarihi</span>
                        <p class="text-gray-900">{{ $announcement->created_at->format('d.m.Y H:i') }}</p>
                    </div>

                    @if($announcement->updated_at != $announcement->created_at)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Son Güncelleme</span>
                        <p class="text-gray-900">{{ $announcement->updated_at->format('d.m.Y H:i') }}</p>
                    </div>
                    @endif

                    <div>
                        <span class="text-sm font-medium text-gray-500">Oluşturan</span>
                        <p class="text-gray-900">{{ $announcement->creator->name }}</p>
                    </div>

                    @if($announcement->start_date)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Başlangıç Tarihi</span>
                        <p class="text-gray-900">{{ $announcement->start_date->format('d.m.Y') }}</p>
                    </div>
                    @endif

                    @if($announcement->end_date)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Bitiş Tarihi</span>
                        <p class="text-gray-900">{{ $announcement->end_date->format('d.m.Y') }}</p>
                    </div>
                    @endif

                    <div>
                        <span class="text-sm font-medium text-gray-500">Sıralama</span>
                        <p class="text-gray-900">{{ $announcement->sort_order }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-cogs mr-2 text-gray-500"></i>
                    İşlemler
                </h3>

                <div class="space-y-3">
                    <form action="{{ route('admin.announcements.toggle-status', $announcement) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full {{ $announcement->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-{{ $announcement->is_active ? 'pause' : 'play' }} mr-2"></i>
                            {{ $announcement->is_active ? 'Pasif Yap' : 'Aktif Yap' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="w-full"
                          onsubmit="return confirm('Bu duyuruyu silmek istediğinizden emin misiniz?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-trash mr-2"></i>Duyuruyu Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
