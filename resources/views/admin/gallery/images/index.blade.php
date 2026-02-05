@extends('admin.layouts.app')

@section('title', 'Galeri Resimleri')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">
        Galeri Resimleri
        @if(request('category_id'))
            @php $category = \App\Models\GalleryCategory::find(request('category_id')) @endphp
            @if($category)
                <span class="text-lg text-gray-600">- {{ $category->name }}</span>
            @endif
        @endif
    </h1>
    <div class="flex space-x-3">
        <a href="{{ route('admin.gallery-categories.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-folder mr-2"></i>Kategoriler
        </a>
        <a href="{{ route('admin.gallery-images.create', ['category_id' => request('category_id')]) }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Yeni Resim
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<!-- Category Filter -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="flex items-center space-x-4">
        <div class="flex-1">
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori Filtresi</label>
            <select name="category_id" id="category_id" onchange="this.form.submit()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Tüm Kategoriler</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->images()->count() }} resim)
                    </option>
                @endforeach
            </select>
        </div>
        @if(request('category_id'))
            <div class="mt-6">
                <button type="button" onclick="showBulkUploadModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-upload mr-2"></i>Toplu Yükleme
                </button>
            </div>
        @endif
    </form>
</div>

<div class="bg-white rounded-lg shadow">
    @if($images->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
            @foreach($images as $image)
                <div class="relative group bg-white rounded-lg shadow border overflow-hidden">
                    <div class="aspect-w-16 aspect-h-9">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->title }}" 
                             class="w-full h-48 object-cover">
                    </div>
                    
                    <div class="p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-1 line-clamp-2">{{ $image->title }}</h3>
                        <p class="text-xs text-gray-500 mb-2">{{ $image->category->name }}</p>
                        
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $image->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $image->is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                            <span class="text-xs text-gray-500">Sıra: {{ $image->sort_order }}</span>
                        </div>
                    </div>
                    
                    <!-- Action Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                        <div class="flex space-x-2">
                            <button onclick="toggleStatus({{ $image->id }})" 
                                    class="p-2 bg-white rounded-full text-gray-600 hover:text-gray-900 transition-colors" 
                                    title="Durumu Değiştir">
                                <i class="fas fa-power-off"></i>
                            </button>
                            <a href="{{ route('admin.gallery-images.edit', $image) }}" 
                               class="p-2 bg-white rounded-full text-blue-600 hover:text-blue-900 transition-colors" 
                               title="Düzenle">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.gallery-images.destroy', $image) }}" 
                                  method="POST" class="inline" 
                                  onsubmit="return confirm('Bu resmi silmek istediğinizden emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2 bg-white rounded-full text-red-600 hover:text-red-900 transition-colors" 
                                        title="Sil">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="px-6 py-4 border-t">
            {{ $images->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-images text-6xl text-gray-300 mb-6"></i>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Henüz resim bulunmuyor</h3>
            <p class="text-gray-600 mb-8">
                @if(request('category_id'))
                    Bu kategoride henüz resim bulunmuyor.
                @else
                    Henüz hiç resim yüklenmemiş.
                @endif
            </p>
            <a href="{{ route('admin.gallery-images.create', ['category_id' => request('category_id')]) }}" 
               class="bg-teal-600 text-white px-6 py-3 rounded-lg hover:bg-teal-700 transition-colors font-medium">
                <i class="fas fa-plus mr-2"></i>İlk Resmi Ekle
            </a>
        </div>
    @endif
</div>

<!-- Bulk Upload Modal -->
<div id="bulkUploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Toplu Resim Yükleme</h3>
                <button onclick="closeBulkUploadModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.gallery-images.bulk-upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="gallery_category_id" value="{{ request('category_id') }}">
                
                <div class="mb-4">
                    <label for="bulk_images" class="block text-sm font-medium text-gray-700 mb-2">Resimler Seçin</label>
                    <input type="file" name="images[]" id="bulk_images" multiple accept="image/*" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <p class="mt-1 text-sm text-gray-500">Birden fazla resim seçebilirsiniz. Her biri maksimum 5MB olmalıdır.</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeBulkUploadModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                        İptal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition-colors">
                        <i class="fas fa-upload mr-2"></i>Yükle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleStatus(id) {
    fetch(`/admin/gallery-images/${id}/toggle-status`, {
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

function showBulkUploadModal() {
    document.getElementById('bulkUploadModal').classList.remove('hidden');
}

function closeBulkUploadModal() {
    document.getElementById('bulkUploadModal').classList.add('hidden');
}
</script>
@endsection