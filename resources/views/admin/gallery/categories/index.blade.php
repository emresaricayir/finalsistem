@extends('admin.layouts.app')

@section('title', 'Galeri Kategorileri')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Galeri Kategorileri</h1>
    <a href="{{ route('admin.gallery-categories.create') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
        <i class="fas fa-plus mr-2"></i>Yeni Kategori
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow">
    @if($categories->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sıra</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resim Sayısı</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="sortable-list">
                    @foreach($categories as $category)
                        <tr data-id="{{ $category->id }}" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fas fa-grip-vertical text-gray-400 mr-2 cursor-move"></i>
                                    <span class="text-sm text-gray-900">{{ $category->sort_order }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->cover_image)
                                    <img src="{{ asset('storage/' . $category->cover_image) }}" alt="{{ $category->name }}" class="w-16 h-12 rounded object-cover">
                                @else
                                    <div class="w-16 h-12 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                <div class="text-sm text-gray-500">{{ $category->slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $category->images_count }} resim
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button onclick="toggleStatus({{ $category->id }})" 
                                        class="px-3 py-1 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? 'Aktif' : 'Pasif' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.gallery-images.index', ['category_id' => $category->id]) }}" 
                                       class="text-blue-600 hover:text-blue-900" title="Resimleri Görüntüle">
                                        <i class="fas fa-images"></i>
                                    </a>
                                    <a href="{{ route('admin.gallery-categories.edit', $category) }}" 
                                       class="text-indigo-600 hover:text-indigo-900" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.gallery-categories.destroy', $category) }}" 
                                          method="POST" class="inline" 
                                          onsubmit="return confirm('Bu kategori ve tüm resimleri silinecek. Emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Sil">
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
    @else
        <div class="text-center py-12">
            <i class="fas fa-folder-open text-6xl text-gray-300 mb-6"></i>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Henüz kategori bulunmuyor</h3>
            <p class="text-gray-600 mb-8">Yeni galeri kategorileri eklendiğinde burada görünecektir.</p>
            <a href="{{ route('admin.gallery-categories.create') }}" class="bg-teal-600 text-white px-6 py-3 rounded-lg hover:bg-teal-700 transition-colors font-medium">
                <i class="fas fa-plus mr-2"></i>İlk Kategoriyi Ekle
            </a>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortableList = document.getElementById('sortable-list');
    if (sortableList) {
        new Sortable(sortableList, {
            handle: '.fa-grip-vertical',
            animation: 150,
            onEnd: function(evt) {
                const items = Array.from(sortableList.children).map((item, index) => ({
                    id: item.dataset.id,
                    sort_order: index
                }));

                fetch('{{ route("admin.gallery-categories.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ items: items })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update sort order numbers
                        items.forEach((item, index) => {
                            const row = document.querySelector(`[data-id="${item.id}"]`);
                            if (row) {
                                row.querySelector('td:first-child span').textContent = index;
                            }
                        });
                    }
                });
            }
        });
    }
});

function toggleStatus(id) {
    fetch(`/admin/gallery-categories/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endsection