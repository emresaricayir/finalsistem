@extends('admin.layouts.app')

@section('title', 'Duyurular')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-bullhorn mr-2 text-blue-500"></i>
                Duyurular
            </h1>
            <p class="mt-2 text-gray-600">Sistemdeki tüm duyuruları yönetin ve takip edin.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.announcements.create') }}" class="btn-primary text-white px-6 py-3 rounded-xl font-medium flex items-center shadow-lg hover:shadow-xl transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>
                Yeni Duyuru Ekle
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Toplam Duyuru</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $announcements->total() }}</p>
                </div>
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bullhorn text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Aktif Duyurular</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $announcements->where('is_active', true)->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Öne Çıkan</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">{{ $announcements->where('is_featured', true)->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-star text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pasif Duyurular</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $announcements->where('is_active', false)->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-times-circle text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('admin.announcements.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Arama</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Başlık veya içerik ara...">
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tür</label>
                <select name="type" id="type" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tüm Türler</option>
                    <option value="general" {{ request('type') === 'general' ? 'selected' : '' }}>Güncel Duyuru</option>
                    <option value="obituary" {{ request('type') === 'obituary' ? 'selected' : '' }}>Vefat Duyurusu</option>
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
                <select name="status" id="status" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tüm Durumlar</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Pasif</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-medium transition-colors">
                    <i class="fas fa-search mr-2"></i>Filtrele
                </button>
            </div>
        </form>
    </div>

    <!-- Announcements: Desktop Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hidden sm:block">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duyuru</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tür</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($announcements as $announcement)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($announcement->image_url)
                                    <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}" class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                                @else
                                    <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="flex items-center">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $announcement->title }}</h3>
                                        @if($announcement->is_featured)
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-star mr-1"></i>Öne Çıkan
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($announcement->content, 100) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->type_badge_class }}">
                                {{ $announcement->type_display }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $announcement->is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.announcements.show', $announcement) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors"
                                   title="Görüntüle">
                                    <i class="fas fa-eye mr-1"></i>Görüntüle
                                </a>
                                <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors"
                                   title="Düzenle">
                                    <i class="fas fa-edit mr-1"></i>Düzenle
                                </a>
                                <button onclick="deleteAnnouncement({{ $announcement->id }}, '{{ $announcement->title }}')"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors"
                                        title="Sil">
                                    <i class="fas fa-trash mr-1"></i>Sil
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Kayıt bulunamadı.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $announcements->links() }}
        </div>
    </div>

    <!-- Announcements: Mobile List -->
    <div class="sm:hidden space-y-3">
        @forelse($announcements as $announcement)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center justify-between">
                <div class="min-w-0 pr-3">
                    <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $announcement->title }}</h3>
                    <div class="mt-1 flex items-center gap-2 text-xs">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $announcement->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $announcement->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $announcement->type_badge_class }}">
                            {{ $announcement->type_display }}
                        </span>
                        @if($announcement->is_featured)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">
                                <i class="fas fa-star mr-1"></i>Öne Çıkan
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('admin.announcements.show', $announcement) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600" title="Görüntüle">
                        <i class="fas fa-eye text-sm"></i>
                    </a>
                    <a href="{{ route('admin.announcements.edit', $announcement) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600" title="Düzenle">
                        <i class="fas fa-edit text-sm"></i>
                    </a>
                    <button onclick="deleteAnnouncement({{ $announcement->id }}, '{{ $announcement->title }}')" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600" title="Sil">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 text-sm">Kayıt bulunamadı.</div>
        @endforelse

        <div class="pt-2">
            {{ $announcements->links() }}
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Duyuruyu Sil</h3>
                <p class="text-sm text-gray-600">Bu işlem geri alınamaz</p>
            </div>
        </div>

        <div class="mb-6">
            <p class="text-gray-700">
                "<span id="deleteAnnouncementTitle" class="font-semibold"></span>" başlıklı duyuruyu silmek istediğinizden emin misiniz?
            </p>
        </div>

        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()"
                    class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                İptal
            </button>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                    <i class="fas fa-trash mr-2"></i>Sil
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function deleteAnnouncement(id, title) {
    document.getElementById('deleteAnnouncementTitle').textContent = title;
    document.getElementById('deleteForm').action = `/admin/announcements/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection
