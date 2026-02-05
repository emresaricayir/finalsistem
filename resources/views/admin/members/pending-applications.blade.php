@extends('admin.layouts.app')

@section('title', 'Online Başvurular')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-globe mr-2 text-blue-500"></i>
                    Online Başvurular
                </h1>
                <p class="mt-2 text-gray-600">Online üyelik başvurularını inceleyin ve yönetin. Onaylanmamış başvurular üstte görünür.</p>
            </div>
            <a href="{{ route('admin.members.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl flex items-center transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Üyelere Dön
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif



    @if($applications->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Başvuru Bilgileri
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                İletişim
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aidat Bilgileri
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Başvuru Tarihi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Durum
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                İşlemler
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($applications as $application)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $application->name }} {{ $application->surname }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $application->member_no }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $application->birth_date ? $application->birth_date->format('d.m.Y') : 'Belirtilmemiş' }} - {{ $application->nationality }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $application->email }}</div>
                                <div class="text-sm text-gray-500">{{ $application->phone ?: 'Belirtilmemiş' }}</div>
                                <div class="text-xs text-gray-400">{{ Str::limit($application->address, 30) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ number_format($application->monthly_dues, 2) }} €
                                </div>
                                <div class="text-sm text-gray-500">
                                    @switch($application->payment_method)
                                        @case('cash')
                                            <i class="fas fa-money-bill-wave mr-1"></i>Nakit
                                            @break
                                        @case('direct_debit')
                                            <i class="fas fa-university mr-1"></i>Lastschrift
                                            @break
                                        @case('standing_order')
                                            <i class="fas fa-sync-alt mr-1"></i>Dauerauftrag
                                            @break
                                        @default
                                            Belirtilmemiş
                                    @endswitch
                                </div>
                                <div class="text-xs text-gray-400">
                                    Aile: {{ $application->family_members_count }} kişi
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ $application->application_date ? $application->application_date->format('d.m.Y') : 'Belirtilmemiş' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $application->application_date ? $application->application_date->diffForHumansTr() : 'Belirtilmemiş' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @switch($application->application_status)
                                    @case('pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Bekliyor
                                        </span>
                                        @break
                                    @case('approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            Onaylandı
                                        </span>
                                        @break
                                    @case('rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>
                                            Reddedildi
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-question mr-1"></i>
                                            Belirsiz
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button onclick="showApplicationDetails({{ $application->id }})"
                                            class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200"
                                            title="Detayları Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    @if($application->application_status === 'pending')
                                        <button onclick="approveApplication({{ $application->id }})"
                                                class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-colors duration-200"
                                                title="Onayla">
                                            <i class="fas fa-check"></i>
                                        </button>

                                        <button onclick="rejectApplication({{ $application->id }})"
                                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200"
                                                title="Reddet">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @elseif($application->application_status === 'rejected')
                                        <button onclick="approveApplication({{ $application->id }})"
                                                class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-colors duration-200"
                                                title="Onayla (Redden Onayla)">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @elseif($application->application_status === 'approved')
                                        <button onclick="resendApprovalEmail({{ $application->id }})"
                                                class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200"
                                                title="Tekrar Mail Gönder">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    @endif

                                    <a href="{{ route('member.application.pdf', $application->id) }}"
                                       target="_blank"
                                       class="text-purple-600 hover:text-purple-900 p-2 rounded-lg hover:bg-purple-50 transition-colors duration-200"
                                       title="PDF İndir (Yeni Sayfada Aç)">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $applications->links() }}
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-inbox text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Başvuru Yok</h3>
            <p class="text-gray-500">Henüz hiç üyelik başvurusu bulunmamaktadır.</p>
        </div>
    @endif
</div>

<!-- Application Details Modal -->
<div id="applicationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Başvuru Detayları</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="applicationDetails" class="space-y-4">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Reject Application Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Başvuruyu Reddet</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Red Nedeni *
                    </label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                              placeholder="Red nedenini açıklayın..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                        İptal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                        Reddet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showApplicationDetails(id) {
    fetch(`/admin/members/${id}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const details = document.getElementById('applicationDetails');
            details.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4 text-lg">
                            <i class="fas fa-user mr-2 text-blue-500"></i>
                            Kişisel Bilgiler
                        </h4>
                        <div class="space-y-3 text-sm bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Ad Soyad:</span>
                                <span class="text-gray-900">${data.name} ${data.surname}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Üye No:</span>
                                <span class="text-gray-900">${data.member_no || 'Henüz atanmamış'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Doğum Tarihi:</span>
                                <span class="text-gray-900">${data.birth_date ? new Date(data.birth_date).toLocaleDateString('tr-TR') : 'Belirtilmemiş'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Doğum Yeri:</span>
                                <span class="text-gray-900">${data.birth_place || 'Belirtilmemiş'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Uyruk:</span>
                                <span class="text-gray-900">${data.nationality || 'Belirtilmemiş'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Meslek:</span>
                                <span class="text-gray-900">${data.occupation || 'Belirtilmemiş'}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4 text-lg">
                            <i class="fas fa-address-book mr-2 text-green-500"></i>
                            İletişim Bilgileri
                        </h4>
                        <div class="space-y-3 text-sm bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">E-posta:</span>
                                <span class="text-gray-900">${data.email}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Telefon:</span>
                                <span class="text-gray-900">${data.phone || 'Belirtilmemiş'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Adres:</span>
                                <span class="text-gray-900">${data.address || 'Belirtilmemiş'}</span>
                            </div>
                        </div>

                        <h4 class="font-semibold text-gray-900 mb-4 mt-6 text-lg">
                            <i class="fas fa-credit-card mr-2 text-purple-500"></i>
                            Ödeme Bilgileri
                        </h4>
                        <div class="space-y-3 text-sm bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Aylık Aidat:</span>
                                <span class="text-gray-900">${parseFloat(data.monthly_dues || 0).toFixed(2)} €</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Ödeme Yöntemi:</span>
                                <span class="text-gray-900">${getPaymentMethodText(data.payment_method)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Ödeme Sıklığı:</span>
                                <span class="text-gray-900">${getPaymentFrequencyText(data.payment_frequency)}</span>
                            </div>
                            ${data.payment_method !== 'cash' ? `
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Hesap Sahibi:</span>
                                <span class="text-gray-900">${data.account_holder || 'Belirtilmemiş'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Banka:</span>
                                <span class="text-gray-900">${data.bank_name || 'Belirtilmemiş'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">IBAN:</span>
                                <span class="text-gray-900">${data.iban || 'Belirtilmemiş'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">BIC:</span>
                                <span class="text-gray-900">${data.bic || 'Belirtilmemiş'}</span>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="font-semibold text-gray-900 mb-4 text-lg">
                        <i class="fas fa-info-circle mr-2 text-orange-500"></i>
                        Başvuru Bilgileri
                    </h4>
                    <div class="space-y-3 text-sm bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Başvuru Tarihi:</span>
                            <span class="text-gray-900">${data.application_date ? new Date(data.application_date).toLocaleString('tr-TR') : 'Belirtilmemiş'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Başvuru Durumu:</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${getStatusBadgeClass(data.application_status)}">
                                ${getStatusText(data.application_status)}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">SEPA Anlaşması:</span>
                            <span class="text-gray-900">${data.sepa_agreement ? 'Kabul Edildi' : 'Kabul Edilmedi'}</span>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('applicationModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Detaylar yüklenirken bir hata oluştu. Lütfen tekrar deneyin.');
        });
}

function getPaymentMethodText(method) {
    switch(method) {
        case 'cash': return 'Nakit';
        case 'direct_debit': return 'Lastschrift';
        case 'standing_order': return 'Dauerauftrag';
        default: return 'Belirtilmemiş';
    }
}

function getPaymentFrequencyText(frequency) {
    switch(frequency) {
        case 'monthly': return 'Aylık';
        case 'semi_annual': return '6 Aylık';
        case 'annual': return 'Yıllık';
        default: return 'Belirtilmemiş';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'pending': return 'Bekliyor';
        case 'approved': return 'Onaylandı';
        case 'rejected': return 'Reddedildi';
        default: return 'Belirsiz';
    }
}

function getStatusBadgeClass(status) {
    switch(status) {
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'approved': return 'bg-green-100 text-green-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function closeModal() {
    document.getElementById('applicationModal').classList.add('hidden');
}

function approveApplication(id) {
    if (confirm('Bu başvuruyu onaylamak istediğinizden emin misiniz?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/members/${id}/approve`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectApplication(id) {
    document.getElementById('rejectForm').action = `/admin/members/${id}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejection_reason').value = '';
}

function resendApprovalEmail(id) {
    if (confirm('Onay mailini tekrar göndermek istediğinizden emin misiniz?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/members/${id}/resend-email`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

// Close modals when clicking outside
window.onclick = function(event) {
    const applicationModal = document.getElementById('applicationModal');
    const rejectModal = document.getElementById('rejectModal');

    if (event.target === applicationModal) {
        closeModal();
    }
    if (event.target === rejectModal) {
        closeRejectModal();
    }
}
</script>
@endsection
