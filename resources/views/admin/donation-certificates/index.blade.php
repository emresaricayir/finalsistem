@extends('admin.layouts.app')

@section('title', 'Spendenbescheinigungen')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-file-invoice mr-2 text-purple-500"></i>
                Spendenbescheinigungen
            </h1>
            <p class="mt-2 text-gray-600">
                Oluşturduğunuz tüm bağış makbuzlarını burada görebilirsiniz.
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3">
            <button onclick="openBulkReceiptModal()"
               class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-3 rounded-xl flex items-center justify-center font-medium shadow-lg hover:shadow-xl transition-all duration-200 group">
                <div class="bg-white/20 p-1 rounded-lg mr-3 group-hover:bg-white/30 transition-all duration-200">
                    <i class="fas fa-plus text-sm"></i>
                </div>
                <div>
                    <div class="font-semibold">Yeni Spendenbescheinigung Oluştur</div>
                </div>
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Üye</label>
                <select name="member_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm">
                    <option value="">Tümü</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
                            {{ $member->surname }} {{ $member->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Oluşturma Tarihi (Başlangıç)</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Oluşturma Tarihi (Bitiş)</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm">
            </div>
            <div class="flex space-x-2">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrele
                </button>
                @if(request()->hasAny(['member_id','date_from','date_to']))
                    <a href="{{ route('admin.donation-certificates.index') }}"
                       class="inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg font-semibold text-xs text-gray-700 hover:bg-gray-200">
                        Temizle
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-800 flex items-center">
                <i class="fas fa-list mr-2 text-purple-500"></i>
                Oluşturulan Spendenbescheinigungen
            </h2>
            <span class="text-xs text-gray-500">
                Toplam {{ $certificates->total() }} kayıt
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Üye</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tarih Aralığı</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Toplam Tutar</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Oluşturan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Oluşturma Tarihi</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($certificates as $certificate)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($certificate->member)
                                    <div class="font-medium text-gray-900">
                                        {{ $certificate->member->surname }} {{ $certificate->member->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Üye No: {{ $certificate->member->member_no ?? '-' }}
                                    </div>
                                @else
                                    <span class="text-gray-500 italic">Üye silinmiş</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">
                                @php
                                    $from = $certificate->date_from ? \Carbon\Carbon::parse($certificate->date_from)->format('d.m.Y') : null;
                                    $to = $certificate->date_to ? \Carbon\Carbon::parse($certificate->date_to)->format('d.m.Y') : null;
                                @endphp
                                @if($from || $to)
                                    {{ $from ?? '---' }} - {{ $to ?? '---' }}
                                @else
                                    <span class="text-xs text-gray-500">Belirtilmemiş</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-semibold text-gray-900">
                                €{{ number_format($certificate->total_amount, 2) }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">
                                @if($certificate->createdBy)
                                    <div class="font-medium text-gray-900">
                                        {{ $certificate->createdBy->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $certificate->createdBy->email }}
                                    </div>
                                @else
                                    <span class="text-xs text-gray-500">Bilinmiyor</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">
                                {{ optional($certificate->created_at)->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                <form action="{{ route('admin.donation-certificates.destroy', $certificate) }}" method="POST" onsubmit="return confirm('Bu kaydı silmek istediğinize emin misiniz?');" class="inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 border border-red-200 text-xs font-semibold rounded-lg text-red-600 bg-red-50 hover:bg-red-100 hover:border-red-300 transition-colors">
                                        <i class="fas fa-trash-alt mr-1"></i>
                                        Sil
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500 text-sm">
                                Henüz hiç Spendenbescheinigung oluşturulmamış.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($certificates->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $certificates->links() }}
            </div>
        @endif
    </div>
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
                        <div>
                            <h3 class="text-xl font-bold">Spendenbescheinigung Oluştur</h3>
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
    });
</script>
@endsection

