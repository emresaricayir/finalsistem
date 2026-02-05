@extends('admin.layouts.app')

@section('title', 'Video Kategorileri')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-folder mr-2 text-blue-500"></i>
                Video Kategorileri
            </h1>
            <p class="mt-2 text-gray-600">Video galeri kategorilerini yönetin ve düzenleyin.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.video-categories.create') }}"
               class="btn-primary text-white px-6 py-3 rounded-xl font-medium inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Yeni Kategori
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Categories List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        @if($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Slug
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Video Sayısı
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sıralama
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Durum
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                İşlemler
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categories as $category)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($category->cover_image)
                                            <img src="{{ asset('storage/' . $category->cover_image) }}"
                                                 alt="{{ $category->name }}"
                                                 class="w-12 h-12 rounded-lg object-cover mr-4">
                                        @else
                                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center mr-4">
                                                <i class="fas fa-folder text-white text-lg"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                            @if($category->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($category->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $category->slug }}</code>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $category->videos_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $category->sort_order }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button onclick="toggleStatus({{ $category->id }})"
                                            class="status-toggle flex items-center space-x-2 {{ $category->is_active ? 'text-green-600' : 'text-red-600' }}">
                                        <div class="w-2 h-2 rounded-full {{ $category->is_active ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                        <span class="text-sm font-medium">{{ $category->is_active ? 'Aktif' : 'Pasif' }}</span>
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.video-categories.show', $category) }}"
                                           class="text-blue-600 hover:text-blue-900 transition-colors" title="Görüntüle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.video-categories.edit', $category) }}"
                                           class="text-yellow-600 hover:text-yellow-900 transition-colors" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="text-red-600 hover:text-red-900 transition-colors delete-category"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-folder text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Henüz video kategorisi bulunmuyor</h3>
                <p class="text-gray-600 mb-8 leading-relaxed">İlk video kategorinizi oluşturmak için yukarıdaki butonu kullanın.</p>
                <a href="{{ route('admin.video-categories.create') }}" class="btn-primary text-white px-6 py-3 rounded-xl font-medium inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    İlk Kategoriyi Oluştur
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 text-center mb-2">Kategori Sil</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 text-center">
                    <strong id="categoryName"></strong> kategorisini silmek istediğinizden emin misiniz?
                </p>
                <p class="text-xs text-red-600 text-center mt-2">
                    Bu işlem geri alınamaz ve kategorideki tüm videolar kategorisiz kalacaktır.
                </p>
            </div>
            <div class="flex items-center justify-center px-4 py-3 space-x-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    İptal
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Sil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle status function
function toggleStatus(categoryId) {
    fetch(`/admin/video-categories/${categoryId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload to update the status
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Delete category functions
function showDeleteModal(categoryId, categoryName) {
    document.getElementById('categoryName').textContent = categoryName;
    document.getElementById('deleteForm').action = `/admin/video-categories/${categoryId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Add event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Delete category buttons
    document.querySelectorAll('.delete-category').forEach(button => {
        button.addEventListener('click', function() {
            const categoryId = this.dataset.id;
            const categoryName = this.dataset.name;
            showDeleteModal(categoryId, categoryName);
        });
    });
});
</script>
@endpush
