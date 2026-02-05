@extends('admin.layouts.app')

@section('title', 'Detaylı Raporlar')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                <i class="fas fa-list mr-2"></i>
                Detaylı Raporlar
            </h1>
            <p class="text-gray-600 mt-1">Üye ve ödeme verilerini detaylı olarak analiz edin</p>
        </div>
        <a href="{{ route('admin.reports.index') }}" class="btn-secondary px-4 py-2 rounded-xl font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Geri
        </a>
    </div>


    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-8 gap-3 items-end">
            <div class="lg:col-span-1">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Rapor Türü</label>
                <select name="type" id="type" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    <option value="payments" {{ $type === 'payments' ? 'selected' : '' }}>Ödemeler</option>
                    <option value="members" {{ $type === 'members' ? 'selected' : '' }}>Üyeler</option>
                    <option value="manual" {{ $type === 'manual' ? 'selected' : '' }}>Elden Takip</option>
                </select>
            </div>

            <div class="lg:col-span-1" id="startDateWrapper">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Başlangıç Tarihi</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" lang="tr"
                       class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
            </div>

            <div class="lg:col-span-1" id="endDateWrapper">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Bitiş Tarihi</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" lang="tr"
                       class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
            </div>

            <div class="lg:col-span-1">
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Ödeme Yöntemi</label>
                <select name="payment_method" id="payment_method" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    <option value="">Tümü</option>
                    <option value="cash" {{ (request('payment_method') === 'cash' || (isset($paymentMethod) && $paymentMethod === 'cash')) ? 'selected' : '' }}>Nakit</option>
                    <option value="bank_transfer" {{ (request('payment_method') === 'bank_transfer' || (isset($paymentMethod) && $paymentMethod === 'bank_transfer')) ? 'selected' : '' }}>Banka Transferi</option>
                    <option value="lastschrift_monthly" {{ (request('payment_method') === 'lastschrift_monthly' || (isset($paymentMethod) && $paymentMethod === 'lastschrift_monthly')) ? 'selected' : '' }}>Lastschrift (Aylık)</option>
                    <option value="lastschrift_semi_annual" {{ (request('payment_method') === 'lastschrift_semi_annual' || (isset($paymentMethod) && $paymentMethod === 'lastschrift_semi_annual')) ? 'selected' : '' }}>Lastschrift (6 Aylık)</option>
                    <option value="lastschrift_annual" {{ (request('payment_method') === 'lastschrift_annual' || (isset($paymentMethod) && $paymentMethod === 'lastschrift_annual')) ? 'selected' : '' }}>Lastschrift (Yıllık)</option>
                </select>
            </div>

            <div class="lg:col-span-1" id="addressFilterWrapper" style="display: none;">
                <label for="address_filter" class="block text-sm font-medium text-gray-700 mb-2">Adres Durumu</label>
                <select name="address_filter" id="address_filter" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    <option value="">Tümü</option>
                    <option value="has" {{ request('address_filter') === 'has' ? 'selected' : '' }}>Adresi Olan</option>
                    <option value="missing" {{ request('address_filter') === 'missing' ? 'selected' : '' }}>Adresi Olmayan</option>
                </select>
            </div>

            <div class="lg:col-span-1" id="phoneFilterWrapper" style="display: none;">
                <label for="phone_filter" class="block text-sm font-medium text-gray-700 mb-2">Telefon Durumu</label>
                <select name="phone_filter" id="phone_filter" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    <option value="">Tümü</option>
                    <option value="has" {{ request('phone_filter') === 'has' ? 'selected' : '' }}>Telefonu Olan</option>
                    <option value="missing" {{ request('phone_filter') === 'missing' ? 'selected' : '' }}>Telefonu Olmayan</option>
                </select>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-end space-y-2 sm:space-y-0 sm:space-x-2 lg:col-span-2 md:col-span-2 justify-end">
                <!-- Filtrele Butonu -->
                <button type="submit" id="filterButton" class="px-3 py-2 rounded-lg text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white transition-colors flex items-center justify-center">
                    <i class="fas fa-filter mr-1" id="filterIcon"></i>
                    <span id="filterText">Filtrele</span>
                </button>

                <!-- Export Butonları - Mobilde Grid, Masaüstünde Flex -->
                <div class="grid grid-cols-3 gap-2 sm:flex sm:space-x-2">
                    <a href="{{ route('admin.reports.export') }}?{{ http_build_query(request()->all()) }}"
                       class="px-2 sm:px-3 py-2 rounded-lg text-xs sm:text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors flex items-center justify-center"
                       onclick="this.innerHTML='<i class=\'fas fa-spinner fa-spin mr-1\'></i>İndiriliyor...'; this.style.pointerEvents='none';">
                        <i class="fas fa-file-csv mr-1"></i>
                        <span class="hidden sm:inline">CSV</span>
                        <span class="sm:hidden">CSV</span>
                    </a>
                    <a href="{{ route('admin.reports.export-xlsx') }}?{{ http_build_query(request()->all()) }}"
                       class="px-2 sm:px-3 py-2 rounded-lg text-xs sm:text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors flex items-center justify-center"
                       onclick="this.innerHTML='<i class=\'fas fa-spinner fa-spin mr-1\'></i>İndiriliyor...'; this.style.pointerEvents='none';">
                        <i class="fas fa-file-excel mr-1"></i>
                        <span class="hidden sm:inline">EXCEL</span>
                        <span class="sm:hidden">XLS</span>
                    </a>
                    <a href="{{ route('admin.reports.export-pdf') }}?{{ http_build_query(request()->all()) }}"
                       class="px-2 sm:px-3 py-2 rounded-lg text-xs sm:text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors flex items-center justify-center"
                       onclick="this.innerHTML='<i class=\'fas fa-spinner fa-spin mr-1\'></i>İndiriliyor...'; this.style.pointerEvents='none';">
                        <i class="fas fa-file-pdf mr-1"></i>
                        <span class="hidden sm:inline">PDF</span>
                        <span class="sm:hidden">PDF</span>
                    </a>
                </div>
            </div>
        </form>
    </div>


    @if($type === 'payments' && isset($data['payments']))
        <!-- Payment Report -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-credit-card mr-2 text-green-500"></i>
                        Ödeme Raporu
                    </h3>
                    <div class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}
                    </div>
                </div>
            </div>

            <!-- Özet Kartları -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600">{{ count($data['monthly_payments']) }}</p>
                        <p class="text-sm text-gray-600">Toplam Üye</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-2xl font-bold text-green-600">{{ number_format($data['summary']['total_count']) }}</p>
                        <p class="text-sm text-gray-600">Toplam İşlem</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($data['summary']['total_amount'], 2) }} €</p>
                        <p class="text-sm text-gray-600">Toplam Tutar</p>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-lg">
                        <p class="text-2xl font-bold text-orange-600">{{ count($data['months']) }}</p>
                        <p class="text-sm text-gray-600">Rapor Dönemi (Ay)</p>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span class="text-sm font-medium">Detaylı üye listesi için PDF/Excel raporunu indirin</span>
                    </div>
                </div>
            </div>

            <!-- Üye Ödeme Durumu Tablosu -->
            @if(request()->hasAny(['start_date', 'end_date', 'type', 'payment_method']))
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Üye</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aylık Aidat</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ocak</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Şubat</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Mart</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nisan</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Mayıs</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Haziran</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Temmuz</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ağustos</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Eylül</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ekim</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kasım</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aralık</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($data['monthly_payments'] as $index => $memberData)
                        <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">{{ $memberData['member']->surname }}, {{ $memberData['member']->name }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($memberData['member']->monthly_dues, 2) }} €</td>
                            @for($month = 1; $month <= 12; $month++)
                                @php
                                    // Seçilen tarih aralığındaki yıl için kontrol et
                                    $startYear = \Carbon\Carbon::parse($startDate)->year;
                                    $endYear = \Carbon\Carbon::parse($endDate)->year;
                                    $isPaid = false;

                                    // Aralıktaki her yıl için kontrol et
                                    for ($year = $startYear; $year <= $endYear; $year++) {
                                        $monthKey = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                                        if (isset($memberData['monthly_data'][$monthKey]) && $memberData['monthly_data'][$monthKey]) {
                                            $isPaid = true;
                                            break;
                                        }
                                    }
                                @endphp
                                <td class="px-3 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    @if($isPaid)
                                        <span class="text-green-600 font-bold">✓</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    @elseif($type === 'members' && isset($data['members']))
        <!-- Members Report -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-users mr-2 text-blue-500"></i>
                        Üye Raporu
                    </h3>
                    <div class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}
                    </div>
                </div>

                @if(request()->hasAny(['start_date', 'end_date', 'type', 'payment_method', 'address_filter']))
                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                            <div>
                                <p class="text-blue-800 font-medium text-sm">Filtreleme tamamlandı!</p>
                                <p class="text-blue-600 text-xs mt-1">PDF ya da Excel CSV raporunuzu alabilirsiniz.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Summary -->
            <div class="p-6 border-b border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($data['summary']['total_new_members']) }}</p>
                        <p class="text-sm text-gray-600">Yeni Üye</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-2xl font-bold text-green-600">{{ number_format($data['summary']['active_members']) }}</p>
                        <p class="text-sm text-gray-600">Aktif Üye</p>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <p class="text-2xl font-bold text-red-600">{{ number_format($data['summary']['inactive_members']) }}</p>
                        <p class="text-sm text-gray-600">Pasif Üye</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($data['summary']['total_monthly_revenue'], 2) }} €</p>
                        <p class="text-sm text-gray-600">Toplam Aylık Gelir</p>
                    </div>
                </div>
            </div>

            <!-- Üye tablosu kaldırıldı - Sadece özet kartları gösteriliyor -->
        </div>

    @elseif($type === 'manual')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-list-check mr-2 text-gray-500"></i>
                        Elden Takip (Boş Şablon)
                    </h3>
                    <div class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}
                    </div>
                </div>
            </div>
            <div class="p-6 text-sm text-gray-600">
                PDF çıktısı için üstteki PDF İndir butonunu kullanın. Bu görünüm bilgi amaçlıdır.
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const paymentMethodDiv = document.getElementById('payment_method').closest('div');

    function togglePaymentMethodFilter() {
        const addressFilterDiv = document.getElementById('addressFilterWrapper');
        const phoneFilterDiv = document.getElementById('phoneFilterWrapper');
        const startWrap = document.getElementById('startDateWrapper');
        const endWrap = document.getElementById('endDateWrapper');

        if (typeSelect.value === 'payments') {
            paymentMethodDiv.style.display = 'block';
            addressFilterDiv.style.display = 'none';
            phoneFilterDiv.style.display = 'none';
            startWrap.style.display = 'block';
            endWrap.style.display = 'block';
        } else if (typeSelect.value === 'members') {
            paymentMethodDiv.style.display = 'none';
            addressFilterDiv.style.display = 'block';
            phoneFilterDiv.style.display = 'block';
            startWrap.style.display = 'block';
            endWrap.style.display = 'block';
        } else if (typeSelect.value === 'manual') {
            paymentMethodDiv.style.display = 'block';
            addressFilterDiv.style.display = 'none';
            phoneFilterDiv.style.display = 'none';
            startWrap.style.display = 'none';
            endWrap.style.display = 'none';
        }
    }

    // İlk yüklemede kontrol et
    togglePaymentMethodFilter();

    // Rapor türü değiştiğinde kontrol et
    typeSelect.addEventListener('change', togglePaymentMethodFilter);

    // Filtreleme butonu loading
    const filterButton = document.getElementById('filterButton');
    const filterIcon = document.getElementById('filterIcon');
    const filterText = document.getElementById('filterText');

    if (filterButton) {
        filterButton.addEventListener('click', function() {
            // Loading durumunu göster
            filterIcon.className = 'fas fa-spinner fa-spin mr-1';
            filterText.textContent = 'Filtreleniyor...';
            filterButton.disabled = true;
            filterButton.classList.add('opacity-75', 'cursor-not-allowed');

            // Form submit edilsin
            setTimeout(() => {
                filterButton.closest('form').submit();
            }, 100);
        });
    }
});
</script>
@endsection
