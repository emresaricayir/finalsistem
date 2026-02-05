@extends('admin.layouts.app')

@section('title', 'Video Galeri Yönetimi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-video mr-2 text-red-500"></i>
                Video Galeri Yönetimi
            </h1>
            <p class="mt-2 text-gray-600">YouTube videolarını yönetin ve galeriyi düzenleyin.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.video-categories.index') }}"
               class="btn-secondary text-gray-700 px-6 py-3 rounded-xl font-medium inline-flex items-center">
                <i class="fas fa-tags mr-2"></i>
                Kategoriler
            </a>
            <a href="{{ route('admin.video-gallery.create') }}"
               class="btn-primary text-white px-6 py-3 rounded-xl font-medium inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Yeni Video Ekle
            </a>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori Filtresi</label>
                <select name="category_id" id="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tüm Kategoriler</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->videos_count }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrele
                </button>
                @if(request('category_id'))
                    <a href="{{ route('admin.video-gallery.index') }}" class="btn-secondary text-gray-700 px-4 py-2 rounded-lg font-medium ml-2">
                        <i class="fas fa-times mr-1"></i>
                        Temizle
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Videos List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        @if($videos->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Video
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Başlık
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sıra
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Durum
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tarih
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                İşlemler
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($videos as $video)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-24 h-16 rounded-lg overflow-hidden bg-gray-100">
                                        <img src="{{ $video->thumbnail_url }}"
                                             alt="{{ $video->title }}"
                                             class="w-full h-full object-cover"
                                             onerror="this.onerror=null; this.src='{{ $video->thumbnail_urls['mq'] ?? $video->thumbnail_urls['default'] ?? '' }}';">
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $video->title }}</div>
                                    @if($video->description)
                                        <div class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $video->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($video->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $video->category->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Kategorisiz
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $video->sort_order }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button onclick="toggleStatus({{ $video->id }})"
                                            class="status-toggle flex items-center space-x-2 {{ $video->is_active ? 'text-green-600' : 'text-red-600' }}">
                                        <div class="w-2 h-2 rounded-full {{ $video->is_active ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                        <span class="text-sm font-medium">{{ $video->is_active ? 'Aktif' : 'Pasif' }}</span>
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $video->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ $video->youtube_url }}" target="_blank"
                                           class="text-red-600 hover:text-red-900 transition-colors">
                                            <i class="fab fa-youtube"></i>
                                        </a>
                                        <a href="{{ route('admin.video-gallery.edit', $video) }}"
                                           class="text-blue-600 hover:text-blue-900 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.video-gallery.destroy', $video) }}"
                                              class="inline-block"
                                              onsubmit="return confirm('Bu videoyu silmek istediğinize emin misiniz?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($videos->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $videos->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <i class="fas fa-video text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Henüz video eklenmemiş</h3>
                <p class="text-gray-500 mb-6">YouTube videolarınızı ekleyerek güzel bir video galeri oluşturun.</p>
                <a href="{{ route('admin.video-gallery.create') }}"
                   class="btn-primary text-white px-6 py-3 rounded-xl font-medium inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    İlk Videoyu Ekle
                </a>
            </div>
        @endif
    </div>
</div>

<script>
async function toggleStatus(videoId) {
    try {
        const response = await fetch(`/admin/video-gallery/${videoId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Bir hata oluştu. Lütfen tekrar deneyin.');
    }
}
</script>
@endsection
