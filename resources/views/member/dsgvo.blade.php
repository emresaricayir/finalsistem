@extends('layouts.member-profile')

@section('title', __('common.dsgvo_title'))

@section('content-class', 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12')

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

        <!-- Page Header -->
        <div class="bg-gradient-to-r from-teal-600 to-teal-700 rounded-2xl shadow-lg p-6 sm:p-8 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white/20 rounded-2xl flex items-center justify-center mr-4 sm:mr-6 shadow-lg">
                    <i class="fas fa-shield-alt text-white text-2xl sm:text-3xl"></i>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">
                        {{ __('common.dsgvo_title') }}
                    </h1>
                    <p class="text-teal-100 text-sm sm:text-base">
                        {{ __('common.dsgvo_subtitle') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Data Export Section (DSGVO) -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center mb-6 sm:mb-8 space-y-4 sm:space-y-0">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center mr-3 sm:mr-4 shadow-lg flex-shrink-0">
                    <i class="fas fa-download text-white text-lg sm:text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg sm:text-2xl font-bold text-gray-900">
                        {{ __('common.data_export_title') }}
                    </h2>
                    <p class="text-gray-600 mt-1 text-sm sm:text-base">
                        {{ __('common.data_export_desc') }}
                    </p>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 sm:p-6 border border-blue-200">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex-1">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 flex items-center">
                            <i class="fas fa-download text-blue-600 mr-2 sm:mr-3"></i>
                            {{ __('common.data_export_title') }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ __('common.data_export_desc') }}
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-2">
                        <a href="{{ route('member.data.export', 'json') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl font-semibold text-sm sm:text-base shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-file-code mr-2"></i>
                            {{ __('common.download_json') }}
                        </a>
                        <a href="{{ route('member.data.export', 'pdf') }}" 
                           class="bg-red-600 hover:bg-red-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl font-semibold text-sm sm:text-base shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-file-pdf mr-2"></i>
                            {{ __('common.download_pdf') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Privacy Consent Withdrawal Section -->
        @if($member->privacy_consent)
        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center mb-6 sm:mb-8 space-y-4 sm:space-y-0">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center mr-3 sm:mr-4 shadow-lg flex-shrink-0">
                    <i class="fas fa-user-shield text-white text-lg sm:text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg sm:text-2xl font-bold text-gray-900">
                        {{ __('common.consent_withdrawal_title') }}
                    </h2>
                    <p class="text-gray-600 mt-1 text-sm sm:text-base">
                        {{ __('common.consent_withdrawal_desc') }}
                    </p>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 sm:p-6 mb-4 sm:mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mr-3 mt-0.5"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-2">{{ __('common.consent_withdrawal_info_title') }}</p>
                        <p>{{ __('common.consent_withdrawal_info_desc') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('member.privacy.consent.withdraw') }}" method="POST" class="space-y-4 sm:space-y-6" id="consentWithdrawalForm">
                @csrf
                @method('PUT')
                
                <div class="flex items-start">
                    <input type="checkbox" name="confirm" id="consent_confirm" required
                           class="mt-1 w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <label for="consent_confirm" class="ml-3 text-sm text-gray-700">
                        {{ __('common.consent_withdrawal_confirm') }}
                    </label>
                </div>

                <div class="flex justify-center pt-4 sm:pt-6">
                    <button type="submit"
                            class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white py-3 sm:py-4 px-8 sm:px-12 rounded-xl font-bold text-base sm:text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex items-center w-full sm:w-auto">
                        <i class="fas fa-user-times mr-2 sm:mr-3 text-sm sm:text-base"></i>
                        <span class="text-sm sm:text-base">{{ __('common.withdraw_consent') }}</span>
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Privacy Consent Section (Rıza Yoksa) -->
        @if(!$member->privacy_consent)
        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center mb-6 sm:mb-8 space-y-4 sm:space-y-0">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mr-3 sm:mr-4 shadow-lg flex-shrink-0">
                    <i class="fas fa-user-shield text-white text-lg sm:text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg sm:text-2xl font-bold text-gray-900">
                        {{ __('common.privacy_consent_title') }}
                    </h2>
                    <p class="text-gray-600 mt-1 text-sm sm:text-base">
                        {{ __('common.privacy_consent_desc') }}
                    </p>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 sm:p-6 mb-4 sm:mb-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 mt-0.5"></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold mb-2">Gizlilik Politikası Rızası Verilmedi</p>
                        <p>Kişisel verilerinizin işlenmesi için gizlilik politikası rızası vermeniz gerekmektedir.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('member.privacy.consent.give') }}" method="POST" class="space-y-4 sm:space-y-6">
                @csrf
                @method('PUT')
                
                <div class="flex items-start">
                    <input type="checkbox" name="confirm" id="consent_give_confirm" required
                           class="mt-1 w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <label for="consent_give_confirm" class="ml-3 text-sm text-gray-700">
                        <a href="/sayfa/datenschutz" target="_blank" class="text-blue-600 hover:underline">
                            Gizlilik Politikası</a>'nı okudum ve kişisel verilerimin işlenmesine rıza gösteriyorum.
                    </label>
                </div>

                <div class="flex justify-center pt-4 sm:pt-6">
                    <button type="submit"
                            class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white py-3 sm:py-4 px-8 sm:px-12 rounded-xl font-bold text-base sm:text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex items-center w-full sm:w-auto">
                        <i class="fas fa-check-circle mr-2 sm:mr-3 text-sm sm:text-base"></i>
                        <span class="text-sm sm:text-base">Rızayı Ver</span>
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Data Deletion Request Section (DSGVO) -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center mb-6 sm:mb-8 space-y-4 sm:space-y-0">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl flex items-center justify-center mr-3 sm:mr-4 shadow-lg">
                    <i class="fas fa-trash-alt text-white text-lg sm:text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg sm:text-2xl font-bold text-gray-900">
                        {{ __('common.data_deletion_title') }}
                    </h2>
                    <p class="text-gray-600 mt-1 text-sm sm:text-base">
                        {{ __('common.data_deletion_desc') }}
                    </p>
                </div>
            </div>

            @php
                $pendingRequest = $member->deletionRequest()->where('status', 'pending')->first();
            @endphp

            @if($pendingRequest)
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 sm:p-6 mb-4 sm:mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-clock text-amber-600 mr-3 mt-0.5"></i>
                        <div>
                            <p class="font-semibold text-amber-900 mb-2">{{ __('common.deletion_request_pending') }}</p>
                            <p class="text-sm text-amber-800">{{ __('common.deletion_request_pending_desc') }}</p>
                        </div>
                    </div>
                </div>
            @else
                <form action="{{ route('member.data.deletion.request') }}" method="POST" class="space-y-4 sm:space-y-6" id="deletionForm">
                    @csrf
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 sm:p-6 mb-4 sm:mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-3 mt-0.5"></i>
                            <div class="text-sm text-red-800">
                                <p class="font-semibold mb-2">{{ __('common.data_deletion_warning') }}</p>
                                <p>{{ __('common.data_deletion_warning_desc') }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="reason" class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-comment-alt mr-2 text-red-500"></i>
                            {{ __('common.deletion_reason') }}
                            <span class="text-xs ml-1">({{ __('common.optional') }})</span>
                        </label>
                        <textarea name="reason" id="reason" rows="4"
                                  class="w-full bg-white border border-gray-300 rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300 resize-none"
                                  placeholder="{{ __('common.deletion_reason_placeholder') }}"></textarea>
                    </div>

                    <div class="flex items-start">
                        <input type="checkbox" name="confirm" id="confirm" required
                               class="mt-1 w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <label for="confirm" class="ml-3 text-sm text-gray-700">
                            {{ __('common.deletion_confirm') }}
                        </label>
                    </div>

                    <div class="flex justify-center pt-4 sm:pt-6">
                        <button type="submit"
                                class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white py-3 sm:py-4 px-8 sm:px-12 rounded-xl font-bold text-base sm:text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex items-center w-full sm:w-auto">
                            <i class="fas fa-trash-alt mr-2 sm:mr-3 text-sm sm:text-base"></i>
                            <span class="text-sm sm:text-base">{{ __('common.submit_deletion_request') }}</span>
                        </button>
                    </div>
                </form>
            @endif
        </div>
@endsection
