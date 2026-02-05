@extends('admin.layouts.app')

@section('title', 'Etkinlikler')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Etkinlikler</h1>
                    <p class="text-gray-600 mt-1">Topluluk etkinliklerini yönetin</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('events.display') }}" target="_blank"
                       class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-all duration-200 flex items-center">
                        <i class="fas fa-tv mr-2"></i>
                        Canlı Görüntüle
                    </a>
                    <a href="{{ route('admin.events.create') }}"
                       class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200 flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Yeni
                    </a>
                </div>
            </div>
        </div>

        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-200">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ $event->title }}</h3>
                                    <p class="text-purple-100 text-sm mt-1">{{ $event->event_type_label }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="text-2xl">{{ $event->event_type_icon }}</div>
                                    @if($event->is_active)
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
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Tarih & Saat:</label>
                                    <p class="text-gray-900">{{ $event->event_date->format('d.m.Y - H:i') }}</p>
                                </div>

                                @if($event->location)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Konum:</label>
                                    <p class="text-gray-900 text-sm">{{ $event->location }}</p>
                                </div>
                                @endif

                                @if($event->description)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Açıklama:</label>
                                    <p class="text-gray-900 text-sm line-clamp-2">{{ Str::limit($event->description, 100) }}</p>
                                </div>
                                @endif

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Gösterim Süresi:</label>
                                    <p class="text-gray-900 text-sm">{{ $event->display_duration }} saniye</p>
                                </div>

                                <div class="flex justify-end items-center pt-4 border-t">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.events.show', $event) }}"
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.events.edit', $event) }}"
                                           class="text-green-600 hover:text-green-800 text-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.events.destroy', $event) }}"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Bu etkinliği silmek istediğinizden emin misiniz?')">
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

            <!-- Pagination -->
            @if($events->hasPages())
            <div class="mt-6">
                {{ $events->links() }}
            </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-calendar-alt text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Henüz etkinlik yok</h3>
                <p class="text-gray-500 mb-6">İlk etkinliğinizi oluşturmaya başlayın.</p>
                <a href="{{ route('admin.events.create') }}"
                   class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200 inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    İlk Etkinliği Oluştur
                </a>
            </div>
        @endif
    </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Silme butonlarına event listener ekle
    const deleteButtons = document.querySelectorAll('[data-delete-event]');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            if (confirm('Bu etkinliği silmek istediğinizden emin misiniz?')) {
                const form = this.closest('form');
                form.submit();

                // Silme işleminden sonra display sayfasını yenile
                setTimeout(function() {
                    const displayWindow = window.open('{{ route("events.display") }}', '_blank');
                    if (displayWindow) {
                        displayWindow.location.reload();
                    }
                }, 1000);
            }
        });
    });
});
</script>
@endsection
