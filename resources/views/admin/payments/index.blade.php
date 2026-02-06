@extends('admin.layouts.app')

@section('title', 'Ödemeler')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">
                <i class="fas fa-credit-card mr-2 text-blue-500"></i>
                Ödemeler
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-600">Tüm Ödenmiş Aidatları görüntüleyin ve düzenleyin.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.donation-certificates.index') }}" class="inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded-md transition-colors">
                <i class="fas fa-file-invoice mr-1.5 text-xs"></i>
                <span class="hidden sm:inline">Spendenbescheinigung</span>
                <span class="sm:hidden">Spendenbescheinigung</span>
            </a>
            <button onclick="openPaymentReport()" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors">
                <i class="fas fa-chart-bar mr-1.5 text-xs"></i>
                <span class="hidden sm:inline">Ödeme Raporu</span>
                <span class="sm:hidden">Rapor</span>
            </button>
        </div>
    </div>

    <!-- Compact Stats and Filters -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <!-- Compact Stats Row -->
        <div class="grid grid-cols-3 gap-px bg-gray-200 border-b border-gray-200">
            <div class="bg-white p-3 text-center">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <i class="fas fa-calendar text-blue-500 text-xs"></i>
                    <p class="text-xs text-gray-600 font-medium">Seçilen Ay</p>
                </div>
                <p class="text-lg font-bold text-blue-600">{{ \Carbon\Carbon::createFromDate((int)request('year', now()->year), (int)request('month', now()->month), 1)->locale('tr')->isoFormat('MMMM YYYY') }}</p>
            </div>
            <div class="bg-white p-3 text-center">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <i class="fas fa-users text-green-500 text-xs"></i>
                    <p class="text-xs text-gray-600 font-medium">Ödeme Yapan</p>
                </div>
                <p class="text-lg font-bold text-green-600">{{ $stats['unique_members'] }}</p>
            </div>
            <div class="bg-white p-3 text-center">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <i class="fas fa-euro-sign text-purple-500 text-xs"></i>
                    <p class="text-xs text-gray-600 font-medium">Toplam Tutar</p>
                </div>
                <p class="text-lg font-bold text-purple-600">€{{ number_format($stats['total_amount'], 2) }}</p>
            </div>
        </div>

        <!-- Compact Filters Row -->
        <form method="GET" action="{{ route('admin.payments.index') }}" class="p-3 bg-gray-50">
            <div class="flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-calendar text-blue-500 text-xs mr-1"></i>Yıl
                    </label>
                    <select name="year" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        @for($y = now()->year; $y >= now()->year - 1; $y--)
                            <option value="{{ $y }}" {{ (int)request('year', now()->year) === $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-calendar-alt text-green-500 text-xs mr-1"></i>Ay
                    </label>
                    <select name="month" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ (int)request('month', now()->month) === $m ? 'selected' : '' }}>{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-credit-card text-purple-500 text-xs mr-1"></i>Ödeme Yöntemi
                    </label>
                    <select name="payment_method" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                        <option value="">Tümü</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Nakit</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Banka Transferi</option>
                        <option value="lastschrift_monthly" {{ request('payment_method') == 'lastschrift_monthly' ? 'selected' : '' }}>Lastschrift (Aylık)</option>
                        <option value="lastschrift_semi_annual" {{ request('payment_method') == 'lastschrift_semi_annual' ? 'selected' : '' }}>Lastschrift (6 Aylık)</option>
                        <option value="lastschrift_annual" {{ request('payment_method') == 'lastschrift_annual' ? 'selected' : '' }}>Lastschrift (Yıllık)</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-search text-purple-500 text-xs mr-1"></i>Üye Arama
                    </label>
                    <div class="relative">
                        <input type="text" id="memberSearch" placeholder="Üye ara..."
                               value="{{ request('search', '') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 pl-8 text-xs focus:ring-1 focus:ring-purple-500 focus:border-purple-500 bg-white">
                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded-md transition-colors">
                        <i class="fas fa-times mr-1"></i>
                        Temizle
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Receipt Modal -->
    <div id="bulkReceiptModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden">
        <div class="absolute inset-0 flex items-center justify-center p-4" onclick="closeBulkReceiptModal()">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden" onclick="event.stopPropagation()">
                <!-- Header -->
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-5 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-white/20 p-3 rounded-xl mr-4">
                                <i class="fas fa-file-invoice text-2xl"></i>
                            </div>
                            <div>Spendenbescheinigung Oluştur </h3>
                                <p class="text-purple-100 text-sm">Üye ödemeleri için toplu makbuz</p>
                            </div>
                        </div>
                        <button class="text-white/80 hover:text-white hover:bg-white/20 p-2 rounded-lg transition-all duration-200" onclick="closeBulkReceiptModal()">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-6">
                    <!-- Member Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-purple-600"></i>
                            Üye Seçimi
                        </label>
                        <div class="space-y-3">
                            <div class="relative">
                                <input type="text" id="memberSearch" placeholder="Üye ara..."
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 pl-12 bg-gray-50 focus:bg-white text-gray-800" />
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                            <div class="border-2 border-gray-200 rounded-xl bg-white shadow-sm max-h-48 overflow-y-auto">
                                <select id="bulkMember" size="6" class="w-full border-0 focus:ring-0 focus:outline-none text-gray-800">
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" class="px-4 py-3 hover:bg-purple-50 cursor-pointer border-b border-gray-100 last:border-b-0">{{ $member->surname }} {{ $member->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 flex items-center">
                            <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                            Seçilen üyeye ait ödemelerden makbuz oluşturulur
                        </p>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-purple-600"></i>
                            Tarih Aralığı (Opsiyonel)
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-2">Başlangıç Tarihi</label>
                                <input type="date" id="bulkFrom" lang="tr"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white text-gray-800">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-2">Bitiş Tarihi</label>
                                <input type="date" id="bulkTo" lang="tr"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white text-gray-800">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                    <button class="px-6 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium transition-all duration-200 flex items-center" onclick="closeBulkReceiptModal()">
                        <i class="fas fa-times mr-2"></i>
                        İptal
                    </button>
                    <a id="bulkReceiptGo" href="#" class="px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-medium transition-all duration-200 flex items-center shadow-lg hover:shadow-xl">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Oluştur
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openBulkReceiptModal() {
            document.getElementById('bulkReceiptModal').classList.remove('hidden');
        }
        function closeBulkReceiptModal() {
            document.getElementById('bulkReceiptModal').classList.add('hidden');
        }

        // Check if member has payments
        async function checkMemberPayments(memberId) {
            try {
                const response = await fetch(`/admin/payments/check-payments/${memberId}`);
                const data = await response.json();
                return data.hasPayments;
            } catch (error) {
                console.error('Error checking payments:', error);
                return true; // Allow if check fails
            }
        }

        // Check if a donation certificate already exists for this member / date range
        async function checkExistingCertificate(memberId, dateFrom, dateTo) {
            try {
                const params = new URLSearchParams();
                params.set('member_id', memberId);
                if (dateFrom) params.set('date_from', dateFrom);
                if (dateTo) params.set('date_to', dateTo);

                const response = await fetch(`{{ route('admin.payments.check-certificate') }}?` + params.toString());
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error checking existing certificate:', error);
                return { hasCertificate: false };
            }
        }

        // Show payment warning
        function showPaymentWarning() {
            // Remove existing warning if any
            const existingWarning = document.getElementById('paymentWarning');
            if (existingWarning) {
                existingWarning.remove();
            }

            // Create warning message
            const warning = document.createElement('div');
            warning.id = 'paymentWarning';
            warning.className = 'mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg';
            warning.innerHTML = `
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2 mt-0.5"></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold">Uyarı:</p>
                        <p>Seçtiğiniz üyenin ödenmiş aidatı yoktur. Spendenbescheinigung oluşturulamaz.</p>
                    </div>
                </div>
            `;

            // Insert warning after member selection
            const memberSelection = document.querySelector('#bulkMember').closest('div');
            memberSelection.parentNode.insertBefore(warning, memberSelection.nextSibling);
        }

        // Show certificate warning (if already exists)
        function showCertificateWarning(info) {
            const existingWarning = document.getElementById('certificateWarning');
            if (existingWarning) {
                existingWarning.remove();
            }

            const warning = document.createElement('div');
            warning.id = 'certificateWarning';
            warning.className = 'mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg';
            const rangeText = (() => {
                if (info && (info.date_from || info.date_to)) {
                    const from = info.date_from ? info.date_from : '---';
                    const to = info.date_to ? info.date_to : '---';
                    return ` (${from} - ${to})`;
                }
                return '';
            })();

            warning.innerHTML = `
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mr-2 mt-0.5"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold">Bilgi:</p>
                        <p>Bu üye için daha önce bir Spendenbescheinigung oluşturulmuş${rangeText}. Yine de devam ederseniz yeni bir belge daha oluşturulacak.</p>
                    </div>
                </div>
            `;

            const memberSelection = document.querySelector('#bulkMember').closest('div');
            memberSelection.parentNode.insertBefore(warning, memberSelection.nextSibling);
        }

        // Turkish character normalization function
        function normalizeTurkishText(text) {
            return text.toLowerCase()
                .replace(/ı/g, 'i')
                .replace(/i/g, 'ı')
                .replace(/ş/g, 's')
                .replace(/s/g, 'ş')
                .replace(/ğ/g, 'g')
                .replace(/g/g, 'ğ')
                .replace(/ü/g, 'u')
                .replace(/u/g, 'ü')
                .replace(/ö/g, 'o')
                .replace(/o/g, 'ö')
                .replace(/ç/g, 'c')
                .replace(/c/g, 'ç');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const member = document.getElementById('bulkMember');
            const from = document.getElementById('bulkFrom');
            const to = document.getElementById('bulkTo');
            const go = document.getElementById('bulkReceiptGo');
            const search = document.getElementById('memberSearch');

            function updateLink() {
                if (!member.value) {
                    go.href = '#';
                    go.setAttribute('disabled', 'disabled');
                    return;
                }

                // Check if member has payments before allowing receipt generation
                checkMemberPayments(member.value).then(hasPayments => {
                    if (!hasPayments) {
                        showPaymentWarning();
                        go.href = '#';
                        go.setAttribute('disabled', 'disabled');
                        return;
                    }

                    const params = new URLSearchParams();
                    params.set('member_id', member.value);
                    if (from.value) params.set('date_from', from.value);
                    if (to.value) params.set('date_to', to.value);

                    // Önce var olan belgeyi kontrol et, varsa uyarı göster, yine de linki aktif bırak
                    checkExistingCertificate(member.value, from.value, to.value).then(result => {
                        if (result && result.hasCertificate) {
                            showCertificateWarning(result.certificate);
                        } else {
                            const existingWarning = document.getElementById('certificateWarning');
                            if (existingWarning) {
                                existingWarning.remove();
                            }
                        }

                        go.removeAttribute('disabled');
                        go.href = `{{ route('admin.payments.bulk-receipt') }}?` + params.toString();
                    });
                });
            }
            ['change','input'].forEach(evt => {
                member.addEventListener(evt, updateLink);
                from.addEventListener(evt, updateLink);
                to.addEventListener(evt, updateLink);
            });

            // Clear warning when member changes
            member.addEventListener('change', function() {
                const existingWarning = document.getElementById('paymentWarning');
                if (existingWarning) {
                    existingWarning.remove();
                }
            });
            updateLink();

            // Enhanced client-side filter with Turkish character support
            if (search) {
                search.addEventListener('input', function() {
                    const query = this.value.toLowerCase();
                    const normalizedQuery = normalizeTurkishText(query);

                    Array.from(member.options).forEach(opt => {
                        const normalizedText = normalizeTurkishText(opt.text.toLowerCase());
                        opt.hidden = normalizedQuery && !normalizedText.includes(normalizedQuery);
                    });
                });
            }

            // URL'de open_bulk_receipt=1 varsa modalı otomatik aç
            const params = new URLSearchParams(window.location.search);
            if (params.get('open_bulk_receipt') === '1') {
                openBulkReceiptModal();
            }
        });

        // Otomatik filtreleme - Seçim değişince form otomatik submit olsun
        document.addEventListener('DOMContentLoaded', function() {
            // Yıl seçimi değişince
            const yearSelect = document.querySelector('select[name="year"]');
            if (yearSelect) {
                yearSelect.addEventListener('change', function() {
                    this.form.submit();
                });
            }

            // Ay seçimi değişince
            const monthSelect = document.querySelector('select[name="month"]');
            if (monthSelect) {
                monthSelect.addEventListener('change', function() {
                    this.form.submit();
                });
            }

            // Ödeme yöntemi seçimi değişince
            const paymentMethodSelect = document.querySelector('select[name="payment_method"]');
            if (paymentMethodSelect) {
                paymentMethodSelect.addEventListener('change', function() {
                    this.form.submit();
                });
            }
        });
    </script>

    <!-- Payments Table -->
    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100">
        <!-- Bulk Actions -->
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <input type="checkbox" id="selectAllPayments" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="selectAllPayments" class="text-sm font-medium text-gray-700">Bu sayfadaki tümünü seç</label>
                </div>
                <div class="flex items-center space-x-2">
                    <span id="selectedCount" class="text-sm text-gray-600">0 ödeme seçildi</span>
                    <button type="button" onclick="clearAllSelections()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Seçilen Üyeleri Temizle
                    </button>
                    <button type="button" id="bulkDeleteBtn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-trash mr-2"></i>
                        Seçilen Aidatları Sil
                    </button>
                </div>
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                            <input type="checkbox" id="selectAllPaymentsTable" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Üye
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tutar
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ödeme Yöntemi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ödeme Tarihi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aidat Dönemi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kaydeden
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            İşlemler
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-blue-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="selected_payments[]" value="{{ $payment->id }}" class="payment-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $payment->member->surname }} {{ $payment->member->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $payment->member->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ number_format($payment->amount, 2) }} €
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $payment->payment_method_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $payment->payment_date->format('d.m.Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">
                                    {{ $payment->due_period }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $payment->recordedBy ? $payment->recordedBy->name : 'Silinmiş Kullanıcı' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-100 transition-colors duration-200" title="Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="inline" onsubmit="return confirm('Bu ödemeyi silmek istediğinizden emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-100 transition-colors duration-200" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-400 text-6xl mb-4">💰</div>
                                <div class="text-gray-500 text-lg font-medium mb-2">Ödeme Bulunamadı</div>
                                <div class="text-gray-400 text-sm">Henüz hiç ödeme kaydı bulunmamaktadır.</div>
                                <div class="mt-4">
                                    <a href="{{ route('admin.payments.create') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        <i class="fas fa-plus mr-2"></i>
                                        İlk Ödemeyi Oluştur
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden">
            @forelse($payments as $payment)
                <div class="border-b border-gray-200 p-4 hover:bg-blue-50 transition-colors duration-200">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-start space-x-3 flex-1">
                            <input type="checkbox" name="selected_payments[]" value="{{ $payment->id }}" class="payment-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1">
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-900 mb-1">
                                    {{ $payment->member->surname }} {{ $payment->member->name }}
                                </h3>
                                <p class="text-xs text-gray-500 mb-2">{{ $payment->member->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-gray-900">
                                {{ number_format($payment->amount, 2) }} €
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $payment->payment_date->format('d.m.Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $payment->payment_method_text }}
                        </span>
                        <span class="text-xs text-gray-500 font-semibold">
                            Dönem: {{ $payment->due_period }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="text-xs text-gray-500">
                            Kaydeden: {{ $payment->recordedBy ? $payment->recordedBy->name : 'Silinmiş Kullanıcı' }}
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-100 transition-colors duration-200" title="Görüntüle">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="inline" onsubmit="return confirm('Bu ödemeyi silmek istediğinizden emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-100 transition-colors duration-200" title="Sil">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="text-gray-400 text-4xl mb-4">💰</div>
                    <div class="text-gray-500 text-base font-medium mb-2">Ödeme Bulunamadı</div>
                    <div class="text-gray-400 text-sm mb-4">Henüz hiç ödeme kaydı bulunmamaktadır.</div>
                    <a href="{{ route('admin.payments.create') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        İlk Ödemeyi Oluştur
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Total Count Info -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="text-center">
                <div class="text-sm text-gray-700">
                    Toplam <span class="font-semibold text-gray-900">{{ $payments->count() }}</span> ödeme gösteriliyor
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div id="bulkDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Toplu Silme Onayı</h3>
                <button onclick="hideBulkDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-4">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        <span class="text-sm text-red-800">
                            <span id="bulkDeleteCount"></span> adet ödemeyi silmek istediğinizden emin misiniz?
                        </span>
                    </div>
                    <p class="text-xs text-red-600 mt-2">
                        Bu işlem geri alınamaz. İlgili aidatlar bekleyen duruma getirilecektir.
                    </p>
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="hideBulkDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    İptal
                </button>
                <button id="confirmBulkDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    Sil
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Toplu seçme ve silme JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAllPayments');
    const selectAllTableCheckbox = document.getElementById('selectAllPaymentsTable');
    const paymentCheckboxes = document.querySelectorAll('tbody .payment-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    // Tümünü seç checkbox'ları
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            paymentCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            if (selectAllTableCheckbox) {
                selectAllTableCheckbox.checked = isChecked;
            }
            updateSelectedCount();
        });
    }

    if (selectAllTableCheckbox) {
        selectAllTableCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            paymentCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = isChecked;
            }
            updateSelectedCount();
        });
    }

    // Bireysel checkbox'lar
    paymentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            updateSelectAllCheckboxes();
        });
    });

    // Toplu silme butonu
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const selectedPayments = getSelectedPayments();
            if (selectedPayments.length === 0) {
                alert('Lütfen silinecek ödemeleri seçin.');
                return;
            }

            // Butonu loading state'e çevir
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>İşleniyor...';
            this.disabled = true;

            // Kısa bir delay sonra modal'ı aç
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
                showBulkDeleteModal(selectedPayments.length);
            }, 100);
        });
    }

    function updateSelectedCount() {
        const selectedCount = getSelectedPayments().length;
        selectedCountSpan.textContent = `${selectedCount} ödeme seçildi`;
        bulkDeleteBtn.disabled = selectedCount === 0;
    }

    function updateSelectAllCheckboxes() {
        const selectedCount = getSelectedPayments().length;
        const totalCount = paymentCheckboxes.length;

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = selectedCount === totalCount;
            selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < totalCount;
        }

        if (selectAllTableCheckbox) {
            selectAllTableCheckbox.checked = selectedCount === totalCount;
            selectAllTableCheckbox.indeterminate = selectedCount > 0 && selectedCount < totalCount;
        }
    }

    function getSelectedPayments() {
        return Array.from(paymentCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);
    }

    function showBulkDeleteModal(count) {
        document.getElementById('bulkDeleteCount').textContent = count;
        document.getElementById('bulkDeleteModal').classList.remove('hidden');
    }

    function hideBulkDeleteModal() {
        document.getElementById('bulkDeleteModal').classList.add('hidden');
    }

    function confirmBulkDelete() {
        console.log('confirmBulkDelete fonksiyonu çağrıldı');

        const selectedPayments = getSelectedPayments();
        if (selectedPayments.length === 0) {
            alert('Lütfen silinecek ödemeleri seçin.');
            return;
        }

        console.log('Seçili ödemeler:', selectedPayments);

        // Modal'ı hemen kapat
        hideBulkDeleteModal();

        // Sayfa üstünde loading göster
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'bulkDeleteLoading';
        loadingDiv.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center';
        loadingDiv.innerHTML = `
            <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                <i class="fas fa-spinner fa-spin text-blue-600 text-xl"></i>
                <span class="text-lg font-medium text-gray-900">Siliniyor...</span>
            </div>
        `;
        document.body.appendChild(loadingDiv);

        // Form oluştur ve hemen gönder
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.payments.bulk-delete") }}';
        form.style.display = 'none';

        // CSRF token ekle
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Seçili ödemeleri ekle
        selectedPayments.forEach(paymentId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'payment_ids[]';
            input.value = paymentId;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        console.log('Form oluşturuldu, gönderiliyor...');
        form.submit();
    }

    // Modal dışına tıklayınca kapat
    document.getElementById('bulkDeleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideBulkDeleteModal();
        }
    });

    // Modal'daki Sil butonuna event listener ekle
    document.getElementById('confirmBulkDeleteBtn').addEventListener('click', function() {
        console.log('Sil butonu tıklandı');
        confirmBulkDelete();
    });

    // İlk yükleme
    updateSelectedCount();

    // Member search functionality - AJAX ile backend'de arama
    let searchTimeout;
    const memberSearchInput = document.getElementById('memberSearch');

    if (memberSearchInput) {
        memberSearchInput.addEventListener('input', function(e) {
            const query = e.target.value.trim();

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 2500);
        });
    }
});

function performSearch(query) {
    // Mevcut URL'yi al
    const currentUrl = new URL(window.location);

    // Arama parametresini ekle/çıkar
    if (query.length > 0) {
        currentUrl.searchParams.set('search', query);
    } else {
        currentUrl.searchParams.delete('search');
    }

    // Sayfayı yeniden yükle
    window.location.href = currentUrl.toString();
}

function clearAllSelections() {
    // Tüm checkbox'ları temizle
    const paymentCheckboxes = document.querySelectorAll('.payment-checkbox');
    paymentCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
    });

    // Ana checkbox'ları da temizle
    const selectAllCheckbox = document.getElementById('selectAllPayments');
    const selectAllTableCheckbox = document.getElementById('selectAllPaymentsTable');

    if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    }

    if (selectAllTableCheckbox) {
        selectAllTableCheckbox.checked = false;
        selectAllTableCheckbox.indeterminate = false;
    }

    // Seçim sayısını güncelle
    updateSelectedCount();
}

// Ödeme Raporu butonuna tıklandığında loading mesajı göster
function openPaymentReport() {
    // SweetAlert2 varsa kullan, yoksa basit alert
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Rapor Hazırlanıyor...',
            html: `
                <div class="text-center">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mb-4"></div>
                    <p class="text-gray-600 mb-2">Üye aidatları işleniyor</p>
                    <p class="text-sm text-gray-500">Lütfen bekleyiniz</p>
                </div>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                setTimeout(() => {
                    window.location.href = '{{ route("admin.reports.detailed") }}';
                }, 2000);
            }
        });
    } else {
        // Basit alert fallback
        alert('Rapor hazırlanıyor...\nÜye aidatları işleniyor\nSabrınız için teşekkürler');
        setTimeout(() => {
            window.location.href = '{{ route("admin.reports.detailed") }}';
        }, 2000);
    }
}
</script>
@endsection
