@extends('admin.layouts.app')

@section('title', 'Yeni Ödeme')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-credit-card mr-2 text-blue-500"></i>
                Yeni Ödeme
            </h1>
            <p class="mt-2 text-gray-600">Üye için yeni ödeme kaydı oluşturun.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.payments.index') }}" class="btn-secondary px-4 py-2 rounded-xl font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                Ödemelere Dön
            </a>
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

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
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

    <!-- Payment Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.payments.store') }}" method="POST" class="space-y-8" onsubmit="return validateForm()">
            @csrf
            <input type="hidden" name="selected_due_ids" id="selected_due_ids" value="">

            <!-- Member Selection Section -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 border border-blue-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-user mr-2 text-blue-500"></i>
                    Üye Bilgileri
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div id="member_selection" class="{{ request('member_id') ? 'hidden' : '' }}">
                        <label for="member_search" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search mr-2 text-green-500"></i>
                            Üye Ara *
                        </label>

                        <!-- Search Input -->
                        <div class="relative mb-3">
                            <input type="text" id="member_search" placeholder="Ad, soyad veya email ile arayın..."
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 pl-10 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Member List -->
                        <div id="member_list" class="max-h-60 overflow-y-auto border border-gray-200 rounded-xl bg-white">
                            @foreach($members as $member)
                                <div class="member-item p-3 border-b border-gray-100 hover:bg-blue-50 cursor-pointer transition-colors"
                                     data-member-id="{{ $member->id }}"
                                     data-member-name="{{ $member->name }} {{ $member->surname }}"
                                     data-member-email="{{ $member->email }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 font-semibold text-sm">
                                                    {{ strtoupper(substr($member->name, 0, 1) . substr($member->surname, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $member->name }} {{ $member->surname }}</div>
                                                <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-green-600">{{ $member->monthly_dues }} €</div>
                                            <div class="text-xs text-gray-400">Aylık Aidat</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Hidden Select for Form -->
                        <select name="member_id" id="member_id" required onchange="loadUnpaidDues()" class="hidden">
                            <option value="">Üye seçin</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old('member_id', request('member_id')) == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }} {{ $member->surname }} - {{ $member->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('member_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="member_info" class="{{ request('member_id') ? '' : 'hidden' }}">
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 border border-blue-200">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-900">
                                    <i class="fas fa-user-check mr-2 text-green-500"></i>
                                    Seçilen Üye
                                </h4>
                                <button type="button" onclick="changeMember()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    <i class="fas fa-edit mr-1"></i>Değiştir
                                </button>
                            </div>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center">
                                    <i class="fas fa-user mr-3 text-blue-500 w-4"></i>
                                    <span id="member_name" class="font-semibold text-gray-900 text-base">-</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-euro-sign mr-3 text-green-500 w-4"></i>
                                    <span class="text-gray-600">Aylık Aidat: <span id="member_dues" class="font-bold text-green-600 text-base">-</span> €</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-credit-card mr-3 text-purple-500 w-4"></i>
                                    <span class="text-gray-600">Ödeme Yöntemi: <span id="member_payment_method" class="font-medium text-purple-600">-</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Due Selection Section -->
            <div id="due_selection" class="hidden bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-6 border border-orange-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-money-bill-wave mr-2 text-orange-500"></i>
                    Aidat Ödemesi
                </h3>

                <!-- Quick Selection Buttons -->
                <div class="mb-6">
                    <div class="flex flex-wrap gap-3 mb-4">
                        <button type="button" onclick="selectOverdueDues()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Geciken Aidatları Seç
                        </button>
                        <button type="button" onclick="selectAllDues()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-check-double mr-2"></i>
                            Tümünü Seç
                        </button>
                        <button type="button" onclick="clearSelection()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Seçimi Temizle
                        </button>
                        <!-- Yearly Selection Buttons -->
                        <div id="yearly_selection_buttons" class="flex flex-wrap gap-2 ml-2">
                            <!-- Year buttons will be added here dynamically -->
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                        Her seçilen aidat için ayrı ödeme kaydı oluşturulur.
                    </div>
                </div>

                <!-- Due Selection Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>
                            Ödenecek Aidatlar *
                        </label>
                        <div id="dues_container" class="max-h-80 overflow-y-auto border border-gray-300 rounded-xl p-4 bg-white">
                            <div class="space-y-2" id="dues_list">
                                <!-- Dues will be loaded here -->
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                            Ödeme yapılacak aidat dönemlerini seçin.
                        </p>
                        @error('selected_due_ids')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="due_summary" class="hidden">
                        <div class="bg-white rounded-xl p-4 border border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-2">Seçilen Aidatlar</h4>
                            <div id="selected_dues_summary" class="space-y-2">
                                <!-- Selected dues will be shown here -->
                            </div>
                            <div class="mt-4 pt-3 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-900">Toplam Tutar:</span>
                                    <span id="total_amount" class="font-bold text-green-600 text-lg">0.00 €</span>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-sm text-gray-600">Seçilen Aidat:</span>
                                    <span id="selected_count" class="text-sm font-medium text-blue-600">0 adet</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details Section -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-6 border border-green-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-credit-card mr-2 text-green-500"></i>
                    Ödeme Detayları
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-euro-sign mr-2 text-green-500"></i>
                            Toplam Tutar (€) *
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-500">€</span>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0.01" required readonly
                                   class="w-full border border-gray-300 rounded-xl pl-8 pr-4 py-3 bg-gray-50 text-gray-700 cursor-not-allowed"
                                   placeholder="0.00">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Her aidat için ayrı ödeme kaydı oluşturulur
                        </p>
                        @error('amount')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-credit-card mr-2 text-purple-500"></i>
                            Ödeme Yöntemi *
                        </label>
                        <select name="payment_method" id="payment_method" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seçin</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>
                                Nakit
                            </option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                Banka Transferi
                            </option>
                            <option value="lastschrift_monthly" {{ old('payment_method') == 'lastschrift_monthly' ? 'selected' : '' }}>
                                Lastschrift (Aylık)
                            </option>
                            <option value="lastschrift_semi_annual" {{ old('payment_method') == 'lastschrift_semi_annual' ? 'selected' : '' }}>
                                Lastschrift (6 Aylık)
                            </option>
                            <option value="lastschrift_annual" {{ old('payment_method') == 'lastschrift_annual' ? 'selected' : '' }}>
                                Lastschrift (Yıllık)
                            </option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Üyenin varsayılan ödeme yöntemi otomatik seçilir, değiştirilebilir
                        </p>
                        @error('payment_method')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-blue-500"></i>
                            Ödeme Tarihi
                        </label>
                        <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="mt-1 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Tahsilatın gerçekleştiği günü seçin.
                        </p>
                        @error('payment_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="receipt_no" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-receipt mr-2 text-orange-500"></i>
                            Makbuz No
                        </label>
                        <input type="text" name="receipt_no" id="receipt_no" value="{{ old('receipt_no') }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Makbuz numarası">
                        @error('receipt_no')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-sticky-note mr-2 text-blue-500"></i>
                    Ek Bilgiler
                </h3>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment mr-2 text-green-500"></i>
                        Açıklama
                        <span id="description_required" class="text-red-500 text-xs ml-2 hidden">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Makbuz numarası girilmediği için zorunlu
                        </span>
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Ödeme ile ilgili ek bilgiler...">{{ old('description') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Makbuz numarası girilmediği durumlarda açıklama alanı zorunludur
                    </p>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.payments.index') }}" class="btn-secondary px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-times mr-2"></i>
                    İptal
                </a>
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Ödemeyi Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let allDues = []; // Global variable to store all dues
let paymentDateManuallySet = false;

document.addEventListener('DOMContentLoaded', () => {
    const paymentDateInput = document.getElementById('payment_date');
    if (paymentDateInput) {
        paymentDateInput.addEventListener('input', () => {
            paymentDateManuallySet = paymentDateInput.value !== '';
        });
    }
});

function loadUnpaidDues() {
    const memberId = document.getElementById('member_id').value;
    const dueSelection = document.getElementById('due_selection');
    const memberInfo = document.getElementById('member_info');

    if (!memberId) {
        dueSelection.classList.add('hidden');
        memberInfo.classList.add('hidden');
        return;
    }

    // Show due selection
    dueSelection.classList.remove('hidden');

    // Fetch member info and unpaid dues
    Promise.all([
        fetch(`/admin/members/${memberId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (!response.ok) throw new Error('Member fetch failed: ' + response.status);
            return response.json();
        }),
        fetch(`/admin/payments/unpaid-dues/${memberId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (!response.ok) throw new Error('Dues fetch failed: ' + response.status);
            return response.json();
        })
    ])
    .then(([member, dues]) => {
        // Update member info
        document.getElementById('member_name').textContent = `${member.name} ${member.surname}`;
        document.getElementById('member_dues').textContent = member.monthly_dues || '0';

        // Update payment method display
        const paymentMethodNames = {
            'cash': 'Nakit',
            'bank_transfer': 'Banka Transferi',
            'lastschrift_monthly': 'Lastschrift (Aylık)',
            'lastschrift_semi_annual': 'Lastschrift (6 Aylık)',
            'lastschrift_annual': 'Lastschrift (Yıllık)'
        };
        const paymentMethodText = member.payment_method ? paymentMethodNames[member.payment_method] || member.payment_method : 'Belirtilmemiş';
        document.getElementById('member_payment_method').textContent = paymentMethodText;

        memberInfo.classList.remove('hidden');

        // Set default payment method from member's preference
        const paymentMethodSelect = document.getElementById('payment_method');
        if (member.payment_method) {
            paymentMethodSelect.value = member.payment_method;
        } else {
            // Default to bank_transfer if no preference set
            paymentMethodSelect.value = 'bank_transfer';
        }

        // Store dues globally
        allDues = dues;

        // Sort dues by date (overdue first, then by due date)
        allDues.sort((a, b) => {
            const dateA = new Date(a.due_date);
            const dateB = new Date(b.due_date);
            const today = new Date();

            const isOverdueA = dateA < today;
            const isOverdueB = dateB < today;

            // Overdue items first
            if (isOverdueA && !isOverdueB) return -1;
            if (!isOverdueA && isOverdueB) return 1;

            // Then sort by date
            return dateA - dateB;
        });

        // Render dues as checkboxes
        renderDuesList();

        // Show success notification
        showNotification(`${allDues.length} adet aidat yüklendi`, 'success');
    })
    .catch(error => {
        console.error('Error loading data:', error);
        document.getElementById('dues_list').innerHTML = `
            <div class="text-center py-8">
                <div class="text-red-400 text-6xl mb-4">❌</div>
                <div class="text-red-500 text-lg font-medium mb-2">Aidatlar Yüklenemedi</div>
                <div class="text-red-400 text-sm">Bir hata oluştu. Lütfen tekrar deneyin.</div>
            </div>
        `;
        showNotification('Aidatlar yüklenirken hata oluştu', 'error');
    });
}

function renderDuesList() {
    const duesList = document.getElementById('dues_list');
    duesList.innerHTML = '';

    if (allDues.length === 0) {
        duesList.innerHTML = `
            <div class="text-center py-8">
                <div class="text-gray-400 text-6xl mb-4">💰</div>
                <div class="text-gray-500 text-lg font-medium mb-2">Ödenecek Aidat Bulunamadı</div>
                <div class="text-gray-400 text-sm">Bu üyenin ödenecek aidatı bulunmamaktadır.</div>
            </div>
        `;
        return;
    }

    // Show loading briefly for better UX
    duesList.innerHTML = '<div class="text-center py-4"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div></div>';

    setTimeout(() => {
        renderDuesContent();
    }, 300);
}

function renderDuesContent() {
    const duesList = document.getElementById('dues_list');
    duesList.innerHTML = '';

    let hasOverdue = false;
    let hasPending = false;
    let addedOverdueHeader = false;
    let addedPendingHeader = false;

    // Get unique years for yearly selection buttons (only past and current years)
    const currentYear = new Date().getFullYear();
    const years = [...new Set(allDues.map(due => new Date(due.due_date).getFullYear()))]
        .filter(year => year <= currentYear)  // Only include past and current years
        .sort((a, b) => a - b);  // Sort ascending (oldest year first)

    // Color classes for yearly buttons
    const colorClasses = [
        'bg-blue-500 hover:bg-blue-600',
        'bg-green-500 hover:bg-green-600',
        'bg-yellow-500 hover:bg-yellow-600',
        'bg-red-500 hover:bg-red-600',
        'bg-indigo-500 hover:bg-indigo-600',
        'bg-pink-500 hover:bg-pink-600',
        'bg-teal-500 hover:bg-teal-600',
        'bg-orange-500 hover:bg-orange-600'
    ];

    // Create yearly selection buttons
    const yearlySelectionButtons = document.getElementById('yearly_selection_buttons');
    yearlySelectionButtons.innerHTML = '';

    years.forEach((year, index) => {
        const colorClass = colorClasses[index % colorClasses.length];
        const button = document.createElement('button');
        button.type = 'button';
        button.className = `${colorClass} text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors`;
        button.innerHTML = `<i class="fas fa-calendar mr-1"></i> ${year}`;
        button.onclick = () => selectYearlyDues(year);
        yearlySelectionButtons.appendChild(button);
    });

    allDues.forEach(due => {
        const monthNames = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran',
                           'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
        const monthName = monthNames[due.month - 1];

        // Determine status
        const dueDate = new Date(due.due_date);
        const today = new Date();
        const isOverdue = dueDate < today;

        // Add group headers
        if (isOverdue && !addedOverdueHeader) {
            const overdueHeader = document.createElement('div');
            overdueHeader.className = 'font-bold text-red-600 text-sm py-2 border-b border-red-200';
            overdueHeader.innerHTML = '🔴 GECİKMİŞ AİDATLAR';
            duesList.appendChild(overdueHeader);
            addedOverdueHeader = true;
            hasOverdue = true;
        } else if (!isOverdue && !addedPendingHeader && hasOverdue) {
            const pendingHeader = document.createElement('div');
            pendingHeader.className = 'font-bold text-orange-600 text-sm py-2 border-b border-orange-200 mt-4';
            pendingHeader.innerHTML = '🟡 BEKLEYEN AİDATLAR';
            duesList.appendChild(pendingHeader);
            addedPendingHeader = true;
            hasPending = true;
        }

        // Create due item
        const dueItem = document.createElement('div');
        dueItem.className = `flex items-center p-3 border rounded-lg mb-2 ${isOverdue ? 'border-red-200 bg-red-50' : 'border-gray-200 bg-gray-50'} hover:bg-white transition-colors`;

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = `due_${due.id}`;
        checkbox.className = 'due-checkbox mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded';
        checkbox.dataset.dueId = due.id;
        checkbox.dataset.amount = due.amount;
        checkbox.dataset.period = `${monthName} ${due.year}`;
        checkbox.dataset.dueDate = due.due_date;
        checkbox.dataset.isOverdue = isOverdue;
        checkbox.addEventListener('change', updateSelection);

        const label = document.createElement('label');
        label.htmlFor = `due_${due.id}`;
        label.className = 'flex-1 cursor-pointer';

        const statusIcon = isOverdue ? '🔴' : '🟡';
        const statusText = isOverdue ? 'Gecikmiş' : 'Bekliyor';
        const statusClass = isOverdue ? 'text-red-600 font-medium' : 'text-orange-600';

        label.innerHTML = `
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-medium text-gray-900">${monthName} ${due.year}</div>
                    <div class="text-sm text-gray-600">Aidat Dönemi</div>
                    <span class="text-sm ${statusClass}">${statusIcon} ${statusText}</span>
                </div>
                <div class="text-right">
                    <div class="font-bold text-green-600">€${parseFloat(due.amount).toFixed(2)}</div>
                    <div class="text-xs text-gray-500">Vade: ${new Date(due.due_date).toLocaleDateString('tr-TR')}</div>
                </div>
            </div>
        `;

        dueItem.appendChild(checkbox);
        dueItem.appendChild(label);
        duesList.appendChild(dueItem);
    });
}

function updatePaymentDate() {
    const selectedCheckboxes = document.querySelectorAll('.due-checkbox:checked');
    const paymentDateInput = document.getElementById('payment_date');

    if (selectedCheckboxes.length === 0) {
        paymentDateInput.value = '';
        paymentDateManuallySet = false;
        return;
    }

    if (paymentDateManuallySet) {
        return;
    }

    // Default to today's date for new selections
    const today = new Date();
    const formattedDate = today.toISOString().split('T')[0];
    paymentDateInput.value = formattedDate;
}

function updateSelection() {
    const selectedCheckboxes = document.querySelectorAll('.due-checkbox:checked');
    const selectedDuesSummary = document.getElementById('selected_dues_summary');
    const totalAmountSpan = document.getElementById('total_amount');
    const selectedCountSpan = document.getElementById('selected_count');
    const dueSummary = document.getElementById('due_summary');
    const amountInput = document.getElementById('amount');
    const selectedDueIdsInput = document.getElementById('selected_due_ids');

    if (selectedCheckboxes.length === 0) {
        dueSummary.classList.add('hidden');
        amountInput.value = '';
        selectedDueIdsInput.value = '';
        updatePaymentDate(); // Reset payment date
        return;
    }

    let totalAmount = 0;
    let selectedDueIds = [];
    let summaryHTML = '';

    selectedCheckboxes.forEach(checkbox => {
        const amount = parseFloat(checkbox.dataset.amount);
        const period = checkbox.dataset.period;
        const isOverdue = checkbox.dataset.isOverdue === 'true';

        totalAmount += amount;
        selectedDueIds.push(checkbox.dataset.dueId);

        const statusIcon = isOverdue ? '🔴' : '🟡';
        const statusClass = isOverdue ? 'text-red-600' : 'text-orange-600';

        summaryHTML += `
            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                <div class="flex items-center">
                    <span class="text-sm ${statusClass} mr-2">${statusIcon}</span>
                    <div>
                        <div class="text-sm font-medium">${period}</div>
                        <div class="text-xs text-gray-500">Aidat Dönemi</div>
                    </div>
                </div>
                <span class="text-sm font-bold text-green-600">€${amount.toFixed(2)}</span>
            </div>
        `;
    });

    selectedDuesSummary.innerHTML = summaryHTML;
    totalAmountSpan.textContent = `€${totalAmount.toFixed(2)}`;
    selectedCountSpan.textContent = `${selectedCheckboxes.length} adet`;
    amountInput.value = totalAmount.toFixed(2);
    selectedDueIdsInput.value = selectedDueIds.join(',');

    // Update payment date based on selected dues
    updatePaymentDate();

    dueSummary.classList.remove('hidden');
}

function selectOverdueDues() {
    const overdueCheckboxes = document.querySelectorAll('.due-checkbox[data-is-overdue="true"]');
    if (overdueCheckboxes.length === 0) {
        showNotification('Geciken aidat bulunamadı', 'warning');
        return;
    }

    overdueCheckboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    updateSelection();
    showNotification(`${overdueCheckboxes.length} adet geciken aidat seçildi`, 'success');
}

function selectAllDues() {
    const allCheckboxes = document.querySelectorAll('.due-checkbox');
    if (allCheckboxes.length === 0) {
        showNotification('Seçilecek aidat bulunamadı', 'warning');
        return;
    }

    allCheckboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    updateSelection();
    showNotification(`${allCheckboxes.length} adet aidat seçildi`, 'success');
}

function clearSelection() {
    const allCheckboxes = document.querySelectorAll('.due-checkbox');
    allCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelection();
    showNotification('Tüm seçimler temizlendi', 'info');
}

function selectYearlyDues(year) {
    const yearlyCheckboxes = document.querySelectorAll(`.due-checkbox[data-due-date*="${year}-"]`);
    if (yearlyCheckboxes.length === 0) {
        showNotification(`${year} yılı için aidat bulunamadı`, 'warning');
        return;
    }

    yearlyCheckboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    updateSelection();
    showNotification(`${year} yılı için ${yearlyCheckboxes.length} adet aidat seçildi`, 'success');
}

function validateForm() {
    const memberId = document.getElementById('member_id').value;
    const selectedDueIds = document.getElementById('selected_due_ids').value;
    const amount = document.getElementById('amount').value;
    const paymentMethod = document.getElementById('payment_method').value;
    const paymentDate = document.getElementById('payment_date').value;
    const receiptNo = document.getElementById('receipt_no').value;
    const description = document.getElementById('description').value;

    if (!memberId) {
        showNotification('Lütfen bir üye seçin', 'error');
        return false;
    }

    if (!selectedDueIds) {
        showNotification('Lütfen en az bir aidat seçin', 'error');
        return false;
    }

    if (!amount || parseFloat(amount) <= 0) {
        showNotification('Lütfen geçerli bir tutar girin', 'error');
        return false;
    }

    if (!paymentMethod) {
        showNotification('Lütfen ödeme yöntemi seçin', 'error');
        return false;
    }

    if (!paymentDate) {
        showNotification('Lütfen ödeme tarihi seçin', 'error');
        return false;
    }

    // Makbuz numarası girilmemişse açıklama zorunlu
    if (!receiptNo.trim() && !description.trim()) {
        showNotification('Makbuz numarası girilmediği için açıklama alanı zorunludur', 'error');
        return false;
    }

    return true;
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

    const colors = {
        success: 'bg-green-500 text-white',
        warning: 'bg-yellow-500 text-white',
        error: 'bg-red-500 text-white',
        info: 'bg-blue-500 text-white'
    };

    notification.className += ` ${colors[type] || colors.info}`;
    notification.innerHTML = `
        <div class="flex items-center">
            <span class="mr-2">${type === 'success' ? '✅' : type === 'warning' ? '⚠️' : type === 'error' ? '❌' : 'ℹ️'}</span>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

function changeMember() {
    // Reset member selection
    document.getElementById('member_selection').classList.remove('hidden');
    document.getElementById('member_info').classList.add('hidden');
    document.getElementById('member_id').value = '';
    document.getElementById('member_search').value = '';

    // Reset due selection
    document.getElementById('due_selection').classList.add('hidden');
    document.getElementById('dues_list').innerHTML = '';
    document.getElementById('due_summary').classList.add('hidden');
    document.getElementById('amount').value = '';
    document.getElementById('selected_due_ids').value = '';
    allDues = [];

    // Reset payment method
    document.getElementById('payment_method').value = '';

    // Show all member items
    document.querySelectorAll('.member-item').forEach(item => {
        item.style.display = 'block';
        item.classList.remove('bg-blue-100', 'border-blue-300');
    });

    // Focus on search input
    document.getElementById('member_search').focus();
}

// Member Search and Selection
document.addEventListener('DOMContentLoaded', function() {
    const memberSearch = document.getElementById('member_search');
    const memberList = document.getElementById('member_list');
    const memberItems = document.querySelectorAll('.member-item');
    const memberSelect = document.getElementById('member_id');

    // Makbuz numarası ve açıklama alanı kontrolü
    const receiptNoInput = document.getElementById('receipt_no');
    const descriptionInput = document.getElementById('description');
    const descriptionRequired = document.getElementById('description_required');

    function updateDescriptionRequirement() {
        const receiptNo = receiptNoInput.value.trim();
        const description = descriptionInput.value.trim();

        if (!receiptNo && !description) {
            // Makbuz numarası yok ve açıklama da yok - açıklama zorunlu
            descriptionRequired.classList.remove('hidden');
            descriptionInput.classList.add('border-red-300', 'bg-red-50');
        } else {
            // Makbuz numarası var veya açıklama var - açıklama zorunlu değil
            descriptionRequired.classList.add('hidden');
            descriptionInput.classList.remove('border-red-300', 'bg-red-50');
        }
    }

    // Event listeners
    receiptNoInput.addEventListener('input', updateDescriptionRequirement);
    descriptionInput.addEventListener('input', updateDescriptionRequirement);

    // İlk yüklemede kontrol et
    updateDescriptionRequirement();

    // Search functionality
    memberSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();

        memberItems.forEach(item => {
            const memberName = item.dataset.memberName.toLowerCase();
            const memberEmail = item.dataset.memberEmail.toLowerCase();

            if (memberName.includes(searchTerm) || memberEmail.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Member selection
    memberItems.forEach(item => {
        item.addEventListener('click', function() {
            const memberId = this.dataset.memberId;

            // Update hidden select
            memberSelect.value = memberId;

            // Hide member selection and show member info
            document.getElementById('member_selection').classList.add('hidden');
            document.getElementById('member_info').classList.remove('hidden');

            // Load unpaid dues (this will also update member info display including payment method)
            loadUnpaidDues();

            // Highlight selected member
            memberItems.forEach(mi => mi.classList.remove('bg-blue-100', 'border-blue-300'));
            this.classList.add('bg-blue-100', 'border-blue-300');
        });
    });

    // Initialize if member_id is pre-filled (from URL parameter)
    const urlParams = new URLSearchParams(window.location.search);
    const memberId = urlParams.get('member_id');

    if (memberId) {
        // Member is pre-selected from member list
        document.getElementById('member_id').value = memberId;

        // Hide member selection and show member info immediately
        document.getElementById('member_selection').classList.add('hidden');
        document.getElementById('member_info').classList.remove('hidden');

        loadUnpaidDues();
    }
});
</script>
@endsection
