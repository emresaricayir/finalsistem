@extends('admin.layouts.app')

@section('title', 'Gecikmiş Aidatlar')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                Gecikmiş Aidatlar
            </h1>
            <p class="text-gray-600 mt-1">Vadesi geçmiş ve ödenmemiş aidatlar</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="showBulkReminderModal()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-envelope mr-2"></i>
                Toplu Hatırlatma
            </button>
            <a href="{{ route('admin.email-logs.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-envelope-open-text mr-2"></i>
                E-posta Kayıtları
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
        <form method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Ödeme Yöntemi</label>
                <select name="payment_method" id="payment_method" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                    <option value="">Tümü</option>
                    @foreach($paymentMethods as $value => $label)
                        <option value="{{ $value }}" {{ request('payment_method') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Yıl</label>
                <select name="year" id="year" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                    <option value="">Tümü</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Ay</label>
                <select name="month" id="month" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                    <option value="">Tümü</option>
                    @foreach($months as $key => $month)
                        <option value="{{ $key }}" {{ request('month') == $key ? 'selected' : '' }}>
                            {{ $month }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-4 border border-red-100">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Gecikmiş Aidat Sayısı</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['total_overdue']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-red-100">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-users text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Gecikmesi Olan Üye Sayısı</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['distinct_members']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-red-100">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-euro-sign text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Toplam Gecikmiş Tutar</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['total_amount'], 2) }} €</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Dues Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">

        <div class="overflow-x-auto">
            <div class="min-w-full">
                @forelse($overdueDues as $memberData)
                    <!-- Üye Satırı - Tıklanabilir -->
                    <div class="border-b border-gray-200 bg-white hover:bg-red-50 transition-colors">
                        <!-- Ana Satır -->
                        <div class="px-6 py-4 cursor-pointer" onclick="toggleMemberDues({{ $memberData['member']->id }})">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 flex-1">
                                    <!-- Expand Icon -->
                                    <div class="flex-shrink-0">
                                        <i id="icon-{{ $memberData['member']->id }}" class="fas fa-chevron-right text-gray-400 transition-transform duration-200"></i>
                                    </div>

                                    <!-- Üye Bilgileri -->
                                    <div class="flex-1 min-w-0">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $memberData['member']->surname }} {{ $memberData['member']->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 truncate">{{ $memberData['member']->email }}</p>
                                        </div>
                                    </div>

                                    <!-- İstatistikler -->
                                    <div class="hidden md:flex items-center space-x-6">
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500">Gecikmiş Aidat</p>
                                            <p class="text-lg font-bold text-red-600">{{ $memberData['dues_count'] }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500">Toplam Borç</p>
                                            <p class="text-lg font-bold text-red-600">{{ number_format($memberData['total_amount'], 2) }} €</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500">En Eski</p>
                                            <p class="text-xs font-medium text-gray-700">{{ \Carbon\Carbon::parse($memberData['oldest_due_date'])->format('d.m.Y') }}</p>
                                        </div>
                                    </div>

                                    <!-- İşlemler -->
                                    <div class="flex items-center space-x-2" onclick="event.stopPropagation()">
                                        <button onclick="showReminderModal({{ $memberData['member']->id }}, '{{ $memberData['member']->name }} {{ $memberData['member']->surname }}')"
                                                class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Hatırlatma Gönder">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobil İstatistikler -->
                            <div class="md:hidden mt-3 flex items-center justify-around text-center">
                                <div>
                                    <p class="text-xs text-gray-500">Aidat</p>
                                    <p class="text-sm font-bold text-red-600">{{ $memberData['dues_count'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Toplam</p>
                                    <p class="text-sm font-bold text-red-600">{{ number_format($memberData['total_amount'], 2) }} €</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">En Eski</p>
                                    <p class="text-xs font-medium text-gray-700">{{ \Carbon\Carbon::parse($memberData['oldest_due_date'])->format('d.m.Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Detay Tablosu (Gizli) -->
                        <div id="dues-{{ $memberData['member']->id }}" class="hidden bg-gray-50">
                            <div class="px-6 py-4">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Dönem</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Tutar</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Vade Tarihi</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Gecikme</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">İşlem</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($memberData['dues'] as $due)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 text-sm text-gray-900">{{ $due->month_name }} {{ $due->year }}</td>
                                                <td class="px-4 py-3 text-sm font-medium text-red-600">{{ number_format($due->amount, 2) }} €</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $due->due_date->format('d.m.Y') }}</td>
                                                <td class="px-4 py-3 text-sm text-red-600 font-medium">{{ $due->due_date->diffForHumansTr() }}</td>
                                                <td class="px-4 py-3 text-sm">
                                                    <a href="{{ route('admin.payments.create', ['due_id' => $due->id]) }}"
                                                       class="inline-flex items-center px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded-md text-xs font-medium transition-colors">
                                                        <i class="fas fa-credit-card mr-1"></i>
                                                        Ödeme Al
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center bg-white">
                        <div class="text-gray-500">
                            <i class="fas fa-check-circle text-green-400 text-4xl mb-4"></i>
                            <p class="text-lg font-medium">Gecikmiş aidat bulunmuyor!</p>
                            <p class="text-sm">Tüm aidatlar zamanında ödenmiş.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($overdueDues->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="text-sm text-gray-700">
                            Toplam <span class="font-semibold text-gray-900">{{ $overdueDues->total() }}</span> üyeden
                            <span class="font-semibold text-red-600">{{ $overdueDues->firstItem() }}-{{ $overdueDues->lastItem() }}</span> arası gösteriliyor
                        </div>
                        <div class="hidden sm:block h-6 w-px bg-gray-300"></div>
                        <div class="px-3 py-1 bg-red-600 text-white rounded-lg font-semibold text-sm shadow-md">
                            Sayfa {{ $overdueDues->currentPage() }} / {{ $overdueDues->lastPage() }}
                        </div>
                    </div>
                    <div class="flex items-center space-x-1">
                        {{-- First Page --}}
                        @if($overdueDues->onFirstPage())
                            <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-angle-double-left"></i>
                            </span>
                        @else
                            <a href="{{ $overdueDues->appends(request()->query())->url(1) }}" class="px-3 py-2 text-gray-700 bg-white hover:bg-red-50 border border-gray-300 rounded-lg transition-colors duration-200">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        @endif

                        {{-- Previous Page --}}
                        @if($overdueDues->onFirstPage())
                            <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $overdueDues->appends(request()->query())->previousPageUrl() }}" class="px-3 py-2 text-gray-700 bg-white hover:bg-red-50 border border-gray-300 rounded-lg transition-colors duration-200">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @php
                            $start = max($overdueDues->currentPage() - 2, 1);
                            $end = min($start + 4, $overdueDues->lastPage());
                            $start = max($end - 4, 1);
                        @endphp

                        @for($page = $start; $page <= $end; $page++)
                            @if($page == $overdueDues->currentPage())
                                <span class="px-4 py-2 bg-red-600 text-white font-bold rounded-lg shadow-lg transform scale-110 border-2 border-red-700">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $overdueDues->appends(request()->query())->url($page) }}" class="px-4 py-2 text-gray-700 bg-white hover:bg-red-50 border border-gray-300 rounded-lg transition-all duration-200 hover:shadow-md">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        {{-- Next Page --}}
                        @if($overdueDues->hasMorePages())
                            <a href="{{ $overdueDues->appends(request()->query())->nextPageUrl() }}" class="px-3 py-2 text-gray-700 bg-white hover:bg-red-50 border border-gray-300 rounded-lg transition-colors duration-200">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif

                        {{-- Last Page --}}
                        @if($overdueDues->currentPage() == $overdueDues->lastPage())
                            <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-angle-double-right"></i>
                            </span>
                        @else
                            <a href="{{ $overdueDues->appends(request()->query())->url($overdueDues->lastPage()) }}" class="px-3 py-2 text-gray-700 bg-white hover:bg-red-50 border border-gray-300 rounded-lg transition-colors duration-200">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Individual Reminder Modal -->
<div id="reminderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Aidat Hatırlatması Gönder</h3>
                <button onclick="closeReminderModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="reminderForm" method="POST">
                @csrf
                <div class="mb-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-blue-800 mb-1">
                                    <span id="memberName" class="font-semibold"></span>
                                </p>
                                <p class="text-xs text-blue-600">
                                    Bu üyenin tüm gecikmiş aidatları için hatırlatma e-postası gönderilecektir.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeReminderModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        İptal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                        <i class="fas fa-envelope mr-2"></i>
                        Hatırlatma Gönder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Bulk Reminder Modal -->
<div id="bulkReminderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Toplu Aidat Hatırlatması</h3>
                <button onclick="closeBulkReminderModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="bulkReminderForm" method="POST" action="{{ route('admin.members.send-bulk-reminders') }}">
                @csrf
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-4">
                        Gecikmiş aidatı olan tüm üyelere otomatik hatırlatma maili gönderilecektir.
                    </p>
                </div>

                <div class="mb-4">
                    <label for="bulkMonths" class="block text-sm font-medium text-gray-700 mb-2">
                        Kaç Aylık Hatırlatma?
                    </label>
                    <select id="bulkMonths" name="months" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">1 Ay</option>
                        <option value="2">2 Ay</option>
                        <option value="3" selected>3 Ay</option>
                        <option value="4">4 Ay</option>
                        <option value="5">5 Ay</option>
                        <option value="6">6 Ay</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeBulkReminderModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        İptal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                        <i class="fas fa-envelope mr-2"></i>
                        Toplu Hatırlatma Gönder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle member dues accordion
function toggleMemberDues(memberId) {
    const duesSection = document.getElementById('dues-' + memberId);
    const icon = document.getElementById('icon-' + memberId);

    if (duesSection.classList.contains('hidden')) {
        // Açıyoruz
        duesSection.classList.remove('hidden');
        icon.classList.remove('fa-chevron-right');
        icon.classList.add('fa-chevron-down');
        icon.classList.add('text-red-600');
        icon.classList.remove('text-gray-400');
    } else {
        // Kapatıyoruz
        duesSection.classList.add('hidden');
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-right');
        icon.classList.remove('text-red-600');
        icon.classList.add('text-gray-400');
    }
}

// Bulk selection functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAllOverdue');
    const dueCheckboxes = document.querySelectorAll('.due-checkbox');

    // Select all functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            dueCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Update select all when individual checkboxes change
    dueCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.due-checkbox:checked').length;
            const totalCount = dueCheckboxes.length;

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checkedCount === totalCount;
                selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
            }
        });
    });
});


function showReminderModal(memberId, memberName) {
    document.getElementById('memberName').textContent = memberName;
    document.getElementById('reminderForm').action = `/admin/members/${memberId}/send-reminder`;
    document.getElementById('reminderModal').classList.remove('hidden');
}

function closeReminderModal() {
    document.getElementById('reminderModal').classList.add('hidden');
}

function showBulkReminderModal() {
    document.getElementById('bulkReminderModal').classList.remove('hidden');
}

function closeBulkReminderModal() {
    document.getElementById('bulkReminderModal').classList.add('hidden');
}

// Close modals when clicking outside
document.getElementById('reminderModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeReminderModal();
    }
});

document.getElementById('bulkReminderModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBulkReminderModal();
    }
});

// WhatsApp: open chats sequentially for selected unique members
function sendSelectedWhatsApp() {
    const selected = Array.from(document.querySelectorAll('.due-checkbox:checked'));
    if (selected.length === 0) {
        alert('Lütfen WhatsApp göndermek için satır seçin.');
        return;
    }

    // Group by member to avoid duplicates
    const byMember = new Map();
    selected.forEach(cb => {
        const id = cb.dataset.memberId;
        if (!byMember.has(id)) {
            byMember.set(id, { phone: cb.dataset.phone, name: cb.dataset.name, items: [] });
        }
        byMember.get(id).items.push({ month: cb.dataset.month, amount: cb.dataset.amount });
    });

    const entries = Array.from(byMember.values());
    if (!confirm(`${entries.length} üyeye WhatsApp sohbeti açılacak. Devam edilsin mi?`)) return;

    let i = 0;
    function openNext() {
        if (i >= entries.length) return;
        const { phone, name, items } = entries[i++];
        if (!phone) { openNext(); return; }

        const normalized = String(phone).replace(/\D/g, '');
        const lines = items.slice(0, 3).map(it => `- ${it.month}: ${it.amount} €`).join('%0A');
        const more = items.length > 3 ? `%0A... ve ${items.length - 3} dönem daha` : '';
        const msg = `Merhaba ${name},%0Aaidat bilginiz:%0A${lines}${more}%0A%0AÖdeme için cami yönetimiyle iletişime geçebilirsiniz. Teşekkürler.`;

        window.open(`https://wa.me/${normalized}?text=${msg}`, '_blank');
        setTimeout(openNext, 900);
    }

    openNext();
}

// Tüm sayfalardan seçilen ID'leri tutacak değişken
let allSelectedDueIds = null;

// Tüm sayfaları seçme fonksiyonu
document.getElementById('selectAllPages').addEventListener('click', function() {
    if (confirm('Tüm sayfalardaki gecikmiş aidatları seçmek istediğinizden emin misiniz?')) {
        // Backend'e istek gönder
        selectAllPagesFromBackend();
    }
});

function selectAllPagesFromBackend() {
    // Mevcut filtreleri al
    const urlParams = new URLSearchParams(window.location.search);
    const formData = new FormData();

    // Filtreleri form data'ya ekle
    if (urlParams.get('payment_method')) formData.append('payment_method', urlParams.get('payment_method'));
    if (urlParams.get('year')) formData.append('year', urlParams.get('year'));
    if (urlParams.get('month')) formData.append('month', urlParams.get('month'));

    // CSRF token ekle
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    // Backend'e istek gönder
    fetch('{{ route("admin.dues.select-all-pages") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Tüm ID'leri sakla
            allSelectedDueIds = data.due_ids;

            // Mevcut sayfadaki tüm checkbox'ları seç
            const checkboxes = document.querySelectorAll('input[name="selected_dues[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });

            // Tüm sayfaları seç butonunu güncelle
            const selectAllBtn = document.getElementById('selectAllPages');
            selectAllBtn.disabled = true;
            selectAllBtn.innerHTML = '<i class="fas fa-check-double mr-2"></i>Tüm Sayfalar Seçildi (' + data.total_count + ')';
            selectAllBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            selectAllBtn.classList.add('bg-green-600', 'cursor-not-allowed');

            // Bu sayfadaki tümünü seç checkbox'ını da işaretle
            document.getElementById('selectAllOverdue').checked = true;
            if (document.getElementById('selectAllOverdueTable')) {
                document.getElementById('selectAllOverdueTable').checked = true;
            }

            // Başarı mesajı göster
            alert(data.message);
        } else {
            alert('Hata: ' + (data.message || 'Bilinmeyen hata'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Bir hata oluştu. Lütfen tekrar deneyin.');
    });
}

</script>
@endsection
