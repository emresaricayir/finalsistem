@extends('layouts.member-contact')

@section('title', __('common.contact_and_donation'))

@section('content-class', 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-12')

@section('content')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
            <!-- Contact Information -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center mb-4 sm:mb-6 lg:mb-8 space-y-3 sm:space-y-0">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl sm:rounded-2xl flex items-center justify-center sm:mr-4 shadow-lg flex-shrink-0">
                        <i class="fas fa-address-book text-white text-lg sm:text-xl lg:text-2xl"></i>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                        {{ __('common.contact_information') }}
                    </h2>
                </div>

                <div class="space-y-4 sm:space-y-6">
                    @if($settings['organization_address'])
                    <div class="bg-gray-50 rounded-xl p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-start space-y-3 sm:space-y-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-xl flex items-center justify-center sm:mr-4 flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-red-600 text-lg sm:text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <label class="block text-xs sm:text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">
                                    {{ __('common.organization_address') }}
                                </label>
                                <p class="text-gray-900 text-sm sm:text-base leading-relaxed break-words">{{ $settings['organization_address'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($settings['organization_phone'])
                    <div class="bg-gray-50 rounded-xl p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-start space-y-3 sm:space-y-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-xl flex items-center justify-center sm:mr-4 flex-shrink-0">
                                <i class="fas fa-phone text-green-600 text-lg sm:text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <label class="block text-xs sm:text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">
                                    {{ __('common.organization_phone') }}
                                </label>
                                <a href="tel:{{ $settings['organization_phone'] }}" class="text-gray-900 text-base sm:text-lg font-semibold hover:text-blue-600 transition-colors break-all">
                                    {{ $settings['organization_phone'] }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($settings['organization_fax'])
                    <div class="bg-gray-50 rounded-xl p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-start space-y-3 sm:space-y-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-xl flex items-center justify-center sm:mr-4 flex-shrink-0">
                                <i class="fas fa-fax text-purple-600 text-lg sm:text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <label class="block text-xs sm:text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">
                                    {{ __('common.organization_fax') }}
                                </label>
                                <p class="text-gray-900 text-base sm:text-lg font-semibold break-all">{{ $settings['organization_fax'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($settings['organization_email'])
                    <div class="bg-gray-50 rounded-xl p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-start space-y-3 sm:space-y-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center sm:mr-4 flex-shrink-0">
                                <i class="fas fa-envelope text-blue-600 text-lg sm:text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <label class="block text-xs sm:text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">
                                    {{ __('common.organization_email') }}
                                </label>
                                <a href="mailto:{{ $settings['organization_email'] }}" class="text-gray-900 text-base sm:text-lg font-semibold hover:text-blue-600 transition-colors break-all">
                                    {{ $settings['organization_email'] }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Donation Information -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center mb-4 sm:mb-6 lg:mb-8 space-y-3 sm:space-y-0">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-xl sm:rounded-2xl flex items-center justify-center sm:mr-4 shadow-lg flex-shrink-0">
                        <i class="fas fa-bank text-white text-lg sm:text-xl lg:text-2xl"></i>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                        {{ __('common.bank_information') }}
                    </h2>
                </div>

                <div class="space-y-4 sm:space-y-6">
                    @if($settings['bank_name'] && $settings['bank_iban'])
                    <div class="bg-gray-50 rounded-xl p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-start mb-4 space-y-3 sm:space-y-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-xl flex items-center justify-center sm:mr-4 flex-shrink-0">
                                <i class="fas fa-university text-green-600 text-lg sm:text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0 w-full">
                                <label class="block text-xs sm:text-sm font-semibold text-gray-600 mb-3 uppercase tracking-wider">
                                    {{ __('common.bank_account') }}
                                </label>
                                <div class="space-y-2">
                                    @if($settings['bank_name'])
                                    <div class="flex flex-col sm:flex-row sm:justify-between space-y-1 sm:space-y-0">
                                        <span class="text-gray-600 font-medium text-xs sm:text-sm">
                                            {{ __('common.bank_name') }}:
                                        </span>
                                        <span class="text-gray-900 font-semibold text-sm sm:text-base break-words">{{ $settings['bank_name'] }}</span>
                                    </div>
                                    @endif
                                    @if($settings['account_holder'])
                                    <div class="flex flex-col sm:flex-row sm:justify-between space-y-1 sm:space-y-0">
                                        <span class="text-gray-600 font-medium text-xs sm:text-sm">
                                            {{ __('common.account_holder') }}:
                                        </span>
                                        <span class="text-gray-900 font-semibold text-sm sm:text-base break-words">{{ $settings['account_holder'] }}</span>
                                    </div>
                                    @endif
                                    @if($settings['bank_iban'])
                                    <div class="flex flex-col sm:flex-row sm:justify-between space-y-1 sm:space-y-0">
                                        <span class="text-gray-600 font-medium text-xs sm:text-sm">
                                            {{ __('common.iban') }}:
                                        </span>
                                        <span class="text-gray-900 font-mono font-semibold text-xs sm:text-sm break-all">{{ $settings['bank_iban'] }}</span>
                                    </div>
                                    @endif
                                    @if($settings['bank_bic'])
                                    <div class="flex flex-col sm:flex-row sm:justify-between space-y-1 sm:space-y-0">
                                        <span class="text-gray-600 font-medium text-xs sm:text-sm">
                                            {{ __('common.bic_swift') }}:
                                        </span>
                                        <span class="text-gray-900 font-mono font-semibold text-xs sm:text-sm break-all">{{ $settings['bank_bic'] }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($settings['bank_iban'])
                        <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4 mt-4">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                                <span class="text-xs sm:text-sm text-gray-600 font-medium">
                                    {{ __('common.copy_iban') }}:
                                </span>
                                <button onclick="copyToClipboard('{{ $settings['bank_iban'] }}', this)"
                                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors">
                                    <i class="fas fa-copy mr-2"></i>
                                    {{ __('common.copy') }}
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($settings['paypal_link'])
                    <div class="bg-gray-50 rounded-xl p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-start space-y-3 sm:space-y-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center sm:mr-4 flex-shrink-0">
                                <i class="fab fa-paypal text-blue-600 text-lg sm:text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0 w-full">
                                <label class="block text-xs sm:text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">
                                    {{ __('common.paypal_donation') }}
                                </label>
                                <p class="text-gray-700 text-xs sm:text-sm mb-4">
                                    {{ __('common.paypal_donation_desc') }}
                                </p>

                                <!-- PayPal Donation Form -->
                                <a href="{{ $settings['paypal_link'] }}" target="_blank" class="block">
                                    <button type="button" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 sm:py-3 px-4 rounded-lg transition-colors flex items-center justify-center text-sm sm:text-base">
                                        <i class="fab fa-paypal mr-2"></i>
                                        PayPal ile Bağış Yap
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!$settings['bank_name'] && !$settings['paypal_link'])
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-start space-y-3 sm:space-y-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-xl flex items-center justify-center sm:mr-4 flex-shrink-0">
                                <i class="fas fa-info-circle text-yellow-600 text-lg sm:text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base sm:text-lg font-semibold text-yellow-800 mb-2">
                                    {{ __('common.donation_info_not_added') }}
                                </h3>
                                <p class="text-yellow-700 text-xs sm:text-sm">
                                    {{ __('common.donation_info_not_added_desc') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="mt-6 sm:mt-8 lg:mt-12 bg-white border border-gray-200 rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center mb-4 sm:mb-6 space-y-3 sm:space-y-0">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center sm:mr-4 flex-shrink-0">
                    <i class="fas fa-info-circle text-white text-lg sm:text-xl"></i>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900">
                    {{ __('common.important_information') }}
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 sm:p-6">
                    <div class="flex items-start">
                        <i class="fas fa-lightbulb text-blue-600 text-lg sm:text-xl mr-3 mt-1 flex-shrink-0"></i>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-blue-900 mb-2 text-sm sm:text-base">
                                {{ __('common.when_donating') }}
                            </h4>
                            <ul class="text-blue-800 text-xs sm:text-sm space-y-1">
                                <li>• {{ __('common.donation_tips_1') }}</li>
                                <li>• {{ __('common.donation_tips_2') }}</li>
                                <li>• {{ __('common.donation_tips_3') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-xl p-4 sm:p-6">
                    <div class="flex items-start">
                        <i class="fas fa-shield-alt text-green-600 text-lg sm:text-xl mr-3 mt-1 flex-shrink-0"></i>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-green-900 mb-2 text-sm sm:text-base">
                                {{ __('common.security') }}
                            </h4>
                            <ul class="text-green-800 text-xs sm:text-sm space-y-1">
                                <li>• {{ __('common.security_tip_1') }}</li>
                                <li>• {{ __('common.security_tip_2') }}</li>
                                <li>• {{ __('common.security_tip_3') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
<script>
// PayPal form functionality
document.addEventListener('DOMContentLoaded', function() {
    const amountSelect = document.querySelector('select[name="amount"]');
    const customAmountDiv = document.getElementById('custom-amount');

    if (amountSelect && customAmountDiv) {
        amountSelect.addEventListener('change', function() {
            if (this.value === '') {
                customAmountDiv.classList.remove('hidden');
            } else {
                customAmountDiv.classList.add('hidden');
            }
        });
    }
});

function copyToClipboard(text, button) {
    // Fallback method for older browsers
    function fallbackCopyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            console.log('Fallback: Copying text command was ' + msg);
            return successful;
        } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
            return false;
        }

        document.body.removeChild(textArea);
    }

    // Modern clipboard API
    if (!navigator.clipboard) {
        if (fallbackCopyTextToClipboard(text)) {
            showCopySuccess();
        } else {
            showCopyError();
        }
        return;
    }

    navigator.clipboard.writeText(text).then(function() {
        showCopySuccess();
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        // Try fallback method
        if (fallbackCopyTextToClipboard(text)) {
            showCopySuccess();
        } else {
            showCopyError();
        }
    });

                function showCopySuccess() {
        // Button animation
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-2"></i>Kopyalandı! <span class="text-xs ml-1">(Kopiert!)</span>';
        button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        button.classList.add('bg-green-600');

        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 2000);
    }

    function showCopyError() {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-times mr-2"></i>Hata! <span class="text-xs ml-1">(Fehler!)</span>';
        button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        button.classList.add('bg-red-600');

        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('bg-red-600');
            button.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 2000);
    }
}
</script>
@endsection
