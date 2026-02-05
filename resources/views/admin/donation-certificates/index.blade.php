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
            <a href="{{ route('admin.payments.index', ['open_bulk_receipt' => 1]) }}"
               class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-3 rounded-xl flex items-center justify-center font-medium shadow-lg hover:shadow-xl transition-all duration-200 group">
                <div class="bg-white/20 p-1 rounded-lg mr-3 group-hover:bg-white/30 transition-all duration-200">
                    <i class="fas fa-plus text-sm"></i>
                </div>
                <div>
                    <div class="font-semibold">Yeni Spendenbescheinigung Oluştur</div>
                </div>
            </a>
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
@endsection

