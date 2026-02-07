@extends('layouts.member-profile')

@section('title', __('common.member_profile'))

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

        <!-- Member Information Card -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center mb-6 sm:mb-8 space-y-4 sm:space-y-0">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-teal-500 to-teal-600 rounded-2xl flex items-center justify-center mr-3 sm:mr-4 shadow-lg">
                    <i class="fas fa-user text-white text-lg sm:text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg sm:text-2xl font-bold text-gray-900">
                        {{ __('common.member_information') }}
                    </h2>
                    <p class="text-gray-600 mt-1 text-sm sm:text-base">
                        {{ __('common.your_personal_data') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 sm:p-6 border border-blue-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-user text-blue-600 text-lg mr-2"></i>
                        <label class="text-sm font-semibold text-blue-700 uppercase tracking-wider">
                            {{ __('common.name_and_surname') }}
                        </label>
                    </div>
                    <p class="text-gray-900 text-lg font-semibold">{{ $member->name }} {{ $member->surname }}</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-id-badge text-green-600 text-lg mr-2"></i>
                        <label class="text-sm font-semibold text-green-700 uppercase tracking-wider">
                            {{ __('common.member_number') }}
                        </label>
                    </div>
                    <p class="text-gray-900 font-mono text-lg font-semibold">{{ $member->member_no }}</p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-envelope text-purple-600 text-lg mr-2"></i>
                        <label class="text-sm font-semibold text-purple-700 uppercase tracking-wider">
                            {{ __('common.email') }}
                        </label>
                    </div>
                    <p class="text-gray-900">
                        @if(str_contains($member->email, '@uye.com'))
                            <span class="text-amber-600 font-medium">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                {{ $member->email }}
                            </span>
                            <span class="ml-2 px-2 py-1 text-xs bg-amber-100 text-amber-800 rounded-full">
                                {{ __('common.temporary_email') }}
                            </span>
                        @else
                            {{ $member->email }}
                        @endif
                    </p>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 border border-red-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-calendar text-red-600 text-lg mr-2"></i>
                        <label class="text-sm font-semibold text-red-700 uppercase tracking-wider">
                            {{ __('common.birth_date') }}
                        </label>
                    </div>
                    <p class="text-gray-900 text-lg font-semibold">
                        {{ $member->birth_date ? $member->birth_date->format('d.m.Y') : __('common.not_specified') }}
                    </p>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border border-orange-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-map-marker-alt text-orange-600 text-lg mr-2"></i>
                        <label class="text-sm font-semibold text-orange-700 uppercase tracking-wider">
                            {{ __('common.birth_place') }}
                        </label>
                    </div>
                    <p class="text-gray-900 text-lg font-semibold">
                        {{ $member->birth_place ?: __('common.not_specified') }}
                    </p>
                </div>

                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-6 border border-indigo-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-flag text-indigo-600 text-lg mr-2"></i>
                        <label class="text-sm font-semibold text-indigo-700 uppercase tracking-wider">
                            {{ __('common.nationality') }}
                        </label>
                    </div>
                    <p class="text-gray-900 text-lg font-semibold">
                        {{ $member->nationality ?: __('common.not_specified') }}
                    </p>
                </div>

                <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl p-6 border border-pink-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-briefcase text-pink-600 text-lg mr-2"></i>
                        <label class="text-sm font-semibold text-pink-700 uppercase tracking-wider">
                            {{ __('common.occupation') }}
                        </label>
                    </div>
                    <p class="text-gray-900 text-lg font-semibold">
                        {{ $member->occupation ?: __('common.not_specified') }}
                    </p>
                </div>

                <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl p-6 border border-teal-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-calendar-plus text-teal-600 text-lg mr-2"></i>
                        <label class="text-sm font-semibold text-teal-700 uppercase tracking-wider">
                            {{ __('common.membership_date') }}
                        </label>
                    </div>
                    <p class="text-gray-900 text-lg font-semibold">{{ $member->membership_date->format('d.m.Y') }}</p>
                </div>

                <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-xl p-6 border border-cyan-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-home text-cyan-600 text-lg mr-2"></i>
                        <label class="text-sm font-semibold text-cyan-700 uppercase tracking-wider">
                            {{ __('common.address') }}
                        </label>
                    </div>
                    <p class="text-gray-900 text-lg font-semibold">
                        {{ $member->address ?: __('common.not_specified') }}
                    </p>
                </div>
            </div>

            <!-- Data Export Section (DSGVO) -->
            <div class="mt-6 sm:mt-8 pt-6 sm:pt-8 border-t border-gray-200">
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

        <!-- Edit Profile Form -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center mb-6 sm:mb-8 space-y-4 sm:space-y-0">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center mr-3 sm:mr-4 shadow-lg">
                    <i class="fas fa-edit text-white text-lg sm:text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg sm:text-2xl font-bold text-gray-900">
                        {{ __('common.update_information') }}
                    </h2>
                    <p class="text-gray-600 mt-1 text-sm sm:text-base">
                        {{ __('common.update_information_desc') }}
                    </p>
                </div>
            </div>

            <form action="{{ route('member.profile.update') }}" method="POST" class="space-y-6 sm:space-y-8">
                @csrf
                @method('PUT')

                @if(str_contains($member->email, '@' . \App\Models\Settings::getTemporaryEmailDomain()))
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 sm:p-4 mb-4 sm:mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-amber-600 mr-3 mt-0.5"></i>
                            <div class="text-sm text-amber-800">
                                <p class="font-semibold mb-1">
                                    {{ __('common.temporary_email_detected') }}
                                </p>
                                <p>
                                    {{ __('common.update_temporary_email_desc') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- İletişim Bilgileri -->
                <div class="bg-gray-50 rounded-2xl p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 sm:mb-6 flex items-center">
                        <i class="fas fa-phone text-blue-600 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                        {{ __('common.contact_information_label') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-envelope mr-2 text-blue-500"></i>{{ __('common.email_address_label') }}
                                @if(str_contains($member->email, '@' . \App\Models\Settings::getTemporaryEmailDomain()))
                                    <span class="ml-2 px-2 py-1 text-xs bg-amber-100 text-amber-800 rounded-full">
                                        {{ __('common.update_required') }}
                                    </span>
                                @endif
                            </label>
                            <input type="email" name="email" id="email"
                                   value="{{ old('email', $member->email) }}"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300
                                   @if(str_contains($member->email, '@uye.com')) border-amber-400 bg-amber-50 @endif"
                                   placeholder="ornek@email.com">
                            @if(str_contains($member->email, '@uye.com'))
                                <p class="text-amber-600 text-sm mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    {{ __('common.update_temporary_email') }}
                                </p>
                            @endif
                            @error('email')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-phone mr-2 text-blue-500"></i>{{ __('common.phone_label') }}
                            </label>
                            <input type="tel" name="phone" id="phone"
                                   value="{{ old('phone', $member->phone) }}"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                   placeholder="+49 5XX XXX XX XX">
                            @error('phone')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Kişisel Bilgiler -->
                <div class="bg-gray-50 rounded-2xl p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 sm:mb-6 flex items-center">
                        <i class="fas fa-user text-green-600 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                        {{ __('common.personal_information_label') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="birth_date" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-calendar mr-2 text-green-500"></i>{{ __('common.birth_date_label') }}
                            </label>
                            <input type="date" name="birth_date" id="birth_date" lang="tr"
                                   value="{{ old('birth_date', $member->birth_date ? $member->birth_date->format('Y-m-d') : '') }}"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300">
                            @error('birth_date')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="birth_place" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-map-marker-alt mr-2 text-green-500"></i>{{ __('common.birth_place_label') }}
                            </label>
                            <input type="text" name="birth_place" id="birth_place"
                                   value="{{ old('birth_place', $member->birth_place) }}"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300"
                                   placeholder="{{ __('common.birth_place_placeholder') }}">
                            @error('birth_place')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nationality" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-flag mr-2 text-green-500"></i>{{ __('common.nationality_label') }}
                            </label>
                            <input type="text" name="nationality" id="nationality"
                                   value="{{ old('nationality', $member->nationality) }}"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300"
                                   placeholder="{{ __('common.nationality_placeholder') }}">
                            @error('nationality')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="occupation" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-briefcase mr-2 text-green-500"></i>{{ __('common.occupation_label') }}
                            </label>
                            <input type="text" name="occupation" id="occupation"
                                   value="{{ old('occupation', $member->occupation) }}"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300"
                                   placeholder="{{ __('common.occupation_placeholder') }}">
                            @error('occupation')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Adres Bilgileri -->
                <div class="bg-gray-50 rounded-2xl p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 sm:mb-6 flex items-center">
                        <i class="fas fa-map-marker-alt text-purple-600 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                        Adres Bilgileri
                        <span class="text-xs sm:text-sm text-gray-600 ml-1 sm:ml-2">(Adressinformationen)</span>
                    </h3>

                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-home mr-2 text-purple-500"></i>{{ __('common.address_label') }} *
                        </label>
                        <textarea name="address" id="address" rows="4" required
                                  class="w-full bg-white border border-gray-300 rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 resize-none"
                                  placeholder="{{ __('common.address_placeholder') }}">{{ old('address', $member->address) }}</textarea>
                        @error('address')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Şifre Değiştirme -->
                <div class="bg-gray-50 rounded-2xl p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 sm:mb-6 flex items-center">
                        <i class="fas fa-lock text-red-600 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                        {{ __('common.change_password') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-key mr-2 text-red-500"></i>{{ __('common.current_password') }}
                            </label>
                            <input type="password" name="current_password" id="current_password"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300"
                                   placeholder="{{ __('common.current_password_placeholder') }}">
                            @error('current_password')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-lock mr-2 text-red-500"></i>{{ __('common.new_password') }}
                            </label>
                            <input type="password" name="new_password" id="new_password"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300"
                                   placeholder="{{ __('common.new_password_placeholder') }}">
                            @error('new_password')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-lock mr-2 text-red-500"></i>{{ __('common.confirm_password') }}
                            </label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300"
                                   placeholder="{{ __('common.confirm_password_placeholder') }}">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center pt-4 sm:pt-6">
                    <button type="submit"
                            class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white py-3 sm:py-4 px-8 sm:px-12 rounded-xl font-bold text-base sm:text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex items-center w-full sm:w-auto">
                        <i class="fas fa-save mr-2 sm:mr-3 text-sm sm:text-base"></i>
                        <span class="text-sm sm:text-base">{{ __('common.save_changes_button') }}</span>
                    </button>
                </div>
                </form>
            </div>
        </div>
@endsection
