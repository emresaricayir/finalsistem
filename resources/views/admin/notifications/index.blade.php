@extends('admin.layouts.app')

@section('title', 'Bildirimler')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-bell mr-2 text-blue-500"></i>
                Bildirimler
            </h1>
            <p class="mt-2 text-gray-600">Sistemdeki tüm bildirimleri görüntüleyin ve yönetin.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button id="mark-all-read-btn" class="btn-primary text-white px-6 py-3 rounded-xl font-medium flex items-center shadow-lg hover:shadow-xl transition-all duration-200">
                <i class="fas fa-check-double mr-2"></i>
                Tümünü Okundu İşaretle
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Toplam Bildirim</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $notifications->total() }}</p>
                </div>
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bell text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Okunmamış</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $notifications->where('is_read', false)->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Okunmuş</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $notifications->where('is_read', true)->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Bu Ay</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">{{ $notifications->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Tüm Bildirimler</h2>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($notifications as $notification)
            <div class="notification-item px-6 py-4 hover:bg-gray-50 transition-colors {{ !$notification->is_read ? 'bg-blue-50' : '' }}" data-id="{{ $notification->id }}">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 mt-1">
                        <i class="fas {{ $notification->icon ?: getNotificationIcon($notification->type) }} text-{{ getNotificationColor($notification->type) }} text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900 {{ !$notification->is_read ? 'font-bold' : '' }}">
                                {{ $notification->title }}
                                @if(!$notification->is_read)
                                    <span class="inline-block w-2 h-2 bg-red-500 rounded-full ml-2"></span>
                                @endif
                            </p>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                @if(!$notification->is_read)
                                <button class="mark-read-btn text-xs text-blue-600 hover:text-blue-800" data-id="{{ $notification->id }}">
                                    Okundu İşaretle
                                </button>
                                @endif
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ getNotificationBadgeColor($notification->type) }}-100 text-{{ getNotificationBadgeColor($notification->type) }}-800">
                                {{ ucfirst($notification->type) }}
                            </span>
                            @if($notification->is_read)
                            <span class="text-xs text-gray-400">
                                <i class="fas fa-check mr-1"></i>
                                {{ $notification->read_at->diffForHumans() }} okundu
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <i class="fas fa-bell-slash text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Bildirim Bulunmuyor</h3>
                <p class="text-gray-500">Henüz hiç bildirim oluşturulmamış.</p>
            </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark as read functionality
    document.querySelectorAll('.mark-read-btn').forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.dataset.id;
            markAsRead(notificationId);
        });
    });

    // Mark all as read
    document.getElementById('mark-all-read-btn').addEventListener('click', function() {
        markAllAsRead();
    });

    function markAsRead(notificationId) {
        fetch('{{ route("admin.notifications.mark-read") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ notification_id: notificationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error marking notification as read:', error));
    }

    function markAllAsRead() {
        fetch('{{ route("admin.notifications.mark-all-read") }}', {
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
        })
        .catch(error => console.error('Error marking all notifications as read:', error));
    }
});
</script>
@endsection

@php
function getNotificationIcon($type) {
    $icons = [
        'info' => 'fa-info-circle',
        'warning' => 'fa-exclamation-triangle',
        'success' => 'fa-check-circle',
        'error' => 'fa-times-circle'
    ];
    return $icons[$type] ?? 'fa-bell';
}

function getNotificationColor($type) {
    $colors = [
        'info' => 'blue-500',
        'warning' => 'yellow-500',
        'success' => 'green-500',
        'error' => 'red-500'
    ];
    return $colors[$type] ?? 'blue-500';
}

function getNotificationBadgeColor($type) {
    $colors = [
        'info' => 'blue',
        'warning' => 'yellow',
        'success' => 'green',
        'error' => 'red'
    ];
    return $colors[$type] ?? 'blue';
}
@endphp
