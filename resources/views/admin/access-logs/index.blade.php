@extends('admin.layouts.app')

@section('title', 'Veri Erişim Logları')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-clipboard-list mr-2 text-blue-500"></i>
                Veri Erişim Logları
            </h1>
            <p class="mt-2 text-gray-600">DSGVO gereği üye verilerine yapılan erişimlerin kayıtları</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('admin.access-logs.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Member Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Üye</label>
                <select name="member_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tümü</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
                            {{ $member->surname }} {{ $member->name }} ({{ $member->member_no }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- User Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kullanıcı</label>
                <select name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tümü</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İşlem</label>
                <select name="action" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tümü</option>
                    <option value="view" {{ request('action') == 'view' ? 'selected' : '' }}>Görüntüleme</option>
                    <option value="edit" {{ request('action') == 'edit' ? 'selected' : '' }}>Düzenleme</option>
                    <option value="export" {{ request('action') == 'export' ? 'selected' : '' }}>Veri İndirme</option>
                    <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Silme</option>
                    <option value="restore" {{ request('action') == 'restore' ? 'selected' : '' }}>Geri Getirme</option>
                    <option value="payment_create" {{ request('action') == 'payment_create' ? 'selected' : '' }}>Ödeme Alındı</option>
                    <option value="payment_delete" {{ request('action') == 'payment_delete' ? 'selected' : '' }}>Ödeme Silindi</option>
                    <option value="due_create" {{ request('action') == 'due_create' ? 'selected' : '' }}>Aidat Oluşturuldu</option>
                    <option value="due_delete" {{ request('action') == 'due_delete' ? 'selected' : '' }}>Aidat Silindi</option>
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Başlangıç Tarihi</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Date To -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bitiş Tarihi</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Filter Buttons -->
            <div class="md:col-span-2 lg:col-span-5 flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrele
                </button>
                <a href="{{ route('admin.access-logs.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                    <i class="fas fa-times mr-2"></i>
                    Temizle
                </a>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tarih/Saat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Üye</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kullanıcı</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">İşlem</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">IP Adresi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Detaylar</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="font-medium">{{ $log->created_at->format('d.m.Y') }}</div>
                            <div class="text-gray-500 text-xs">{{ $log->created_at->format('H:i:s') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $log->member->full_name }}</div>
                            <div class="text-sm text-gray-500">{{ $log->member->member_no }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($log->user)
                                <div class="text-sm font-medium text-gray-900">{{ $log->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $log->user->email }}</div>
                            @else
                                <span class="text-sm text-gray-500 italic">Üye kendisi</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $actionColors = [
                                    'view' => 'bg-blue-100 text-blue-800',
                                    'edit' => 'bg-yellow-100 text-yellow-800',
                                    'export' => 'bg-green-100 text-green-800',
                                    'delete' => 'bg-red-100 text-red-800',
                                    'restore' => 'bg-purple-100 text-purple-800',
                                    'payment_create' => 'bg-emerald-100 text-emerald-800',
                                    'payment_delete' => 'bg-rose-100 text-rose-800',
                                    'due_create' => 'bg-cyan-100 text-cyan-800',
                                    'due_delete' => 'bg-orange-100 text-orange-800',
                                ];
                                $color = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                {{ $log->action_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $log->ip_address ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if($log->details && is_array($log->details))
                                @if(isset($log->details['changed_fields']))
                                    <span class="text-xs">Değişen alanlar: {{ implode(', ', $log->details['changed_fields']) }}</span>
                                @elseif(isset($log->details['deletion_reason']))
                                    <span class="text-xs">Sebep: {{ Str::limit($log->details['deletion_reason'], 50) }}</span>
                                @elseif(isset($log->details['format']))
                                    <span class="text-xs">Format: {{ strtoupper($log->details['format']) }}</span>
                                @elseif(isset($log->details['amount']))
                                    <span class="text-xs">
                                        Tutar: {{ number_format($log->details['amount'], 2) }} €
                                        @if(isset($log->details['year']) && isset($log->details['month']))
                                            | {{ $log->details['year'] }}-{{ str_pad($log->details['month'], 2, '0', STR_PAD_LEFT) }}
                                        @endif
                                        @if(isset($log->details['payment_date']))
                                            | Tarih: {{ $log->details['payment_date'] }}
                                        @endif
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-clipboard-list text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500 font-medium">Henüz log kaydı bulunmamaktadır.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-eye text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Görüntüleme</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $logs->where('action', 'view')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-edit text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Düzenleme</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $logs->where('action', 'edit')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-download text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Veri İndirme</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $logs->where('action', 'export')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-trash-alt text-red-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Silme/Geri Getirme</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $logs->whereIn('action', ['delete', 'restore'])->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
