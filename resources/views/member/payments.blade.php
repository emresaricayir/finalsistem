@extends('layouts.member-payments')

@section('title', __('common.member_payments'))

@section('content')
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-8 py-6 rounded-2xl mb-10 shadow-lg">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-lg">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Payment Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-8 card-hover">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-check-circle text-white text-2xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">
                        {{ __('common.paid_dues') }}
                    </p>
                    <p class="text-4xl font-bold text-gray-900 mb-2">{{ $allPayments->count() }}</p>
                    <p class="text-lg text-green-600 font-medium">
                        {{ number_format($allPayments->sum('amount'), 2) }} € {{ __('common.total') }}
                    </p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-8 card-hover">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-calendar text-white text-2xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">
                        {{ __('common.monthly_dues_amount') }}
                    </p>
                    <p class="text-4xl font-bold text-gray-900 mb-2">{{ number_format($member->monthly_dues, 2) }} €</p>
                    <p class="text-lg text-blue-600 font-medium">
                        @if($member->payment_method === 'cash')
                            {{ __('common.cash_payment') }}
                        @else
                            {{ __('common.payment_with') }} {{ ucfirst($member->payment_method) }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-8 mb-12">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                        <i class="fas fa-history text-white text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ __('common.payment_history') }}
                    </h2>
                </div>
            </div>

            @if($allPayments->count() > 0)
                <div class="rounded-2xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 table-fixed min-w-[800px]">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-28 px-4 sm:px-8 py-4 sm:py-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">
                                    {{ __('common.date') }}
                                </th>
                                <th class="w-48 px-4 sm:px-8 py-4 sm:py-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">
                                    {{ __('common.period') }}
                                </th>
                                <th class="w-24 px-4 sm:px-8 py-4 sm:py-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">
                                    {{ __('common.amount') }}
                                </th>
                                <th class="w-28 px-4 sm:px-8 py-4 sm:py-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">
                                    {{ __('common.status') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($allPayments as $payment)
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 border-b border-gray-100">
                                <td class="px-4 sm:px-8 py-4 sm:py-6 text-sm sm:text-base font-semibold text-gray-900">
                                    @if($payment->payment_date)
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar-alt text-blue-500 mr-2 text-sm"></i>
                                            <span class="bg-gray-100 px-3 py-1 rounded-lg text-sm font-medium">
                                                {{ $payment->payment_date->format('d.m.Y') }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm bg-gray-100 px-3 py-1 rounded-lg">
                                            {{ __('common.not_specified') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-8 py-4 sm:py-6 text-sm sm:text-base text-gray-900">
                                    @if($payment->dues && $payment->dues->count() > 0)
                                        <div class="space-y-3">
                                            @foreach($payment->duesByYear as $year => $yearDues)
                                                <div class="space-y-2">
                                                    <div class="flex items-center">
                                                        <span class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-3 py-1 rounded-lg text-sm font-bold shadow-sm mr-2">
                                                            {{ $year }}
                                                        </span>
                                                        <div class="flex flex-wrap gap-1">
                                                            @php
                                                                $sortedDues = collect($yearDues)->sortBy(function($due) {
                                                                    return (int) $due->month;
                                                                })->values();
                                                            @endphp
                                                            @foreach($sortedDues as $due)
                                                                <span class="bg-gradient-to-r from-green-600 to-green-700 text-white px-2 py-1 rounded-lg text-xs font-semibold shadow-sm">
                                                                    {{ $due->month }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($payment->due && $payment->due->due_date)
                                        <div class="flex items-center">
                                            <span class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-3 py-1 rounded-lg text-sm font-bold shadow-sm mr-2">
                                                {{ $payment->due->due_date->year }}
                                            </span>
                                            <span class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-2 py-1 rounded-lg text-xs font-semibold shadow-sm">
                                                {{ $payment->due->due_date->month }}
                                            </span>
                                        </div>
                                    @elseif($payment->due)
                                        <div class="flex items-center">
                                            <span class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-3 py-1 rounded-lg text-sm font-bold shadow-sm mr-2">
                                                {{ $payment->due->year }}
                                            </span>
                                            <span class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-2 py-1 rounded-lg text-xs font-semibold shadow-sm">
                                                {{ $payment->due->month }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs bg-gray-100 px-3 py-1.5 rounded-full">
                                            {{ __('common.not_specified') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-8 py-4 sm:py-6 text-sm sm:text-base text-gray-900 font-bold">
                                    <div class="flex items-center">
                                        <i class="fas fa-euro-sign text-green-500 mr-2 text-sm"></i>
                                        <span class="bg-green-100 text-green-800 px-3 py-1.5 rounded-lg text-sm font-bold">
                                            {{ number_format($payment->amount, 2) }} €
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-8 py-4 sm:py-6">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2 text-sm"></i>
                                        <span class="bg-gradient-to-r from-green-500 to-green-600 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-sm">
                                            {{ __('common.paid') }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-receipt text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ __('common.no_payment_made') }}
                    </h3>
                    <p class="text-gray-600 text-lg">
                        {{ __('common.no_payment_records') }}
                    </p>
                </div>
            @endif

            <!-- Pagination -->
            @if($allPayments->hasPages())
                <div class="mt-8 flex justify-center">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Toplam {{ $allPayments->total() }} ödeme kaydından {{ $allPayments->firstItem() }}-{{ $allPayments->lastItem() }} arası gösteriliyor
                            </div>
                            <div class="flex space-x-1">
                                @if ($allPayments->onFirstPage())
                                    <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">Önceki</span>
                                @else
                                    <a href="{{ $allPayments->previousPageUrl() }}" class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Önceki</a>
                                @endif

                                @foreach ($allPayments->getUrlRange(1, $allPayments->lastPage()) as $page => $url)
                                    @if ($page == $allPayments->currentPage())
                                        <span class="px-3 py-2 text-white bg-green-600 rounded-lg">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if ($allPayments->hasMorePages())
                                    <a href="{{ $allPayments->nextPageUrl() }}" class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Sonraki</a>
                                @else
                                    <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">Sonraki</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>


@endsection
