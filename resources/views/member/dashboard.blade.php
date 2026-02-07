@extends('layouts.member-dashboard')

@section('title', __('common.member_dashboard'))

@section('content')
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 sm:px-8 py-4 sm:py-6 rounded-2xl mb-6 sm:mb-10 shadow-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center mr-3 sm:mr-4">
                        <i class="fas fa-check-circle text-green-600 text-lg sm:text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-sm sm:text-lg">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(str_contains($member->email, '{{\App\Models\Settings::getTemporaryEmailDomain()}}'))
            <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 sm:px-8 py-4 sm:py-6 rounded-2xl mb-6 sm:mb-10 shadow-lg">
                <div class="flex items-start">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-full flex items-center justify-center mr-3 sm:mr-4 mt-1">
                        <i class="fas fa-exclamation-triangle text-amber-600 text-lg sm:text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm sm:text-lg mb-2">{{ __('common.temporary_email_detected') }}</p>
                        <p class="text-sm sm:text-base">{{ __('common.you_have_temporary_email') }} <a href="{{ route('member.profile') }}" class="underline hover:text-amber-600 font-medium">{{ __('common.from_profile_page') }}</a> {{ __('common.update_email_from_profile') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 sm:px-8 py-4 sm:py-6 rounded-2xl mb-6 sm:mb-10 shadow-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-full flex items-center justify-center mr-3 sm:mr-4">
                        <i class="fas fa-exclamation-circle text-red-600 text-lg sm:text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-sm sm:text-lg">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Payment Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 mb-8 sm:mb-12">
            <!-- Aylık Aidat Tutarı -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 card-hover">
                <div class="flex flex-col sm:flex-row items-start sm:items-center">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-teal-500 to-teal-600 rounded-2xl flex items-center justify-center mr-4 sm:mr-6 shadow-lg mb-4 sm:mb-0">
                        <i class="fas fa-euro-sign text-white text-2xl sm:text-3xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">
                            {{ __('common.monthly_dues_amount') }}
                        </p>
                        <p class="text-3xl sm:text-5xl font-bold text-gray-900 mb-2">{{ number_format($member->monthly_dues, 2) }} €</p>
                        <p class="text-sm sm:text-lg text-teal-600 font-medium">
                            @if($member->payment_method === 'cash')
                                {{ __('common.cash_payment') }}
                            @else
                                {{ __('common.payment_with') }} {{ ucfirst($member->payment_method) }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Gecikmiş Aidatlar -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 card-hover">
                <div class="flex flex-col sm:flex-row items-start sm:items-center">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl flex items-center justify-center mr-4 sm:mr-6 shadow-lg mb-4 sm:mb-0">
                        <i class="fas fa-exclamation-triangle text-white text-2xl sm:text-3xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">
                            {{ __('common.overdue_dues') }}
                        </p>
                        <p class="text-3xl sm:text-5xl font-bold text-gray-900 mb-2">{{ $unpaidDues->where('due_date', '<', now())->count() }}</p>
                        <p class="text-sm sm:text-lg text-red-600 font-medium">
                            {{ number_format($unpaidDues->where('due_date', '<', now())->sum('amount'), 2) }} € {{ __('common.total_debt') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overdue Dues -->
        @if($unpaidDues->where('due_date', '<', now())->count() > 0)
        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 mb-8 sm:mb-12">
            <div class="flex flex-col sm:flex-row items-start sm:items-center mb-6 sm:mb-8 space-y-4 sm:space-y-0">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl flex items-center justify-center mr-3 sm:mr-4 shadow-lg">
                    <i class="fas fa-exclamation-triangle text-white text-lg sm:text-2xl"></i>
                </div>
                <h2 class="text-lg sm:text-2xl font-bold text-gray-900">
                    {{ __('common.my_overdue_dues') }}
                </h2>
            </div>

            <div class="overflow-x-auto rounded-2xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 sm:px-8 py-4 sm:py-6 text-left text-xs sm:text-sm font-bold text-gray-700 uppercase tracking-wider">
                                {{ __('common.period') }}
                            </th>
                            <th class="px-4 sm:px-8 py-4 sm:py-6 text-left text-xs sm:text-sm font-bold text-gray-700 uppercase tracking-wider">
                                {{ __('common.amount') }}
                            </th>
                            <th class="px-4 sm:px-8 py-4 sm:py-6 text-left text-xs sm:text-sm font-bold text-gray-700 uppercase tracking-wider">
                                {{ __('common.due_date') }}
                            </th>
                            <th class="px-4 sm:px-8 py-4 sm:py-6 text-left text-xs sm:text-sm font-bold text-gray-700 uppercase tracking-wider">
                                {{ __('common.status') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($unpaidDues->where('due_date', '<', now())->take(10) as $due)
                        <tr class="hover:bg-gray-50 transition-colors duration-300">
                            <td class="px-4 sm:px-8 py-4 sm:py-6 whitespace-nowrap text-sm sm:text-base font-semibold text-gray-900">
                                {{ $due->due_date->formatTr('F Y') }}
                            </td>
                            <td class="px-4 sm:px-8 py-4 sm:py-6 whitespace-nowrap text-sm sm:text-base text-gray-900 font-bold">
                                {{ number_format($due->amount, 2) }} €
                            </td>
                            <td class="px-4 sm:px-8 py-4 sm:py-6 whitespace-nowrap text-sm sm:text-base text-gray-600">
                                {{ $due->due_date->format('d.m.Y') }}
                            </td>
                            <td class="px-4 sm:px-8 py-4 sm:py-6 whitespace-nowrap">
                                <span class="inline-flex px-2 sm:px-4 py-1 sm:py-2 text-xs sm:text-sm font-bold rounded-xl bg-red-100 text-red-800 border border-red-200">
                                    {{ __('common.not_paid') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif



        <!-- Quick Payment Summary -->
        @if($allPayments->count() > 0)
        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-8 mb-12">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ __('common.payment_summary') }}
                    </h2>
                </div>
                <a href="{{ route('member.payments') }}" class="inline-flex items-center bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl transition-all duration-300 text-base font-semibold shadow-lg hover:shadow-xl">
                    <i class="fas fa-receipt mr-2"></i>
                    {{ __('common.all_my_payments') }}
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 bg-gray-50 rounded-2xl">
                    <div class="text-4xl font-bold text-gray-900 mb-3">{{ $allPayments->count() }}</div>
                    <div class="text-base text-gray-600 font-semibold">
                        {{ __('common.total_payments') }}
                    </div>
                </div>
                <div class="text-center p-6 bg-gray-50 rounded-2xl">
                    <div class="text-4xl font-bold text-gray-900 mb-3">{{ number_format($allPayments->count() > 0 ? $allPayments->sum('amount') : 0, 0) }} €</div>
                    <div class="text-base text-gray-600 font-semibold">
                        {{ __('common.total_amount') }}
                    </div>
                </div>
                <div class="text-center p-6 bg-gray-50 rounded-2xl">
                    <div class="text-4xl font-bold text-gray-900 mb-3">{{ $allPayments->first() ? $allPayments->first()->payment_date->format('m/Y') : '-' }}</div>
                    <div class="text-base text-gray-600 font-semibold">
                        {{ __('common.last_payment') }}
                    </div>
                </div>
            </div>
        </div>
        @endif

@endsection

