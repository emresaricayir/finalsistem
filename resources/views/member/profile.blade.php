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
