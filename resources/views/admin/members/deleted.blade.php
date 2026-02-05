@extends('admin.layouts.app')

@section('title', 'Silinen Üyeler')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('admin.members.index') }}" class="hover:text-blue-600 transition-colors">Üyeler</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-700 font-medium">Silinen Üyeler</span>
                </nav>
                <h1 class="text-3xl font-bold text-gray-900">Silinen Üyeler</h1>
                <p class="text-gray-600 mt-1">Silinen üyeleri görüntüleyin, geri getirin veya kalıcı olarak silin</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.members.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-lg hover:shadow-xl">
                    <i class="fas fa-users mr-2"></i>
                    Aktif Üyeler
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-200">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-red-50 to-red-100 border-b border-red-200 px-6 py-4">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-trash-alt text-red-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Silinen Üye Listesi</h2>
                    <p class="text-gray-600 text-sm">Aşağıda silinen üyelerin listesi görüntülenmektedir</p>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-6">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($deletedMembers->count() > 0)
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-600">Toplam Silinen</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $deletedMembers->total() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-yellow-600">Bu Sayfa</p>
                                <p class="text-2xl font-bold text-yellow-900">{{ $deletedMembers->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="bg-red-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-red-600">Dikkat</p>
                                @if(auth()->user()->isSuperAdmin())
                                    <p class="text-sm text-red-700">Kalıcı silme geri alınamaz</p>
                                @else
                                    <p class="text-sm text-red-700">Kalıcı silme sadece başkan tarafından yapılabilir</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Desktop Table Section -->
                <div class="hidden lg:block bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="overflow-hidden">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">
                                        <i class="fas fa-hashtag mr-1"></i>Üye No
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-64">
                                        <i class="fas fa-user mr-1"></i>Ad Soyad
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-80">
                                        <i class="fas fa-info-circle mr-1"></i>Silme Nedeni
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                        <i class="fas fa-calendar-times mr-1"></i>Silinme Tarihi
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-48">
                                        <i class="fas fa-cogs mr-1"></i>İşlemler
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($deletedMembers as $member)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                #{{ $member->member_no }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                                        {{ substr($member->name, 0, 1) }}{{ substr($member->surname, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-semibold text-gray-900 truncate">{{ $member->full_name }}</div>
                                                    <div class="text-xs text-gray-500 truncate">{{ $member->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($member->deletion_reason)
                                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                                    <div class="text-sm text-yellow-700 mb-2">{{ $member->deletion_reason }}</div>
                                                    @if($member->deleted_by)
                                                        <div class="text-xs text-yellow-600">
                                                            <i class="fas fa-user mr-1"></i>
                                                            Silen: {{ \App\Models\User::find($member->deleted_by)->name ?? 'Bilinmeyen' }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-500 italic">
                                                    <i class="fas fa-minus-circle mr-1"></i>
                                                    Belirtilmemiş
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <div class="bg-red-100 p-1.5 rounded-lg mr-2">
                                                    <i class="fas fa-trash text-red-600 text-xs"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $member->deleted_at->format('d.m.Y') }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $member->deleted_at->format('H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center space-x-1">
                                                <form action="{{ route('admin.members.restore', $member->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 flex items-center shadow-md hover:shadow-lg"
                                                            onclick="return confirm('Bu üyeyi geri getirmek istediğinizden emin misiniz?')">
                                                        <i class="fas fa-undo mr-1"></i>
                                                        Geri Getir
                                                    </button>
                                                </form>

                                                @if(auth()->user()->isSuperAdmin())
                                                    <form action="{{ route('admin.members.force-delete', $member->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 flex items-center shadow-md hover:shadow-lg"
                                                                onclick="return confirm('Bu üyeyi KALICI olarak silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!')">
                                                            <i class="fas fa-trash mr-1"></i>
                                                            Kalıcı Sil
                                                        </button>
                                                    </form>
                                                @else
                                                    <button disabled class="bg-gray-400 cursor-not-allowed text-white px-3 py-1.5 rounded-lg text-xs font-medium flex items-center"
                                                            title="Sadece başkan kalıcı silme yapabilir">
                                                        <i class="fas fa-lock mr-1"></i>
                                                        Yetki Gerekli
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Cards Section -->
                <div class="lg:hidden space-y-4">
                    @foreach($deletedMembers as $member)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                            <!-- Card Header -->
                            <div class="px-4 py-3 bg-gradient-to-r from-red-50 to-red-100 border-b border-red-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg mr-3">
                                            {{ substr($member->name, 0, 1) }}{{ substr($member->surname, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $member->full_name }}</div>
                                            <div class="text-xs text-gray-500">#{{ $member->member_no }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-trash mr-1"></i>
                                            Silindi
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="p-4 space-y-3">
                                <!-- Silme Nedeni (Öncelikli) -->
                                @if($member->deletion_reason)
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                        <div class="text-sm text-yellow-700 mb-2">{{ $member->deletion_reason }}</div>
                                        @if($member->deleted_by)
                                            <div class="text-xs text-yellow-600">
                                                <i class="fas fa-user mr-1"></i>
                                                Silen: {{ \App\Models\User::find($member->deleted_by)->name ?? 'Bilinmeyen' }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-minus-circle text-gray-500 mr-2"></i>
                                            <span class="text-sm text-gray-600 italic">Belirtilmemiş</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- İletişim Bilgileri -->
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-envelope text-gray-400 mr-2 w-4"></i>
                                        <span class="truncate">{{ $member->email }}</span>
                                    </div>
                                    @if($member->phone)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-phone text-gray-400 mr-2 w-4"></i>
                                            <span>{{ $member->phone }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Silinme Tarihi -->
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar-times text-gray-400 mr-2 w-4"></i>
                                    <span>{{ $member->deleted_at->format('d.m.Y H:i') }}</span>
                                </div>

                                <!-- İşlem Butonları -->
                                <div class="flex flex-col space-y-2 pt-2 border-t border-gray-100">
                                    <form action="{{ route('admin.members.restore', $member->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center justify-center shadow-md hover:shadow-lg"
                                                onclick="return confirm('Bu üyeyi geri getirmek istediğinizden emin misiniz?')">
                                            <i class="fas fa-undo mr-2"></i>
                                            Geri Getir
                                        </button>
                                    </form>

                                    @if(auth()->user()->isSuperAdmin())
                                        <form action="{{ route('admin.members.force-delete', $member->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center justify-center shadow-md hover:shadow-lg"
                                                    onclick="return confirm('Bu üyeyi KALICI olarak silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!')">
                                                <i class="fas fa-trash mr-2"></i>
                                                Kalıcı Sil
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="w-full bg-gray-400 cursor-not-allowed text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center justify-center"
                                                title="Sadece başkan kalıcı silme yapabilir">
                                            <i class="fas fa-lock mr-2"></i>
                                            Yetki Gerekli
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        {{ $deletedMembers->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-trash-alt text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">Silinen üye bulunmuyor</h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        Henüz silinen üye bulunmuyor. Tüm üyeler aktif durumda.
                    </p>
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('admin.members.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 inline-flex items-center shadow-lg hover:shadow-xl">
                            <i class="fas fa-users mr-2"></i>
                            Aktif Üyelere Git
                        </a>
                        <a href="{{ route('admin.members.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 inline-flex items-center shadow-lg hover:shadow-xl">
                            <i class="fas fa-plus mr-2"></i>
                            Yeni Üye Ekle
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
