@extends('admin.layouts.app')

@section('title', 'WhatsApp Aidat Hatırlatmaları')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Coming Soon Section -->
    <div class="text-center mb-8">
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fab fa-whatsapp text-green-600 text-4xl"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">WhatsApp Aidat Hatırlatmaları</h1>
        <p class="text-gray-600 text-lg">Üyelere otomatik WhatsApp mesajları ile aidat hatırlatması gönder</p>
    </div>

    <!-- Coming Soon Card -->
    <div class="max-w-4xl mx-auto mb-8">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-8 text-center">
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                    <i class="fas fa-clock text-green-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-green-800 mb-2">Yakında Aktif Olacak!</h2>
                <p class="text-green-700 text-lg mb-6">WhatsApp entegrasyonu tamamlandığında bu özellik aktif hale gelecektir.</p>
            </div>

            <!-- Features Preview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Toplu Mesaj</h3>
                    <p class="text-gray-600 text-sm">Tüm gecikmiş aidatı olan üyelere tek seferde mesaj gönder</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-edit text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Özel Mesajlar</h3>
                    <p class="text-gray-600 text-sm">Üyeye özel mesaj şablonları ile kişiselleştirilmiş hatırlatmalar</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">İstatistikler</h3>
                    <p class="text-gray-600 text-sm">Gönderilen mesajların istatistikleri ve başarı oranları</p>
                </div>
            </div>

            <!-- Current Stats -->
            <div class="bg-white rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Mevcut Durum</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600">{{ isset($stats['total_overdue']) ? $stats['total_overdue'] : '0' }}</div>
                        <div class="text-sm text-gray-600">Gecikmiş Aidat</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ isset($stats['members_with_phone']) ? $stats['members_with_phone'] : '0' }}</div>
                        <div class="text-sm text-gray-600">Telefonu Olan Üye</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ isset($stats['total_amount']) ? number_format($stats['total_amount'], 2) : '0.00' }} €</div>
                        <div class="text-sm text-gray-600">Toplam Tutar</div>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-center text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span class="text-sm font-medium">
                        WhatsApp Business API entegrasyonu tamamlandığında bu sayfa tam işlevsel hale gelecektir.
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Technical Info -->
    <div class="max-w-4xl mx-auto">
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-cog mr-2"></i>
                Teknik Bilgiler
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700">API Durumu:</span>
                    <span class="text-orange-600 ml-2">Entegrasyon Bekleniyor</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Rate Limit:</span>
                    <span class="text-gray-600 ml-2">80 mesaj/saniye</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Günlük Limit:</span>
                    <span class="text-gray-600 ml-2">1,000 mesaj</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Sistem:</span>
                    <span class="text-green-600 ml-2">Hazır</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{--
==============================================
WHATSAPP FUNCTIONALITY - COMMENTED OUT
==============================================
Bu bölüm WhatsApp API entegrasyonu tamamlandığında aktif edilecek.
Tüm kodlar hazır durumda, sadece yorum satırından çıkarılması yeterli.

<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">WhatsApp Aidat Hatırlatmaları</h1>
            <p class="text-gray-600 mt-1">Gecikmiş aidatlar için WhatsApp hatırlatması gönder</p>
        </div>
        <div class="flex space-x-2">
            <button id="bulkReminderBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fab fa-whatsapp mr-2"></i>
                Toplu Hatırlatma
            </button>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('errors') && count(session('errors')) > 0)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                <span class="text-red-800 font-medium">Hatalar:</span>
            </div>
            <ul class="text-red-700 text-sm list-disc list-inside">
                @foreach(session('errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Gecikmiş Aidat</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['total_overdue'] }}</p>
                    <p class="text-sm text-red-600">{{ number_format($stats['total_amount'], 2) }} €</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fab fa-whatsapp text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">WhatsApp Gönderim</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['members_with_phone'] }}</p>
                    <p class="text-sm text-green-600">Telefonu olan üye</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-clock text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Rate Limit</p>
                    <p class="text-2xl font-bold text-blue-600">5</p>
                    <p class="text-sm text-blue-600">mesaj/dakika</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Dues Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <!-- Bulk Actions -->
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <input type="checkbox" id="selectAllDues" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <label for="selectAllDues" class="text-sm font-medium text-gray-700">Bu sayfadaki tümünü seç</label>
                </div>
                <div class="flex items-center space-x-2">
                    <span id="selectedCount" class="text-sm text-gray-600">0 üye seçildi</span>
                    <button type="button" id="sendSelectedBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                        <i class="fab fa-whatsapp mr-2"></i>
                        Seçilenlere Gönder
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
                            <input type="checkbox" id="selectAllDuesTable" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Üye</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gecikmiş Aidat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Toplam Tutar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">En Eski</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $memberGroups = $overdueDues->groupBy('member_id');
                    @endphp

                    @forelse($memberGroups as $memberId => $memberDues)
                        @php
                            $member = $memberDues->first()->member;
                            $totalAmount = $memberDues->sum('amount');
                            $oldestDue = $memberDues->sortBy('due_date')->first();
                        @endphp
                        <tr class="hover:bg-green-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->phone)
                                    <input type="checkbox" name="selected_members[]" value="{{ $member->id }}" class="member-checkbox w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                @else
                                    <i class="fas fa-phone-slash text-gray-400" title="Telefon numarası yok"></i>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $member->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->phone)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fab fa-whatsapp mr-1"></i>
                                        {{ $member->phone }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-phone-slash mr-1"></i>
                                        Telefon yok
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $memberDues->count() }} adet
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                {{ number_format($totalAmount, 2) }} €
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $oldestDue->due_date->format('d.m.Y') }}
                                <div class="text-xs text-gray-400">
                                    {{ $oldestDue->due_date->diffForHumans() }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                                <p class="text-lg font-medium">Gecikmiş aidat bulunmuyor!</p>
                                <p class="text-sm">Tüm aidatlar zamanında ödenmiş.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden">
            @php
                $memberGroups = $overdueDues->groupBy('member_id');
            @endphp

            @forelse($memberGroups as $memberId => $memberDues)
                @php
                    $member = $memberDues->first()->member;
                    $totalAmount = $memberDues->sum('amount');
                    $oldestDue = $memberDues->sortBy('due_date')->first();
                @endphp
                <div class="border-b border-gray-200 p-4 hover:bg-green-50 transition-colors duration-200">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-start space-x-3 flex-1">
                            @if($member->phone)
                                <input type="checkbox" name="selected_members[]" value="{{ $member->id }}" class="member-checkbox w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 mt-1">
                            @else
                                <i class="fas fa-phone-slash text-gray-400 mt-1" title="Telefon numarası yok"></i>
                            @endif
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-900 mb-1">{{ $member->full_name }}</h3>
                                <p class="text-xs text-gray-500 mb-2">{{ $member->email }}</p>

                                @if($member->phone)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-2">
                                        <i class="fab fa-whatsapp mr-1"></i>
                                        {{ $member->phone }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 mb-2">
                                        <i class="fas fa-phone-slash mr-1"></i>
                                        Telefon yok
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-red-600">{{ number_format($totalAmount, 2) }} €</span>
                            <p class="text-xs text-gray-500">{{ $memberDues->count() }} adet gecikmiş</p>
                        </div>
                    </div>

                    <div class="text-xs text-gray-500">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        En eski: {{ $oldestDue->due_date->format('d.m.Y') }} ({{ $oldestDue->due_date->diffForHumans() }})
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                    <p class="text-lg font-medium">Gecikmiş aidat bulunmuyor!</p>
                    <p class="text-sm">Tüm aidatlar zamanında ödenmiş.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($overdueDues->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $overdueDues->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Message Modal -->
<div id="messageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">WhatsApp Aidat Hatırlatması</h3>
                <button onclick="hideMessageModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="reminderForm" method="POST" action="{{ route('admin.whatsapp.send-reminders') }}">
                @csrf
                <div id="memberIdsContainer"></div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mesaj Şablonu</label>
                    <div class="flex space-x-2 mb-2">
                        <button type="button" onclick="loadTemplate('standard')" class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Standart</button>
                        <button type="button" onclick="loadTemplate('polite')" class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Nazik</button>
                        <button type="button" onclick="loadTemplate('urgent')" class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Acil</button>
                    </div>
                    <textarea name="message_template" id="messageTemplate" rows="8" required
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                              placeholder="Mesajınızı yazın...">Sayın {full_name},

{due_count} adet gecikmiş aidatınız bulunmaktadır.
Toplam tutar: {total_amount} €
En eski aidat: {oldest_due_date}

Lütfen en kısa sürede ödemenizi yapınız.

Teşekkürler.</textarea>

                    <div class="mt-2 text-xs text-gray-500">
                        <p class="font-medium mb-1">Kullanılabilir değişkenler:</p>
                        <div class="grid grid-cols-2 gap-2">
                            <span>{name} - Ad</span>
                            <span>{surname} - Soyad</span>
                            <span>{full_name} - Ad Soyad</span>
                            <span>{member_number} - Üye No</span>
                            <span>{total_amount} - Toplam Tutar</span>
                            <span>{due_count} - Aidat Sayısı</span>
                            <span>{oldest_due_date} - En Eski Tarih</span>
                            <span>{oldest_due_month} - En Eski Ay</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-yellow-600 mr-2"></i>
                        <div class="text-sm text-yellow-800">
                            <p class="font-medium">Rate Limit: 5 mesaj/dakika</p>
                            <p>Toplu gönderimde her mesaj arası 12 saniye beklenir.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideMessageModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        İptal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fab fa-whatsapp mr-2"></i>
                        Gönder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAllDues');
    const selectAllTableCheckbox = document.getElementById('selectAllDuesTable');
    const memberCheckboxes = document.querySelectorAll('.member-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const sendSelectedBtn = document.getElementById('sendSelectedBtn');
    const bulkReminderBtn = document.getElementById('bulkReminderBtn');

    // Select all functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            memberCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
            updateSelectAllCheckboxes();
        });
    }

    if (selectAllTableCheckbox) {
        selectAllTableCheckbox.addEventListener('change', function() {
            memberCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
            updateSelectAllCheckboxes();
        });
    }

    // Individual checkbox change
    memberCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            updateSelectAllCheckboxes();
        });
    });

    // Send selected button
    if (sendSelectedBtn) {
        sendSelectedBtn.addEventListener('click', function() {
            const selectedMembers = getSelectedMembers();
            if (selectedMembers.length === 0) {
                alert('Lütfen hatırlatma gönderilecek üyeleri seçin.');
                return;
            }
            showMessageModal(selectedMembers);
        });
    }

    // Bulk reminder button
    if (bulkReminderBtn) {
        bulkReminderBtn.addEventListener('click', function() {
            if (confirm('Tüm gecikmiş aidatı olan üyelere hatırlatma göndermek istediğinizden emin misiniz?')) {
                showBulkMessageModal();
            }
        });
    }

    function updateSelectedCount() {
        const selectedCount = getSelectedMembers().length;
        selectedCountSpan.textContent = `${selectedCount} üye seçildi`;
        sendSelectedBtn.disabled = selectedCount === 0;
    }

    function updateSelectAllCheckboxes() {
        const selectedCount = getSelectedMembers().length;
        const totalCount = memberCheckboxes.length;

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = selectedCount === totalCount;
            selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < totalCount;
        }

        if (selectAllTableCheckbox) {
            selectAllTableCheckbox.checked = selectedCount === totalCount;
            selectAllTableCheckbox.indeterminate = selectedCount > 0 && selectedCount < totalCount;
        }
    }

    function getSelectedMembers() {
        return Array.from(memberCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);
    }

    function showMessageModal(memberIds) {
        const container = document.getElementById('memberIdsContainer');
        container.innerHTML = '';

        memberIds.forEach(memberId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'member_ids[]';
            input.value = memberId;
            container.appendChild(input);
        });

        document.getElementById('messageModal').classList.remove('hidden');
    }

    function showBulkMessageModal() {
        // Bulk reminder için form action'ını değiştir
        document.getElementById('reminderForm').action = '{{ route("admin.whatsapp.send-bulk-reminders") }}';
        document.getElementById('memberIdsContainer').innerHTML = '';
        document.getElementById('messageModal').classList.remove('hidden');
    }

    // İlk yükleme
    updateSelectedCount();
});

function hideMessageModal() {
    document.getElementById('messageModal').classList.add('hidden');
    // Form action'ını geri al
    document.getElementById('reminderForm').action = '{{ route("admin.whatsapp.send-reminders") }}';
}

function loadTemplate(type) {
    const templates = {
        'standard': `Sayın {full_name},

{due_count} adet gecikmiş aidatınız bulunmaktadır.
Toplam tutar: {total_amount} €
En eski aidat: {oldest_due_date}

Lütfen en kısa sürede ödemenizi yapınız.

Teşekkürler.`,
        'polite': `Merhaba {name},

Gecikmiş {due_count} adet aidatınız için nazik bir hatırlatma.
Toplam: {total_amount} €

Zamanınızda ödeme yapabilirseniz çok memnun oluruz.

Saygılarımızla.`,
        'urgent': `Sayın {full_name},

⚠️ ACİL: {oldest_due_date} tarihinden beri gecikmiş aidatlarınız var!

Toplam: {total_amount} € ({due_count} adet)

Lütfen DERHAL ödemenizi yapınız.

Cami Yönetimi`
    };

    document.getElementById('messageTemplate').value = templates[type];
}

// Modal dışına tıklayınca kapat
document.getElementById('messageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideMessageModal();
    }
});
</script>

==============================================
END OF COMMENTED WHATSAPP FUNCTIONALITY
==============================================
--}}

@endsection
