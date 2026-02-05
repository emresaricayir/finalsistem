@extends('admin.layouts.app')

@section('title', 'Eğitim Aidat Ödemeleri')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Ödenmiş Aidatlar</h1>
                <p class="text-slate-600 mt-1">Velilerin ödenmiş aidat kayıtları</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.education-payments.bulk') }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-money-bill-wave mr-2"></i>
                    Toplu Ödeme
                </a>
                <a href="{{ route('admin.education-members.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Velilere Dön
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-receipt text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Toplam Ödeme</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $totalPayments }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-lira-sign text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Toplam Tutar</p>
                    <p class="text-2xl font-bold text-slate-900">₺{{ number_format($totalAmount, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-calendar-day text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Bu Ay Ödeme</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $thisMonthPayments }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Bu Ay Tutar</p>
                    <p class="text-2xl font-bold text-slate-900">₺{{ number_format($thisMonthAmount, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <form method="GET" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Veli veya öğrenci adı ile ara..."
                           class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                </div>
            </div>
            <div class="flex gap-3">
                <select name="year" class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tüm Yıllar</option>
                    @for($i = now()->year; $i >= now()->year - 1; $i--)
                        <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <select name="month" class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tüm Aylar</option>
                    @php
                        $monthNames = [
                            1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
                            5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
                            9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
                        ];
                    @endphp
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>{{ $monthNames[$i] }}</option>
                    @endfor
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       placeholder="Vade Başlangıç Tarihi"
                       class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       placeholder="Vade Bitiş Tarihi"
                       class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrele
                </button>
                <a href="{{ route('admin.education-payments.index') }}"
                   class="px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Temizle
                </a>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        @if($payments->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Veli / Öğrenci
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Tutar
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Vade Tarihi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Ödeme Tarihi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Notlar
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                İşlemler
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($payments as $payment)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->educationMember)
                                    <div class="text-sm font-medium text-slate-900">
                                        {{ $payment->educationMember->full_name }}
                                    </div>
                                    <div class="text-sm text-slate-500">
                                        {{ $payment->educationMember->student_full_name }}
                                    </div>
                                @else
                                    <div class="text-sm font-medium text-red-600">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Üye Bulunamadı
                                    </div>
                                    <div class="text-sm text-slate-500">
                                        ID: {{ $payment->education_member_id }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">
                                    €{{ number_format($payment->amount, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                {{ $payment->due_date->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                {{ $payment->paid_date ? $payment->paid_date->format('d.m.Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ $payment->notes ? Str::limit($payment->notes, 50) : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="deletePayment({{ $payment->id }})"
                                        class="text-red-600 hover:text-red-900 transition-colors"
                                        title="Ödemeyi Sil">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $payments->links() }}
            </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-receipt text-2xl text-slate-400"></i>
                </div>
                <h3 class="text-lg font-medium text-slate-900 mb-2">Henüz ödeme kaydı bulunmuyor</h3>
                <p class="text-slate-600 mb-6">
                    Velilerin aidat ödemeleri burada görüntülenecek.
                </p>
                <a href="{{ route('admin.education-members.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-users mr-2"></i>
                    Velileri Görüntüle
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Ödemeyi Sil</h3>
            </div>
            <div class="px-6 py-4">
                <p class="text-slate-600 mb-4">
                    Bu ödemeyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz ve aidat bekleyen duruma getirilecektir.
                </p>
                <div class="flex items-center justify-end space-x-3">
                    <button onclick="closeDeleteModal()"
                            class="px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white text-sm font-medium rounded-lg transition-colors">
                        İptal
                    </button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deletePayment(paymentId) {
    document.getElementById('deleteForm').action = `/admin/education-payments/${paymentId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>

@endsection
