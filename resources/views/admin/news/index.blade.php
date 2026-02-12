@extends('admin.layouts.app')

@section('title', 'Haberler')

@section('content')
<div class="space-y-6">
    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center justify-between alert-success">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3 text-xl"></i>
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700 ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-newspaper mr-2 text-blue-500"></i>
                Haberler
            </h1>
            <p class="mt-2 text-gray-600">Sitede gösterilecek haberleri yönetin.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.news.create') }}" class="btn-primary text-white px-6 py-3 rounded-xl font-medium flex items-center shadow-lg hover:shadow-xl transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>
                Yeni Haber Ekle
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Başlık veya içerikte ara..."
                       class="w-full border border-gray-300 rounded-xl px-4 py-2">
            </div>
            <div>
                <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-2">
                    <option value="">Durum (Hepsi)</option>
                    <option value="active" {{ request('status')==='active'?'selected':'' }}>Aktif</option>
                    <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Pasif</option>
                </select>
            </div>
            <div>
                <button class="btn-info text-white px-6 py-2 rounded-xl">Filtrele</button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-600">
                        <th class="px-4 py-2">Başlık</th>
                        <th class="px-4 py-2">Durum</th>
                        <th class="px-4 py-2">Öne Çıkan</th>
                        <th class="px-4 py-2">Sıra</th>
                        <th class="px-4 py-2">Tarih</th>
                        <th class="px-4 py-2 text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($newsItems as $item)
                        <tr class="border-t">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $item->title }}</td>
                            <td class="px-4 py-3">
                                <form action="{{ route('admin.news.toggle-status', $item) }}" method="POST">
                                    @csrf
                                    <button class="px-3 py-1 rounded-lg text-xs {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $item->is_active ? 'Aktif' : 'Pasif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3">
                                @if($item->is_featured)
                                    <span class="px-3 py-1 rounded-lg text-xs bg-purple-100 text-purple-700">Öne Çıkan</span>
                                @else
                                    <span class="px-3 py-1 rounded-lg text-xs bg-gray-100 text-gray-600">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $item->sort_order }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $item->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.news.edit', $item) }}" class="text-blue-600 hover:underline mr-3">Düzenle</a>
                                <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Kayıt bulunamadı.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $newsItems->links() }}
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Success mesajını 5 saniye sonra otomatik kaldır
    @if(session('success'))
        setTimeout(function() {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    @endif
</script>
@endsection
@endsection


