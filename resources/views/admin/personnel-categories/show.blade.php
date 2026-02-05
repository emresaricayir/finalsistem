@extends('admin.layouts.app')

@section('title', $personnelCategory->name . ' - Kişiler')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center mb-2">
                <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $personnelCategory->color }}"></div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $personnelCategory->name }}</h1>
            </div>
            @if($personnelCategory->description)
                <p class="text-gray-600">{{ $personnelCategory->description }}</p>
            @endif
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.personnel-categories.edit', $personnelCategory) }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>
                Kategoriyi Düzenle
            </a>
            <a href="{{ route('admin.board-members.create') }}?category={{ $personnelCategory->id }}"
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Yeni Kişi Ekle
            </a>
        </div>
    </div>

    <!-- Personnel List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($personnel->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                @foreach($personnel as $member)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start space-x-4">
                            @if($member->image_path)
                                <img src="{{ $member->image_url }}"
                                     alt="{{ $member->name }}"
                                     class="w-16 h-16 rounded-lg object-cover">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400 text-xl"></i>
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $member->name }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $member->title }}</p>

                                @if($member->email)
                                    <p class="text-xs text-gray-500 mb-1">
                                        <i class="fas fa-envelope mr-1"></i>
                                        {{ $member->email }}
                                    </p>
                                @endif

                                @if($member->phone)
                                    <p class="text-xs text-gray-500 mb-2">
                                        <i class="fas fa-phone mr-1"></i>
                                        {{ $member->phone }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $member->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $member->is_active ? 'Aktif' : 'Pasif' }}
                                    </span>

                                    <div class="flex items-center space-x-1">
                                        <a href="{{ route('admin.board-members.edit', $member) }}"
                                           class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50" title="Düzenle">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.board-members.destroy', $member) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Bu personeli silmek istediğinizden emin misiniz?')"
                                                    class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50" title="Sil">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Bu kategoride henüz kişi yok</h3>
                <p class="text-gray-600 mb-4">Bu kategoriye kişi eklemek için aşağıdaki butona tıklayın.</p>
                <a href="{{ route('admin.board-members.create') }}?category={{ $personnelCategory->id }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    İlk Kişiyi Ekle
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
