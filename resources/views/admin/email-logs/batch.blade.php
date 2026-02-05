@extends('admin.layouts.app')

@section('title', 'Batch Detayları - E-posta Logları')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center mb-2">
                <a href="{{ route('admin.email-logs.index') }}"
                   class="text-gray-500 hover:text-gray-700 mr-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-layer-group mr-2 text-blue-600"></i>
                    Batch Detayları
                </h1>
            </div>
            <div class="ml-8">
                <p class="text-gray-600 text-sm">Batch ID: <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $batchId }}</span></p>
                <p class="text-gray-500 text-xs mt-1">Bu batch'e ait tüm e-posta gönderim kayıtları</p>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.email-logs.index', ['batch_id' => $batchId]) }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-filter mr-2"></i>
                Bu Batch'i Filtrele
            </a>
            <a href="{{ route('admin.email-logs.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-list mr-2"></i>
                Tüm Loglar
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-envelope text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Toplam</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Gönderildi</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['sent']) }}</p>
                    @if($stats['total'] > 0)
                        <p class="text-xs text-gray-500 mt-1">{{ number_format(($stats['sent'] / $stats['total']) * 100, 1) }}% başarı oranı</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-times text-red-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Başarısız</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['failed']) }}</p>
                    @if($stats['total'] > 0 && $stats['failed'] > 0)
                        <p class="text-xs text-gray-500 mt-1">{{ number_format(($stats['failed'] / $stats['total']) * 100, 1) }}% hata oranı</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Bekliyor</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Rate Card -->
    @if($stats['total'] > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-medium text-gray-700">Gönderim İstatistikleri</h3>
                <p class="text-xs text-gray-500 mt-1">Bu batch için detaylı gönderim sonuçları</p>
            </div>
            <div class="flex items-center space-x-4">
                @php
                    $successRate = ($stats['sent'] / $stats['total']) * 100;
                    $failureRate = ($stats['failed'] / $stats['total']) * 100;
                    $pendingRate = ($stats['pending'] / $stats['total']) * 100;
                @endphp
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ number_format($successRate, 1) }}%</div>
                    <div class="text-xs text-gray-500">Başarılı</div>
                </div>
                @if($stats['failed'] > 0)
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ number_format($failureRate, 1) }}%</div>
                    <div class="text-xs text-gray-500">Başarısız</div>
                </div>
                @endif
                @if($stats['pending'] > 0)
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ number_format($pendingRate, 1) }}%</div>
                    <div class="text-xs text-gray-500">Bekliyor</div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Email Logs Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-list mr-2 text-blue-600"></i>
                E-posta Gönderim Kayıtları ({{ $logs->count() }})
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alıcı</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Şablon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Konu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detay</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $index => $log)
                        <tr class="hover:bg-gray-50 {{ $log->status === 'failed' ? 'bg-red-50' : ($log->status === 'sent' ? 'bg-green-50' : '') }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $log->recipient_name ?: 'İsim Yok' }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $log->recipient_email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $log->template_name }}</div>
                                <div class="text-xs text-gray-500 font-mono">{{ $log->template_key }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $log->subject }}">
                                    {{ $log->subject }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log->status_badge_class }}">
                                    <i class="fas {{ $log->status === 'sent' ? 'fa-check-circle' : ($log->status === 'failed' ? 'fa-times-circle' : 'fa-clock') }} mr-1"></i>
                                    {{ $log->status_text }}
                                </span>
                                @if($log->error_message)
                                    <div class="mt-1">
                                        <button onclick="showError('{{ $log->id }}')"
                                                class="text-xs text-red-600 hover:text-red-800 underline">
                                            Hata Detayı
                                        </button>
                                        <div id="error-{{ $log->id }}" class="hidden mt-2 p-2 bg-red-50 border border-red-200 rounded text-xs text-red-800">
                                            {{ $log->error_message }}
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex flex-col">
                                    <div>Oluşturulma: {{ $log->created_at->format('d.m.Y H:i') }}</div>
                                    @if($log->sent_at)
                                        <div class="text-xs text-green-600 mt-1">
                                            <i class="fas fa-paper-plane mr-1"></i>
                                            Gönderildi: {{ $log->sent_at->format('d.m.Y H:i') }}
                                        </div>
                                    @endif
                                    @if($log->sent_by)
                                        <div class="text-xs text-gray-400 mt-1">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $log->sent_by }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($log->variables && count($log->variables) > 0)
                                    <button onclick="toggleVariables('{{ $log->id }}')"
                                            class="text-blue-600 hover:text-blue-800 text-xs">
                                        <i class="fas fa-code mr-1"></i>
                                        Değişkenler
                                    </button>
                                    <div id="variables-{{ $log->id }}" class="hidden mt-2 p-3 bg-gray-50 border border-gray-200 rounded text-xs">
                                        <pre class="whitespace-pre-wrap text-gray-700">{{ json_encode($log->variables, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p class="text-lg">Bu batch'e ait log bulunamadı</p>
                                <p class="text-sm">Henüz hiç e-posta gönderilmemiş.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Export Section -->
    @if($logs->count() > 0)
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-download mr-2 text-blue-600"></i>
            Export İşlemleri
        </h3>
        <div class="flex flex-wrap gap-3">
            <button onclick="exportToCsv()"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-file-csv mr-2"></i>
                CSV Olarak İndir
            </button>
            <button onclick="window.print()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-print mr-2"></i>
                Yazdır
            </button>
        </div>
    </div>
    @endif
</div>

<script>
function toggleVariables(logId) {
    const element = document.getElementById('variables-' + logId);
    element.classList.toggle('hidden');
}

function showError(logId) {
    const element = document.getElementById('error-' + logId);
    element.classList.toggle('hidden');
}

function exportToCsv() {
    const headers = ['#', 'Alıcı Adı', 'Alıcı E-posta', 'Şablon', 'Konu', 'Durum', 'Hata Mesajı', 'Oluşturulma Tarihi', 'Gönderilme Tarihi', 'Gönderen'];
    const rows = [
        @foreach($logs as $index => $log)
        [
            {{ $index + 1 }},
            '{{ addslashes($log->recipient_name ?: "İsim Yok") }}',
            '{{ $log->recipient_email }}',
            '{{ addslashes($log->template_name) }}',
            '{{ addslashes($log->subject) }}',
            '{{ $log->status_text }}',
            '{{ addslashes($log->error_message ?: "") }}',
            '{{ $log->created_at->format('d.m.Y H:i') }}',
            '{{ $log->sent_at ? $log->sent_at->format('d.m.Y H:i') : "" }}',
            '{{ $log->sent_by ?: "" }}'
        ]{{ !$loop->last ? ',' : '' }}
        @endforeach
    ];

    let csvContent = headers.join(',') + '\n';
    rows.forEach(row => {
        csvContent += row.map(cell => `"${cell}"`).join(',') + '\n';
    });

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'batch-{{ $batchId }}-{{ date('Y-m-d') }}.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<style>
@media print {
    .no-print {
        display: none;
    }
    body {
        background: white;
    }
}
</style>
@endsection

