<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('common.application_success_title') }} - {{ $settings['organization_name'] }}</title>

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
    @include('partials.theme-styles')
</head>
<body class="bg-gray-50 font-sans">
    @include('partials.header-menu-wrapper')

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Success Animation -->
        <div class="text-center mb-8">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                <i class="fas fa-check-circle text-green-600 text-4xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('common.application_success_title') }}</h1>
        </div>

        <!-- Welcome Message -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-8 mb-8">
            <div class="flex items-start">
                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mr-6 flex-shrink-0">
                    @if($settings['logo'])
                        <img src="{{ asset('storage/' . $settings['logo']) }}" alt="{{ $settings['organization_name'] }}" class="w-12 h-12 object-contain">
                    @else
                        <i class="fas fa-heart text-green-600 text-2xl"></i>
                    @endif
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-green-900 mb-4">
                        {{ __('common.welcome_thanks') }}
                    </h2>
                    <div class="text-green-800 space-y-4">
                        <p class="text-lg font-semibold">
                            {{ __('common.thanks_for_membership') }}
                        </p>
                        <div class="space-y-3">
                            <h3 class="text-lg font-bold flex items-center">
                                <i class="fas fa-clipboard-list mr-3 text-green-600"></i>
                                📝 {{ __('common.process') }}
                            </h3>
                            <ol class="list-decimal list-inside space-y-2 ml-8 text-base">
                                <li class="flex items-start">
                                    <span class="text-green-600 mr-2">✅</span>
                                    <span><strong>{{ __('common.step1_completed') }}</strong></span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-yellow-600 mr-2">⏳</span>
                                    <span><strong>{{ __('common.step2_pending') }}</strong></span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-blue-600 mr-2">🔜</span>
                                    <span><strong>{{ __('common.step3_future') }}</strong></span>
                                </li>

                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($member->application_status === 'approved')
        <!-- PDF Actions - Sadece Onaylanmış Üyeler İçin -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 mb-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-pdf text-green-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-check-circle mr-2 text-green-600"></i>
                    {{ __('common.approved_application_form') }}
                </h2>
                <p class="text-gray-600 text-lg">
                    {{ __('common.membership_approved_pdf') }}
                </p>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-green-900 mb-2">✅ {{ __('common.membership_approved') }}</h3>
                        <div class="text-green-800 space-y-2">
                            <p>• {{ __('common.pdf_official_document') }}</p>
                            <p>• {{ __('common.pdf_download_archive') }}</p>
                            <p>• {{ __('common.membership_active_completed') }}</p>
                            <p class="text-sm text-green-600 mt-3">
                                <i class="fas fa-info-circle mr-1"></i>
                                {{ __('common.document_officially_completed') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('member.application.pdf', $member->id) }}"
                   class="inline-flex items-center justify-center px-8 py-4 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-colors shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-file-pdf mr-3"></i>
                    {{ __('common.download_approved_pdf') }}
                </a>

                <a href="{{ route('member.application.pdf', $member->id) }}?preview=1"
                   target="_blank"
                   class="inline-flex items-center justify-center px-8 py-4 bg-gray-600 text-white font-semibold rounded-xl hover:bg-gray-700 transition-colors shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-eye mr-3"></i>
                    {{ __('common.pdf_preview') }}
                </a>
            </div>

            <div class="mt-6 text-center">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-certificate text-green-600 mr-2"></i>
                        <p class="text-sm text-green-800">
                            <strong>{{ __('common.official_document') }}</strong> {{ __('common.official_document_desc') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Onay Bekliyor Mesajı -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 mb-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-hourglass-half mr-2 text-yellow-600"></i>
                    {{ __('common.application_under_review') }}
                </h2>
                <p class="text-gray-600 text-lg">
                    {{ __('common.application_received_review') }}
                </p>
            </div>

            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-xl p-6 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-yellow-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-yellow-900 mb-2">{{ __('common.approval_process') }}</h3>
                        <div class="text-yellow-800 space-y-2">
                            <p>• {{ __('common.reviewed_by_board') }}</p>
                            <p>• {{ __('common.review_process_days') }}</p>
                            <p>• {{ __('common.email_notification_after_approval') }}</p>
                            <p>• {{ __('common.pdf_available_after_approval') }}</p>
                            <p class="text-sm text-yellow-600 mt-3">
                                <i class="fas fa-clock mr-1"></i>
                                {{ __('common.status') }} <strong>{{ ucfirst($member->application_status) }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-envelope text-blue-600 mr-2"></i>
                        <p class="text-sm text-blue-800">
                            <strong>{{ __('common.notification_info') }}</strong> {{ str_replace(':email', $member->email, __('common.email_will_be_sent')) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($member->signature)
        <!-- Digital Signature Preview -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 mb-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-signature text-green-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-check-circle mr-2 text-green-600"></i>
                    {{ __('common.digital_signature') }}
                </h2>
                <p class="text-gray-600 text-lg">
                    {{ __('common.digital_signature_desc') }}
                </p>
            </div>

            <div class="bg-gray-50 rounded-xl p-6 max-w-md mx-auto">
                <div class="text-center mb-4">
                    <h3 class="font-semibold text-gray-900 mb-2">{{ __('common.digital_signature_preview') }}</h3>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-white">
                        <img src="{{ $member->signature }}"
                             alt="{{ __('common.digital_signature') }}"
                             class="max-w-full h-auto max-h-20 mx-auto"
                             style="image-rendering: crisp-edges;">
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="fas fa-clock mr-1"></i>
                        {{ __('common.signature_date') }} {{ $member->signature_date ? $member->signature_date->format('d.m.Y H:i') : __('common.signature_not_specified') }}
                    </p>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                    <div class="flex items-center justify-center text-sm text-green-800">
                        <i class="fas fa-shield-alt mr-2 text-green-600"></i>
                        <span class="font-medium">{{ __('common.signature_stored_securely') }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Next Steps -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-envelope text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ __('common.email_notification') }}</h3>
                </div>
                <p class="text-gray-600 mb-4">
                    {{ __('common.updates_will_be_sent') }}
                </p>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ __('common.email') }}: <strong>{{ $member->email ?? __('common.email_address') }}</strong>
                    </p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ __('common.waiting_time') }}</h3>
                </div>
                <p class="text-gray-600 mb-4">
                    {{ __('common.board_will_review') }}
                </p>
                <div class="bg-purple-50 p-3 rounded-lg">
                    <p class="text-sm text-purple-800">
                        <i class="fas fa-hourglass-half mr-2"></i>
                        {{ __('common.average_time') }} <strong>{{ __('common.average_time_days') }}</strong>
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-gray-100 p-6 rounded-2xl">
            <div class="text-center">
                <h3 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-question-circle mr-2 text-teal-600"></i>
                    {{ __('common.have_questions') }}
                </h3>
                <p class="text-gray-600 mb-4">
                    {{ __('common.contact_for_questions') }}
                </p>
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-6">
                    <a href="tel:{{ $settings['organization_phone'] }}" class="flex items-center text-teal-600 hover:text-teal-700">
                        <i class="fas fa-phone mr-2"></i>
                        {{ $settings['organization_phone'] }}
                    </a>
                    <a href="mailto:{{ $settings['organization_email'] }}" class="flex items-center text-teal-600 hover:text-teal-700">
                        <i class="fas fa-envelope mr-2"></i>
                        {{ $settings['organization_email'] }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4 mt-8">
            <a href="/" class="bg-gradient-to-r from-teal-600 to-teal-700 text-white px-8 py-3 rounded-lg font-semibold hover:from-teal-700 hover:to-teal-800 transition-all duration-200 shadow-lg">
                <i class="fas fa-home mr-2"></i>
                {{ __('common.back_to_home') }}
            </a>
            <a href="{{ route('member.login') }}" class="bg-white border border-teal-600 text-teal-600 px-8 py-3 rounded-lg font-semibold hover:bg-teal-50 transition-all duration-200">
                <i class="fas fa-sign-in-alt mr-2"></i>
                {{ __('common.member_login_title') }}
            </a>
        </div>

        <!-- Login Info -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200 text-center">
            <div class="text-sm text-blue-700">
                <p class="font-semibold mb-1">
                    <i class="fas fa-info-circle mr-2"></i>
                    {{ __('common.member_panel_login_info') }}
                </p>
                <p>
                    {{ __('common.can_login_after_approval') }}
                </p>
                <p class="mt-2 font-semibold text-blue-800">
                    <i class="fas fa-envelope mr-1"></i>
                    {{ __('common.your_username') }} <strong>{{ $member->email }}</strong> ({{ __('common.email_address') }})
                </p>
                <p class="text-xs text-blue-600 mt-1">
                    {{ __('common.your_password') }}
                </p>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-16">
        <div class="max-w-4xl mx-auto px-4 py-8 text-center">
            <p class="text-sm">
                © {{ date('Y') }} {{ $settings['organization_name'] }}. {{ __('common.all_rights_reserved') }}. 
                <span class="text-gray-400">v{{ config('app.version', '1.0.0') }}</span>
            </p>
        </div>
    </footer>
</body>
</html>
