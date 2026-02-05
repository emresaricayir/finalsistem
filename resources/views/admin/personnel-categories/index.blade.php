@extends('admin.layouts.app')

@section('title', 'Kategoriler')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kategoriler</h1>
            <p class="text-gray-600 text-sm mt-1">Kişi kategorilerini yönetin</p>
        </div>
        <a href="{{ route('admin.personnel-categories.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Yeni Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $category->color }}"></div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                    </div>
                    <div class="flex items-center space-x-1">
                        <a href="{{ route('admin.personnel-categories.edit', $category) }}"
                           class="text-blue-600 hover:text-blue-800 p-2 rounded hover:bg-blue-50" title="Düzenle">
                            <i class="fas fa-edit text-sm"></i>
                        </a>
                        <form action="{{ route('admin.personnel-categories.destroy', $category) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Bu kategoriyi silmek istediğinizden emin misiniz?')"
                                    class="text-red-600 hover:text-red-800 p-2 rounded hover:bg-red-50" title="Sil">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>

                @if($category->description)
                    <p class="text-gray-600 text-sm mb-4">{{ $category->description }}</p>
                @endif

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-users mr-1"></i>
                            {{ $category->personnel_count }} personel
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                    </div>
                    <a href="{{ route('admin.personnel-categories.show', $category) }}"
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Kişileri Gör <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                <i class="fas fa-folder text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Henüz kategori eklenmemiş</h3>
                <p class="text-gray-600 mb-4">Kişi kategorilerini organize etmek için ilk kategoriyi ekleyin.</p>
                <a href="{{ route('admin.personnel-categories.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    İlk Kategoriyi Ekle
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
