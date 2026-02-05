@extends('admin.layouts.app')

@section('title', 'Ödenmemiş Aidatlar')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-minus mr-2 text-red-500"></i>
                Ödenmemiş Aidatlar
            </h1>
            <p class="mt-2 text-gray-600">Aylık ödeme yapmak isterseniz ödeme yöntemine göre filitrelemeyi unutmayın</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.payments.create') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl flex items-center justify-center font-medium shadow-lg hover:shadow-xl transition-all duration-200 text-sm sm:text-base">
                <i class="fas fa-plus mr-2"></i>
                <span class="hidden sm:inline">Yeni Ödeme</span>
                <span class="sm:hidden">Yeni</span>
            </a>
        </div>
    </div>

    <!-- Compact Stats and Filters -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <!-- Compact Stats Row -->
        <div class="grid grid-cols-4 gap-px bg-gray-200 border-b border-gray-200">
            <div class="bg-white p-3 text-center">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <i class="fas fa-file-invoice text-gray-500 text-xs"></i>
                    <p class="text-xs text-gray-600 font-medium">Toplam</p>
                </div>
                <p class="text-lg font-bold text-gray-900">{{ $allDues->count() }}</p>
            </div>
            <div class="bg-white p-3 text-center">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <i class="fas fa-users text-green-500 text-xs"></i>
                    <p class="text-xs text-gray-600 font-medium">Ödeme Yapan</p>
                </div>
                <p class="text-lg font-bold text-green-600">{{ $allDues->where('status', 'paid')->pluck('member_id')->unique()->count() }}</p>
            </div>
            <div class="bg-white p-3 text-center">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <i class="fas fa-clock text-orange-500 text-xs"></i>
                    <p class="text-xs text-gray-600 font-medium">Bekleyen</p>
                </div>
                <p class="text-lg font-bold text-orange-600">{{ $pendingDues->count() }}</p>
            </div>
            <div class="bg-white p-3 text-center">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xs"></i>
                    <p class="text-xs text-gray-600 font-medium">Gecikmiş</p>
                </div>
                <p class="text-lg font-bold text-red-600">{{ $overdueDues->count() }}</p>
            </div>
        </div>

        <!-- Compact Filters Row -->
        <form method="GET" action="{{ route('admin.monthly-payments') }}" class="p-3 bg-gray-50">
            <div class="flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-calendar text-blue-500 text-xs mr-1"></i>Yıl
                    </label>
                    <select name="year" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        @for($y = now()->year + 1; $y >= now()->year - 1; $y--)
                            <option value="{{ $y }}" {{ $selectedYear === $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-calendar-alt text-green-500 text-xs mr-1"></i>Ay
                    </label>
                    <select name="month" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $selectedMonth === $m ? 'selected' : '' }}>{{ $turkishMonths[$m] }}</option>
                        @endfor
                    </select>
                </div>
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        <i class="fas fa-credit-card text-purple-500 text-xs mr-1"></i>Ödeme Yöntemi
                    </label>
                    <select name="payment_method" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                        <option value="" {{ !$paymentMethodFilter ? 'selected' : '' }}>Tümü</option>
                        @foreach($paymentMethods as $key => $label)
                            <option value="{{ $key }}" {{ $paymentMethodFilter == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('admin.monthly-payments') }}" class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded-md transition-colors">
                        <i class="fas fa-times mr-1"></i>
                        Temizle
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
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

    <!-- Selected Members Display -->
    <div id="selectedMembersSection" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hidden">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-users mr-2 text-blue-500"></i>
                Seçili Üyeler
            </h3>
            <button type="button" onclick="clearSelectedMembers()" class="text-sm text-red-600 hover:text-red-800 font-medium">
                <i class="fas fa-times mr-1"></i>Tümünü Temizle
            </button>
        </div>
        <div id="selectedMembersList" class="flex flex-wrap gap-2">
            <!-- Selected members will be displayed here -->
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ session('warning') }}
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                {{ session('info') }}
            </div>
        </div>
    @endif


    @if($dues->count() > 0)
    <!-- Payment Processing -->
    <form method="POST" action="{{ route('admin.monthly-payments.process') }}" id="bulkPaymentForm">
        @csrf
        @if(request('filter'))
            <input type="hidden" name="filter" value="{{ request('filter') }}">
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header with Actions -->
            <div class="p-3 border-b border-gray-100 bg-gray-50">
                <!-- Compact Header and Filters -->
                <div class="flex flex-wrap items-center gap-3 mb-3">
                    <div class="flex-1 min-w-[200px]">
                        <h3 class="text-sm font-semibold text-gray-900">
                            {{ $monthDate->formatTr('F Y') }} Aidatları
                            <span class="text-xs text-gray-500 font-normal ml-1">({{ $dues->count() }} ödenmemiş)</span>
                        </h3>
                    </div>
                    
                    <!-- Payment Date -->
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-medium text-gray-700 whitespace-nowrap">
                            <i class="fas fa-calendar-check text-orange-500 text-xs mr-1"></i>Ödeme Tarihi:
                        </label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                               class="border border-gray-300 rounded px-2 py-1.5 text-xs focus:ring-1 focus:ring-orange-500 focus:border-orange-500 bg-white">
                    </div>

                    <!-- Member Search -->
                    <div class="flex-1 min-w-[200px] relative">
                        <input type="text" id="memberSearch" placeholder="Üye ara..."
                               class="w-full border border-gray-300 rounded px-3 py-1.5 pl-8 text-xs focus:ring-1 focus:ring-purple-500 focus:border-purple-500 bg-white">
                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>

                <!-- Summary and Actions -->
                <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-gray-200">
                    <div class="flex items-center gap-3 text-xs">
                        <span class="text-gray-600">
                            <span id="selectedCount" class="font-semibold text-blue-600">0</span> seçili
                        </span>
                        <span class="text-gray-600">
                            Toplam: <span id="totalAmount" class="font-semibold text-green-600">0.00 €</span>
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="selectNone()" class="inline-flex items-center px-2.5 py-1.5 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded transition-colors">
                            <i class="fas fa-times mr-1 text-xs"></i>
                            Temizle
                        </button>
                        <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition-colors">
                            <i class="fas fa-credit-card mr-1 text-xs"></i>
                            Ödendi Yap
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-left">
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <input type="checkbox" id="selectAllCheckbox" onchange="toggleAll(this)"
                                           class="rounded border-gray-300 text-green-600 focus:ring-green-500 w-3 h-3 sm:w-4 sm:h-4">
                                    <span class="text-xs text-gray-600 font-medium hidden sm:inline">Tümü</span>
                                    <span class="text-xs text-gray-600 font-medium sm:hidden">✓</span>
                                </div>
                            </th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Üye
                            </th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                Aidat Dönemi
                            </th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Tutar
                            </th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                Ödeme Yöntemi
                            </th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                Durum
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($dues as $due)
                            <tr class="hover:bg-gray-50 transition-colors duration-200" data-amount="{{ $due->amount }}" data-member-id="{{ $due->member->id }}">
                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    <input type="checkbox" name="selected_dues[]" value="{{ $due->id }}"
                                           onchange="updateSelection()"
                                           class="due-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500 w-3 h-3 sm:w-4 sm:h-4">
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    <div>
                                        <div class="text-xs sm:text-sm font-medium text-gray-900">{{ $due->member->surname }} {{ $due->member->name }}</div>
                                        <div class="text-xs sm:text-sm text-gray-500">{{ $due->member->member_no }}</div>
                                        <!-- Mobile: Show period and amount inline -->
                                        <div class="sm:hidden text-xs text-gray-600 mt-1">
                                            {{ $due->due_date->formatTr('F Y') }} • {{ number_format($due->amount, 2) }} €
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 hidden sm:table-cell">
                                    <div class="text-sm text-gray-900">{{ $due->due_date->formatTr('F Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $due->due_date->format('d.m.Y') }}</div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 hidden sm:table-cell">
                                    <div class="text-sm font-semibold text-gray-900">{{ number_format($due->amount, 2) }} €</div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 hidden md:table-cell">
                                    @if($due->member->payment_method)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                            @if($due->member->payment_method === 'cash') bg-green-100 text-green-800
                                            @elseif($due->member->payment_method === 'bank_transfer') bg-blue-100 text-blue-800
                                            @elseif(str_contains($due->member->payment_method, 'lastschrift')) bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($due->member->payment_method === 'cash')
                                                <i class="fas fa-money-bill-wave mr-1"></i>Nakit
                                            @elseif($due->member->payment_method === 'bank_transfer')
                                                <i class="fas fa-university mr-1"></i>Banka Transferi
                                            @elseif($due->member->payment_method === 'lastschrift_monthly')
                                                <i class="fas fa-exchange-alt mr-1"></i>Lastschrift (Aylık)
                                            @elseif($due->member->payment_method === 'lastschrift_semi_annual')
                                                <i class="fas fa-exchange-alt mr-1"></i>Lastschrift (6 Aylık)
                                            @elseif($due->member->payment_method === 'lastschrift_annual')
                                                <i class="fas fa-exchange-alt mr-1"></i>Lastschrift (Yıllık)
                                            @else
                                                {{ $due->member->payment_method }}
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">Belirtilmemiş</span>
                                    @endif
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 hidden lg:table-cell">
                                    @if($due->status === 'pending')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">
                                            <i class="fas fa-clock mr-1"></i>Bekliyor
                                        </span>
                                    @elseif($due->status === 'overdue')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Gecikmiş
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    @else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-calendar-check text-gray-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Bu ay için ödenmemiş aidat bulunamadı</h3>
        <p class="text-gray-600">{{ $monthDate->formatTr('F Y') }} ayında tüm aidatlar ödenmiş görünüyor.</p>
    </div>
    @endif
</div>

<script>
function toggleAll(checkbox) {
    const checkboxes = document.querySelectorAll('.due-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateSelection();
}

function selectAll() {
    const checkboxes = document.querySelectorAll('.due-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = true;
    });
    document.getElementById('selectAllCheckbox').checked = true;
    updateSelection();
}

function selectNone() {
    const checkboxes = document.querySelectorAll('.due-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = false;
    });
    document.getElementById('selectAllCheckbox').checked = false;
    updateSelection();
}

function updateSelection() {
    const checkboxes = document.querySelectorAll('.due-checkbox:checked');
    const count = checkboxes.length;
    let total = 0;

    checkboxes.forEach(cb => {
        const row = cb.closest('tr');
        const amount = parseFloat(row.dataset.amount);
        total += amount;
    });

    document.getElementById('selectedCount').textContent = count;
    document.getElementById('totalAmount').textContent = total.toFixed(2) + ' €';

    // Update main checkbox
    const allCheckboxes = document.querySelectorAll('.due-checkbox');
    const mainCheckbox = document.getElementById('selectAllCheckbox');

    if (count === 0) {
        mainCheckbox.indeterminate = false;
        mainCheckbox.checked = false;
    } else if (count === allCheckboxes.length) {
        mainCheckbox.indeterminate = false;
        mainCheckbox.checked = true;
    } else {
        mainCheckbox.indeterminate = true;
    }
}

// Form submission validation
document.getElementById('bulkPaymentForm').addEventListener('submit', function(e) {
    const selectedCount = document.querySelectorAll('.due-checkbox:checked').length;

    if (selectedCount === 0) {
        e.preventDefault();
        alert('Lütfen en az bir aidat seçin.');
        return false;
    }

    if (!confirm(`${selectedCount} ödeme işlenecek. Devam etmek istediğinizden emin misiniz?`)) {
        e.preventDefault();
        return false;
    }
});

// Initialize selection counter
updateSelection();

// Member search functionality - filter table directly
let searchTimeout;

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    const memberSearchInput = document.getElementById('memberSearch');
    console.log('Member search input found:', memberSearchInput); // Debug log

    if (memberSearchInput) {
        memberSearchInput.addEventListener('input', function(e) {
            const query = e.target.value.trim();
            console.log('Input event triggered, query:', query); // Debug log

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterTableBySearch(query);
            }, 300);
        });

        // Test if the input is working
        console.log('Search input event listener attached successfully');
    } else {
        console.error('Member search input not found!');
    }
});

function filterTableBySearch(query) {
    console.log('Filtering table by search:', query); // Debug log

    const rows = document.querySelectorAll('tbody tr');
    let visibleCount = 0;

    rows.forEach(row => {
        const memberName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const memberNo = row.querySelector('td:nth-child(2) div:last-child').textContent.toLowerCase();

        if (query.length === 0 ||
            memberName.includes(query.toLowerCase()) ||
            memberNo.includes(query.toLowerCase())) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    console.log(`Found ${visibleCount} matching rows`);
    updateSelection();
}
</script>
@endsection
