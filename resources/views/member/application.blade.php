<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['form_title'] ?? 'Üyelik Başvurusu' }} - {{ $settings['organization_name'] }}</title>

    <!-- Favicon -->
    @if(\App\Models\Settings::hasFavicon())
        <link rel="icon" type="image/x-icon" href="{{ \App\Models\Settings::getFaviconUrl() }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ \App\Models\Settings::getFaviconUrl() }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        'teal': {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                            950: '#042f2e'
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Theme Styles -->
    @include('partials.theme-styles')
    
    <style>
        .theme-link-icon {
            color: var(--theme-link-color) !important;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        input[type="date"]:focus,
        input[type="password"]:focus,
        select:focus,
        textarea:focus {
            border-color: var(--theme-link-color) !important;
            --tw-ring-color: var(--theme-link-color) !important;
            box-shadow: 0 0 0 2px rgba(13, 148, 136, 0.2) !important;
        }
        input[type="checkbox"]:focus,
        input[type="radio"]:focus {
            --tw-ring-color: var(--theme-link-color) !important;
        }
        input[type="checkbox"]:checked,
        input[type="radio"]:checked {
            background-color: var(--theme-link-color) !important;
            border-color: var(--theme-link-color) !important;
        }
        .focus\:ring-teal-500:focus {
            --tw-ring-color: var(--theme-link-color) !important;
        }
        .focus\:border-teal-500:focus {
            border-color: var(--theme-link-color) !important;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    @include('partials.header-menu-wrapper')

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Form Title -->
        <div class="text-center mb-8">
            <h1 class="text-2xl md:text-3xl font-bold mb-2" style="color: var(--theme-hover-color);">
                {{ __('common.online_membership_form') }}
            </h1>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-lg mb-8">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle mr-3 mt-0.5"></i>
                    <div>
                        <h4 class="font-semibold mb-2">{{ __('common.please_fix_errors') }}</h4>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('member.application.store') }}" method="POST" class="space-y-8" autocomplete="off">
            @csrf

            <!-- Honeypot Field - Bot koruması (görünmez) -->
            <div style="position: absolute !important; left: -9999px !important; width: 1px !important; height: 1px !important; overflow: hidden !important; opacity: 0 !important; pointer-events: none !important;" aria-hidden="true">
                <label for="company_name" style="display: none;">Şirket Adı (boş bırakın)</label>
                <input type="text" 
                       name="company_name" 
                       id="company_name" 
                       tabindex="-1" 
                       autocomplete="off"
                       value=""
                       style="position: absolute !important; left: -9999px !important;">
            </div>

            <!-- Personal Information Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4" style="background-color: rgba(13, 148, 136, 0.1);">
                        <i class="fas fa-user text-xl" style="color: var(--theme-link-color);"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ __('common.personal_information') }}</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2" style="color: var(--theme-link-color);"></i>{{ __('common.first_name') }} *
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg transition-colors"
                               style="--tw-ring-color: var(--theme-link-color);"
                               onfocus="this.style.borderColor='var(--theme-link-color)'; this.style.boxShadow='0 0 0 2px rgba(13, 148, 136, 0.2)';"
                               onblur="this.style.borderColor=''; this.style.boxShadow='';">
                    </div>

                    <div>
                        <label for="surname" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 theme-link-icon"></i>{{ __('common.last_name') }} *
                        </label>
                        <input type="text" name="surname" id="surname" value="{{ old('surname') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-venus-mars mr-2 theme-link-icon"></i>{{ __('common.gender') }}
                        </label>
                        <select name="gender" id="gender"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                            <option value="">{{ __('common.gender_select') }}</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('common.male') }}</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('common.female') }}</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-home mr-2 theme-link-icon"></i>{{ __('common.address') }} *
                        </label>
                        <textarea name="address" id="address" rows="3" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">{{ old('address') }}</textarea>
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 theme-link-icon"></i>{{ __('common.birth_date') }} *
                        </label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                    </div>

                    <div>
                        <label for="birth_place" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 theme-link-icon"></i>{{ __('common.birth_place') }} *
                        </label>
                        <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                    </div>

                    <div>
                        <label for="nationality" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-flag mr-2 theme-link-icon"></i>{{ __('common.nationality') }} *
                        </label>
                        <input type="text" name="nationality" id="nationality" value="{{ old('nationality') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                    </div>

                    <div>
                        <label for="occupation" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-briefcase mr-2 theme-link-icon"></i>{{ __('common.occupation') }} *
                        </label>
                        <input type="text" name="occupation" id="occupation" value="{{ old('occupation') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2 theme-link-icon"></i>{{ __('common.phone') }}
                        </label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 theme-link-icon"></i>{{ __('common.email') }} *
                        </label>
                        <input type="email" name="email" id="email" value="" required autocomplete="off"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors"
                               placeholder="ornek@email.com">
                    </div>
                </div>
            </div>

            <!-- Account Information Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-lock text-gray-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ __('common.account_information') }}</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-key mr-2 text-gray-600"></i>{{ __('common.password') }} *
                        </label>
                        <input type="password" name="password" id="password" value="" required autocomplete="new-password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors"
                               placeholder="{{ __('common.password_min_chars') }}">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-key mr-2 text-gray-600"></i>{{ __('common.password_confirmation') }} *
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" value="" required autocomplete="new-password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors"
                               placeholder="{{ __('common.password_repeat_placeholder') }}">
                    </div>
                </div>

                <div class="mt-6 p-4 rounded-lg border" style="background-color: rgba(13, 148, 136, 0.05); border-color: rgba(13, 148, 136, 0.2);">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle mr-3 mt-0.5" style="color: var(--theme-link-color);"></i>
                        <div class="text-sm" style="color: var(--theme-hover-color);">
                            <p class="font-semibold mb-1">{{ __('common.member_panel_access') }}</p>
                            <p>{{ __('common.member_panel_access_desc') }}</p>
                            <p class="mt-2 font-semibold" style="color: var(--theme-hover-color);">
                                <i class="fas fa-envelope mr-1"></i>
                                {{ __('common.username_info') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-euro-sign text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ __('common.payment_information') }}</h3>
                    </div>
                </div>

                <!-- Monthly Dues -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-4">
                        <i class="fas fa-coins mr-2 text-green-600"></i>{{ __('common.monthly_dues') }} ({{ __('common.monthly_dues_min') }} {{ \App\Models\Settings::getMinimumMonthlyDues() }} €) *
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @foreach(\App\Models\Settings::getDuesOptions() as $amount)
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="monthly_dues" value="{{ $amount }}" {{ old('monthly_dues') == $amount ? 'checked' : '' }} required
                                   class="w-4 h-4 theme-link-icon border-gray-300 focus:ring-teal-500">
                            <span class="ml-2 font-medium">{{ $amount }} €</span>
                        </label>
                        @endforeach
                        <div class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <input type="radio" name="monthly_dues" value="custom" {{ old('monthly_dues') == 'custom' ? 'checked' : '' }} required
                                   class="w-4 h-4 theme-link-icon border-gray-300 focus:ring-teal-500">
                            <input type="number" name="custom_amount" value="{{ old('custom_amount') }}" min="{{ \App\Models\Settings::getMinimumMonthlyDues() }}" step="0.01"
                                   class="ml-2 w-16 px-2 py-1 border border-gray-300 rounded text-sm" placeholder="€">
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-4">
                        <i class="fas fa-credit-card mr-2 text-green-600"></i>{{ __('common.payment_method') }} *
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="cash" {{ old('payment_method') == 'cash' ? 'checked' : '' }} required
                                   class="w-4 h-4 theme-link-icon border-gray-300 focus:ring-teal-500" onchange="togglePaymentFrequency()">
                            <div class="ml-3">
                                <p class="font-medium">{{ __('common.cash_payment') }}</p>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }} required
                                   class="w-4 h-4 theme-link-icon border-gray-300 focus:ring-teal-500" onchange="togglePaymentFrequency()">
                            <div class="ml-3">
                                <p class="font-medium">{{ __('common.bank_transfer') }}</p>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="lastschrift_monthly" {{ old('payment_method') == 'lastschrift_monthly' ? 'checked' : '' }} required
                                   class="w-4 h-4 theme-link-icon border-gray-300 focus:ring-teal-500" onchange="togglePaymentFrequency()">
                            <div class="ml-3">
                                <p class="font-medium">{{ __('common.lastschrift_monthly') }}</p>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="lastschrift_semi_annual" {{ old('payment_method') == 'lastschrift_semi_annual' ? 'checked' : '' }} required
                                   class="w-4 h-4 theme-link-icon border-gray-300 focus:ring-teal-500" onchange="togglePaymentFrequency()">
                            <div class="ml-3">
                                <p class="font-medium">{{ __('common.lastschrift_semi_annual') }}</p>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="lastschrift_annual" {{ old('payment_method') == 'lastschrift_annual' ? 'checked' : '' }} required
                                   class="w-4 h-4 theme-link-icon border-gray-300 focus:ring-teal-500" onchange="togglePaymentFrequency()">
                            <div class="ml-3">
                                <p class="font-medium">{{ __('common.lastschrift_annual') }}</p>
                            </div>
                        </label>
                    </div>
                </div>


                <!-- Bank Details (Only shown when direct_debit is selected) -->
                <div id="bankDetailsSection" class="border-t border-gray-200 pt-8 hidden">
                    <h4 class="text-lg font-semibold text-gray-900 mb-6">
                        <i class="fas fa-university mr-2 text-green-600"></i>{{ __('common.account_details') }}
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="account_holder" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ __('common.account_holder_label') }} *
                            </label>
                            <input type="text" name="account_holder" id="account_holder" value="{{ old('account_holder') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        </div>

                        <div>
                            <label for="bank_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ __('common.bank_name_label') }} *
                            </label>
                            <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        </div>

                        <div>
                            <label for="iban" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ __('common.iban_account_number') }} *
                            </label>
                            <input type="text" name="iban" id="iban" value="{{ old('iban') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        </div>

                        <div>
                            <label for="bic" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ __('common.bic_swift_code') }} *
                            </label>
                            <input type="text" name="bic" id="bic" value="{{ old('bic') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEPA Mandate Agreement (Only shown when direct_debit is selected) -->
            <div id="sepaAgreementSection" class="bg-blue-50 border border-blue-200 rounded-2xl p-6 hidden">
                <div class="flex items-start mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-file-contract text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-blue-900 mb-2">
                            📄 {{ __('common.sepa_mandate_title') }}
                        </h4>
                        <p class="text-sm text-blue-700">
                            {{ __('common.sepa_mandate_desc') }}
                        </p>
                    </div>
                </div>

                <!-- Legal Text (Single Language) -->
                <div class="mb-6 border-l-4 {{ app()->getLocale() === 'de' ? 'border-blue-500' : 'border-red-500' }} pl-4">
                    <h5 class="font-semibold text-gray-900 mb-3">
                        @if(app()->getLocale() === 'de')
                            🇩🇪 {{ __('common.german_legal_text') }}
                        @else
                            🇹🇷 {{ __('common.turkish_legal_text') }}
                        @endif
                    </h5>
                    <div class="space-y-3 text-gray-700">
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <p class="text-sm leading-relaxed">
                                @if(app()->getLocale() === 'de')
                                    {!! str_replace(':organization', '<strong>' . $settings['organization_name'] . '</strong>', __('common.sepa_german_legal_text_content')) !!}
                                @else
                                    {!! str_replace(':organization', '<strong>' . $settings['organization_name'] . '</strong>', __('common.sepa_turkish_legal_text_content')) !!}
                                @endif
                            </p>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 p-3 rounded-lg">
                            <p class="font-semibold text-yellow-800 mb-1">⚠️ {{ __('common.important_notice') }}</p>
                            <p class="text-yellow-700 text-sm leading-relaxed">
                                {{ __('common.important_notice_desc') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Agreement Checkbox -->
                <div class="flex items-start space-x-3 p-4 bg-white rounded-lg border border-gray-200">
                    <input type="checkbox" name="sepa_agreement" id="sepa_agreement" value="1"
                           class="w-5 h-5 theme-link-icon border-2 border-gray-300 rounded focus:ring-teal-500 focus:ring-2 mt-0.5">
                    <label for="sepa_agreement" class="text-sm text-gray-800 cursor-pointer leading-relaxed">
                        {{ __('common.sepa_agreement_text') }} *
                    </label>
                </div>
            </div>

            <!-- Privacy Policy Consent (DSGVO) -->
            <div class="bg-teal-50 border border-teal-200 rounded-2xl p-6 mb-6">
                <div class="flex items-start mb-4">
                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-shield-alt text-teal-600"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-teal-900 mb-2">
                            🔒 {{ __('common.privacy_consent_title') }}
                        </h4>
                        <p class="text-sm text-teal-700">
                            {{ __('common.privacy_consent_desc') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-4 bg-white rounded-lg border border-teal-200">
                    <input type="checkbox" name="privacy_consent" id="privacy_consent" value="1" required
                           class="w-5 h-5 theme-link-icon border-2 border-gray-300 rounded focus:ring-teal-500 focus:ring-2 mt-0.5">
                    <label for="privacy_consent" class="text-sm text-gray-800 cursor-pointer leading-relaxed">
                            Ich habe die <a href="/sayfa/datenschutz" target="_blank" class="underline font-semibold text-teal-700 hover:text-teal-900">Datenschutzerklärung</a> gelesen und stimme der Verarbeitung meiner personenbezogenen Daten zu. *
                    </label>
                </div>
            </div>

            <!-- Digital Signature Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-signature theme-link-icon mr-3"></i>
                    {{ __('common.digital_signature') }}
                </h3>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>{{ __('common.digital_signature_important') }}</strong> {{ __('common.digital_signature_desc') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-pen-fancy mr-2 theme-link-icon"></i>
                        {{ __('common.signature_field') }} *
                    </label>

                    <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                        <div class="relative">
                            <canvas id="signature-pad" class="signature-pad w-full h-40 border border-gray-300 rounded bg-white cursor-crosshair touch-none"
                                    width="800" height="160" style="background-color: white; max-width: 100%; height: auto; min-height: 160px;"></canvas>

                            <!-- Mobile overlay for better touch handling -->
                            <div id="signature-overlay" class="absolute inset-0 pointer-events-none md:pointer-events-none"></div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mt-3 space-y-2 sm:space-y-0">
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-mouse-pointer mr-1"></i>
                                <span class="hidden sm:inline">{{ __('common.sign_with_mouse') }}</span>
                                <span class="sm:hidden">{{ __('common.sign_with_touch') }}</span>
                            </div>
                            <button type="button" id="clear-signature" class="text-sm text-red-600 hover:text-red-800 font-medium px-3 py-1 rounded border border-red-200 hover:bg-red-50 transition-colors">
                                <i class="fas fa-eraser mr-1"></i>
                                {{ __('common.clear_signature') }}
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="signature" id="signature-data" required>

                    <!-- Signature validation message -->
                    <div id="signature-error" class="text-red-600 text-sm mt-1 hidden">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        {{ __('common.please_sign') }}
                    </div>

                    <!-- Signature confirmation -->
                    <div class="flex items-center mt-4">
                                                <input type="checkbox" id="signature_confirmation" name="signature_confirmation" value="1" required
                               class="w-4 h-4 theme-link-icon border-gray-300 rounded focus:ring-teal-500">
                        <label for="signature_confirmation" class="ml-2 text-sm text-gray-700">
                            {{ __('common.signature_confirmation_text') }}
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center">
                <button type="submit" id="submit-button" class="text-white px-12 py-4 rounded-lg font-semibold text-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105" style="background: var(--theme-gradient);" onmouseover="this.style.background='linear-gradient(var(--theme-gradient-direction), var(--theme-hover-color), var(--theme-gradient-end))';" onmouseout="this.style.background='var(--theme-gradient)';">
                    <i class="fas fa-paper-plane mr-3"></i>
                    {{ __('common.submit_application') }}
                </button>
            </div>
        </form>
    </main>

    @include('partials.footer')

    <script>
        // Translation strings for JavaScript
        const translations = {
            ageWarning: '{{ __('common.age_warning_desc') }}',
            privacyConsentRequired: '@if(app()->getLocale() === "de")Bitte stimmen Sie der Datenschutzerklärung zu.@elseLütfen gizlilik politikasını kabul edin.@endif',
            sepaAgreementRequired: '{{ __('common.sepa_agreement_text') }}',
            bankDetailsRequired: '{{ __('common.bank_details_required') }}',
            signatureRequired: '{{ __('common.please_sign') }}',
            signatureConfirmationRequired: '{{ __('common.signature_confirmation_required') }}',
            minimumDues: '{{ __('common.monthly_dues_min') }} {{ \App\Models\Settings::getMinimumMonthlyDues() }} €'
        };
        
        // Initialize custom amount input state on page load
        function initializeCustomAmountInput() {
            const customRadio = document.querySelector('input[name="monthly_dues"][value="custom"]');
            const customInput = document.querySelector('input[name="custom_amount"]');

            if (customRadio && customInput) {
                if (customRadio.checked) {
                    customInput.required = true;
                    customInput.disabled = false;
                } else {
                    customInput.required = false;
                    customInput.disabled = false; // Don't disable, just clear validation
                }
            }
        }

        // Handle custom amount input
        document.querySelectorAll('input[name="monthly_dues"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const customInput = document.querySelector('input[name="custom_amount"]');
                if (this.value === 'custom') {
                    customInput.required = true;
                    customInput.disabled = false;
                    customInput.focus();
                } else {
                    customInput.required = false;
                    customInput.disabled = false; // Don't disable to allow form submission
                    if (!customInput.value) {
                        customInput.value = ''; // Only clear if empty
                    }
                }
            });
        });

        // Set custom amount when custom radio is selected
        const customAmountInput = document.querySelector('input[name="custom_amount"]');
        if (customAmountInput) {
            customAmountInput.addEventListener('input', function() {
                if (this.value) {
                    // Validate minimum amount
                    const minAmount = {{ \App\Models\Settings::getMinimumMonthlyDues() }};
                    if (parseFloat(this.value) < minAmount) {
                        this.setCustomValidity('{{ __('common.monthly_dues_min') }} ' + minAmount + ' € {{ __('common.monthly_dues') }}');
                    } else {
                        this.setCustomValidity('');
                    }
                    const customRadio = document.querySelector('input[name="monthly_dues"][value="custom"]');
                    if (customRadio) {
                        customRadio.checked = true;
                        this.required = true;
                        this.disabled = false;
                    }
                }
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeCustomAmountInput();
        });



                                                                // Toggle payment frequency, SEPA and bank details sections
        function togglePaymentFrequency() {
            const lastschriftRadios = document.querySelectorAll('input[name="payment_method"][value^="lastschrift"]');
            const bankTransferRadio = document.querySelector('input[name="payment_method"][value="bank_transfer"]');
            const sepaAgreementSection = document.getElementById('sepaAgreementSection');
            const bankDetailsSection = document.getElementById('bankDetailsSection');
            const sepaCheckbox = document.getElementById('sepa_agreement');
            const bankInputs = document.querySelectorAll('#bankDetailsSection input');

            // Check if any lastschrift option is selected
            const isLastschriftSelected = Array.from(lastschriftRadios).some(radio => radio.checked);

            // Show SEPA agreement and bank details only when lastschrift is selected
            if (isLastschriftSelected) {
                sepaAgreementSection.classList.remove('hidden');
                bankDetailsSection.classList.remove('hidden');
                sepaCheckbox.required = true;
                // Make bank details required for lastschrift
                bankInputs.forEach(input => {
                    input.required = true;
                });
            } else {
                sepaAgreementSection.classList.add('hidden');
                bankDetailsSection.classList.add('hidden');
                sepaCheckbox.required = false;
                sepaCheckbox.checked = false;
                // Clear bank details when not needed
                bankInputs.forEach(input => {
                    input.value = '';
                    input.required = false;
                });
            }
        }

        // Initialize payment frequency toggle on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePaymentFrequency();
        });

        // Signature Pad Implementation
        class SignaturePad {
            constructor(canvas) {
                this.canvas = canvas;
                this.ctx = canvas.getContext('2d');
                this.isDrawing = false;
                this.isEmpty = true;

                this.setupCanvas();
                this.bindEvents();
            }

            setupCanvas() {
                // Responsive canvas setup
                const rect = this.canvas.getBoundingClientRect();

                // Set canvas size to match display size
                this.canvas.width = rect.width;
                this.canvas.height = rect.height;

                // Beyaz arka plan ekle
                this.ctx.fillStyle = '#FFFFFF';
                this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

                this.ctx.lineCap = 'round';
                this.ctx.lineJoin = 'round';
                this.ctx.strokeStyle = '#000000';
                this.ctx.lineWidth = Math.max(1, rect.width / 400); // Responsive line width
            }

            bindEvents() {
                // Mouse events
                this.canvas.addEventListener('mousedown', this.startDrawing.bind(this));
                this.canvas.addEventListener('mousemove', this.draw.bind(this));
                this.canvas.addEventListener('mouseup', this.stopDrawing.bind(this));
                this.canvas.addEventListener('mouseout', this.stopDrawing.bind(this));

                // Touch events for mobile - improved handling
                this.canvas.addEventListener('touchstart', this.handleTouch.bind(this), { passive: false });
                this.canvas.addEventListener('touchmove', this.handleTouch.bind(this), { passive: false });
                this.canvas.addEventListener('touchend', this.stopDrawing.bind(this), { passive: false });

                // Prevent scrolling when touching canvas on mobile
                this.canvas.addEventListener('touchstart', (e) => e.preventDefault(), { passive: false });
                this.canvas.addEventListener('touchmove', (e) => e.preventDefault(), { passive: false });
            }

            getCoordinates(event) {
                const rect = this.canvas.getBoundingClientRect();

                if (event.touches) {
                    return {
                        x: event.touches[0].clientX - rect.left,
                        y: event.touches[0].clientY - rect.top
                    };
                }

                return {
                    x: event.clientX - rect.left,
                    y: event.clientY - rect.top
                };
            }

            startDrawing(event) {
                event.preventDefault();
                this.isDrawing = true;
                const coords = this.getCoordinates(event);
                this.ctx.beginPath();
                this.ctx.moveTo(coords.x, coords.y);
                this.isEmpty = false;
            }

            draw(event) {
                if (!this.isDrawing) return;
                event.preventDefault();

                const coords = this.getCoordinates(event);
                this.ctx.lineTo(coords.x, coords.y);
                this.ctx.stroke();
            }

            stopDrawing(event) {
                if (!this.isDrawing) return;
                event.preventDefault();
                this.isDrawing = false;
                this.updateSignatureData();
            }

            handleTouch(event) {
                event.preventDefault();

                if (event.touches && event.touches.length > 0) {
                    const touch = event.touches[0];
                    const rect = this.canvas.getBoundingClientRect();
                    const scaleX = this.canvas.width / rect.width;
                    const scaleY = this.canvas.height / rect.height;

                    const x = (touch.clientX - rect.left) * scaleX;
                    const y = (touch.clientY - rect.top) * scaleY;

                    if (event.type === 'touchstart') {
                        this.isDrawing = true;
                        this.ctx.beginPath();
                        this.ctx.moveTo(x, y);
                        this.isEmpty = false;
                    } else if (event.type === 'touchmove' && this.isDrawing) {
                        this.ctx.lineTo(x, y);
                        this.ctx.stroke();
                    } else if (event.type === 'touchend') {
                        this.isDrawing = false;
                        this.updateSignatureData();
                    }
                }
            }

            clear() {
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                // Beyaz arka plan tekrar ekle
                this.ctx.fillStyle = '#FFFFFF';
                this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
                this.isEmpty = true;
                this.updateSignatureData();
            }

            updateSignatureData() {
                const signatureData = this.isEmpty ? '' : this.canvas.toDataURL('image/jpeg', 0.8);
                document.getElementById('signature-data').value = signatureData;

                // Hide error message if signature exists
                if (!this.isEmpty) {
                    document.getElementById('signature-error').classList.add('hidden');
                }
            }

            isSignatureEmpty() {
                return this.isEmpty;
            }
        }

        // Initialize signature pad
        let signaturePad;
        document.addEventListener('DOMContentLoaded', function() {
            togglePaymentFrequency();

            // Initialize signature pad
            const canvas = document.getElementById('signature-pad');
            if (canvas) {
                signaturePad = new SignaturePad(canvas);

                // Clear signature button
                document.getElementById('clear-signature').addEventListener('click', function() {
                    signaturePad.clear();
                });

                // Handle window resize for responsive canvas
                window.addEventListener('resize', function() {
                    if (signaturePad) {
                        signaturePad.setupCanvas();
                    }
                });
            }

            // Real-time age validation
            const birthDateInput = document.getElementById('birth_date');
            if (birthDateInput) {
                birthDateInput.addEventListener('change', function() {
                    checkAge();
                });
            }
        });

        // Function to check age and show/hide warning
        function checkAge() {
            const birthDateInput = document.getElementById('birth_date');
            const warningDiv = document.getElementById('age-warning');

            if (birthDateInput.value) {
                const birthDate = new Date(birthDateInput.value);
                const today = new Date();
                const age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                if (age < 16) {
                    // Show warning
                    if (!warningDiv) {
                        const newWarning = document.createElement('div');
                        newWarning.id = 'age-warning';
                        newWarning.className = 'mt-2 p-3 bg-red-50 border border-red-200 rounded-lg';
                        newWarning.innerHTML = `
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-circle text-red-600 mr-2 mt-0.5"></i>
                                <div class="text-sm text-red-700">
                                    <p class="font-semibold">{{ __('common.age_warning') }}</p>
                                    <p>{{ __('common.age_warning_desc') }}</p>
                                </div>
                            </div>
                        `;
                        birthDateInput.parentNode.appendChild(newWarning);
                    }
                    birthDateInput.classList.add('border-red-500');
                    birthDateInput.classList.remove('border-gray-300');
                } else {
                    // Hide warning and restore normal styling
                    if (warningDiv) {
                        warningDiv.remove();
                    }
                    birthDateInput.classList.remove('border-red-500');
                    birthDateInput.classList.add('border-gray-300');
                }
            } else {
                // Hide warning if no date selected
                if (warningDiv) {
                    warningDiv.remove();
                }
                birthDateInput.classList.remove('border-red-500');
                birthDateInput.classList.add('border-gray-300');
            }
        }

        // Form validation - prevent submission without SEPA agreement and signature when required
        document.querySelector('form').addEventListener('submit', function(e) {
            const birthDateInput = document.getElementById('birth_date');
            const lastschriftRadios = document.querySelectorAll('input[name="payment_method"][value^="lastschrift"]');
            const isLastschriftSelected = Array.from(lastschriftRadios).some(radio => radio.checked);
            const sepaAgreement = document.getElementById('sepa_agreement');
            const privacyConsent = document.getElementById('privacy_consent');
            const signatureConfirmation = document.getElementById('signature_confirmation');

            // Check age requirement (16 years old)
            if (birthDateInput.value) {
                const birthDate = new Date(birthDateInput.value);
                const today = new Date();
                const age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                if (age < 16) {
                    e.preventDefault();
                    alert(translations.ageWarning);
                    birthDateInput.focus();
                    return false;
                }
            }

            // Check privacy consent (DSGVO requirement)
            if (!privacyConsent.checked) {
                e.preventDefault();
                alert(translations.privacyConsentRequired);
                privacyConsent.focus();
                return false;
            }

            // Check SEPA agreement if lastschrift is selected
            if (isLastschriftSelected && !sepaAgreement.checked) {
                e.preventDefault();
                alert(translations.sepaAgreementRequired);
                sepaAgreement.focus();
                return false;
            }

            // Check bank details if lastschrift is selected
            if (isLastschriftSelected) {
                const accountHolder = document.getElementById('account_holder').value.trim();
                const bankName = document.getElementById('bank_name').value.trim();
                const iban = document.getElementById('iban').value.trim();
                const bic = document.getElementById('bic').value.trim();

                if (!accountHolder || !bankName || !iban || !bic) {
                    e.preventDefault();
                    alert(translations.bankDetailsRequired);
                    document.getElementById('account_holder').focus();
                    return false;
                }
            }

            // Check signature
            if (signaturePad && signaturePad.isSignatureEmpty()) {
                e.preventDefault();
                document.getElementById('signature-error').classList.remove('hidden');
                document.getElementById('signature-pad').scrollIntoView({ behavior: 'smooth' });
                alert(translations.signatureRequired);
                return false;
            }

            // Check signature confirmation
            if (!signatureConfirmation.checked) {
                e.preventDefault();
                alert(translations.signatureConfirmationRequired);
                signatureConfirmation.focus();
                return false;
            }
        });

        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
