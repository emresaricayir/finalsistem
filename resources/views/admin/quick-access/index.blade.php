@extends('admin.layouts.app')

@section('title', 'Hızlı Erişim Yönetimi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-teal-800">Hızlı Erişim Yönetimi</h1>
        <a href="{{ route('admin.quick-access.create') }}" class="w-full sm:w-auto text-center bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Yeni Ekle
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <div class="inline-block min-w-full align-middle">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sıra</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İkon</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Başlık</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">URL</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Durum</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($quickAccessItems as $item)
                        <tr>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-teal-800">{{ $item->sort_order }}</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white" style="background-color: {{ $item->icon_color ?? '#14b8a6' }}">
                                    <i class="fas {{ $item->icon }} text-sm"></i>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-teal-800">{{ $item->title }}</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden md:table-cell">
                                @if($item->url)
                                    <a href="{{ $item->url }}" target="_blank" class="text-teal-600 hover:text-teal-800 break-all">
                                        <i class="fas fa-external-link-alt mr-1"></i>Link
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $item->is_active ? 'Aktif' : 'Pasif' }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.quick-access.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.quick-access.toggle-status', $item) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-{{ $item->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $item->is_active ? 'yellow' : 'green' }}-900" title="Durumu Değiştir">
                                            <i class="fas fa-{{ $item->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.quick-access.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Bu öğeyi silmek istediğinizden emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 sm:px-6 py-4 text-center text-gray-500">
                                Henüz hızlı erişim öğesi eklenmemiş.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
