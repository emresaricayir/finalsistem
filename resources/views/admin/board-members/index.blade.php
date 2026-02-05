@extends('admin.layouts.app')

@section('title', 'Yönetim Kurulu Üyeleri')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-teal-800">Kişiler</h1>
            <p class="mt-2 text-gray-600">Sistemdeki tüm kişileri yönetin ve takip edin.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.personnel-categories.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                <i class="fas fa-tags mr-2"></i>Kategoriler
            </a>
            <a href="{{ route('admin.board-members.create') }}" class="bg-teal-600 text-white px-6 py-3 rounded-lg hover:bg-teal-700 transition-colors font-medium">
                <i class="fas fa-plus mr-2"></i>Yeni Kişi Ekle
            </a>
        </div>
    </div>

    <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded">
        <i class="fas fa-info-circle mr-2"></i>
        <span class="font-medium">Bilgi:</span>
        Görev alanı <span class="font-semibold">"Başkan"</span> olan üye otomatik olarak en üstte gösterilir. Her kategori kendi sayfasında görüntülenir.
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Üye Listesi</h2>
        </div>

        @if($boardMembers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sıra</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fotoğraf</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ad Soyad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Görev</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="sortable-list">
                        @foreach($boardMembers as $member)
                            <tr data-id="{{ $member->id }}" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas fa-grip-vertical text-gray-400 mr-2 cursor-move"></i>
                                        <span class="text-sm text-gray-900">{{ $member->sort_order }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($member->image_path)
                                        <img src="{{ asset('storage/' . $member->image_path) }}" alt="{{ $member->name }}" class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $member->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($member->category)
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $member->category->color }}"></div>
                                            <span class="text-sm text-gray-900">{{ $member->category->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">Kategori Yok</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $member->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $member->is_active ? 'Aktif' : 'Pasif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.board-members.edit', $member) }}" class="text-indigo-600 hover:text-indigo-900" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.board-members.destroy', $member) }}" method="POST" class="inline" onsubmit="return confirm('Bu üyeyi silmek istediğinizden emin misiniz?')">
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
                <i class="fas fa-users text-6xl text-gray-300 mb-6"></i>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Henüz üye bulunmuyor</h3>
                <p class="text-gray-600 mb-8">Yeni yönetim kurulu üyeleri eklendiğinde burada görünecektir.</p>
                <a href="{{ route('admin.board-members.create') }}" class="bg-teal-600 text-white px-6 py-3 rounded-lg hover:bg-teal-700 transition-colors font-medium">
                    <i class="fas fa-plus mr-2"></i>İlk Üyeyi Ekle
                </a>
            </div>
        @endif
    </div>
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

                fetch('{{ route("admin.board-members.reorder") }}', {
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
    fetch(`/admin/board-members/${id}/toggle-status`, {
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

