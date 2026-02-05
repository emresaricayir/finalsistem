@extends('admin.layouts.app')

@section('title', 'Reklamlar')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Reklamlar</h1>
                    <p class="text-gray-600 mt-1">TV Ekranında üyelerin arasında gösterilecek reklamları yönetin</p>
                </div>
                <a href="{{ route('admin.settings.tv-display-messages.create') }}"
                   class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Yeni
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Messages List -->
        @if($messages->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($messages as $message)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-200">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ $message->name }}</h3>
                                    <p class="text-purple-100 text-sm mt-1">{{ $message->title }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($message->is_active)
                                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Aktif</span>
                                    @else
                                        <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded-full">Pasif</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="space-y-3">
                                @if($message->content)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">İçerik:</label>
                                    <p class="text-gray-900 text-sm line-clamp-3">{{ Str::limit($message->content, 100) }}</p>
                                </div>
                                @endif

                                @if($message->image)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Resim:</label>
                                    <img src="{{ asset($message->image) }}" alt="Mesaj resmi" class="w-16 h-16 object-cover rounded mt-1">
                                </div>
                                @endif

                                @if($message->display_pages && count($message->display_pages) > 0)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Gösterilecek Sayfalar:</label>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($message->display_pages as $page)
                                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $page }}. Sayfa</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <div class="flex justify-end items-center pt-4 border-t">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.settings.tv-display-messages.show', $message) }}"
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.settings.tv-display-messages.edit', $message) }}"
                                           class="text-green-600 hover:text-green-800 text-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.settings.tv-display-messages.destroy', $message) }}"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Bu mesajı silmek istediğinizden emin misiniz?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                <i class="fas fa-trash"></i>
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
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-comments text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Henüz reklam yok</h3>
                <p class="text-gray-500 mb-6">TV ekranında gösterilecek ilk reklamınızı oluşturun.</p>
                <a href="{{ route('admin.settings.tv-display-messages.create') }}"
                   class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200 inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    İlk Mesajı Oluştur
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
