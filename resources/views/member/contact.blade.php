@extends('layouts.member-contact')

@section('title', 'İletişim ve Bağış')

@section('content-class', 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12')

@section('content')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Contact Information -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-8">
                <div class="flex items-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                        <i class="fas fa-address-book text-white text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        İletişim Bilgileri
                        <span class="text-sm text-gray-600 ml-2">(Kontaktinformationen)</span>
                    </h2>
                </div>

                <div class="space-y-6">
                    @if($settings['organization_address'])
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-red-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">
                                    Adres
                                    <span class="text-xs ml-1">(Adresse)</span>
                                </label>
                                <p class="text-gray-900 text-base leading-relaxed">{{ $settings['organization_address'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($settings['organization_phone'])
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-green-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">
                                    Telefon
                                    <span class="text-xs ml-1">(Telefon)</span>
                                </label>
                                <a href="tel:{{ $settings['organization_phone'] }}" class="text-gray-900 text-lg font-semibold hover:text-blue-600 transition-colors">
                                    {{ $settings['organization_phone'] }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($settings['organization_fax'])
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-fax text-purple-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">
                                    Faks
                                    <span class="text-xs ml-1">(Fax)</span>
                                </label>
                                <p class="text-gray-900 text-lg font-semibold">{{ $settings['organization_fax'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($settings['organization_email'])
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-blue-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">
                                    E-Mail
                                    <span class="text-xs ml-1">(E-Mail)</span>
                                </label>
                                <a href="mailto:{{ $settings['organization_email'] }}" class="text-gray-900 text-lg font-semibold hover:text-blue-600 transition-colors">
                                    {{ $settings['organization_email'] }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Donation Information -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-8">
                <div class="flex items-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                        <i class="fas fa-bank text-white text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        Banka Bilgileri
                        <span class="text-sm text-gray-600 ml-2">(Bankinformationen)</span>
                    </h2>
                </div>

                <div class="space-y-6">
                    @if($settings['bank_name'] && $settings['bank_iban'])
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-start mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-university text-green-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">
                                    Banka Hesabı
                                    <span class="text-xs ml-1">(Bankkonto)</span>
                                </label>
                                <div class="space-y-2">
                                    @if($settings['bank_name'])
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 font-medium">
                                            Banka:
                                            <span class="text-xs ml-1">(Bank)</span>
                                        </span>
                                        <span class="text-gray-900 font-semibold">{{ $settings['bank_name'] }}</span>
                                    </div>
                                    @endif
                                    @if($settings['account_holder'])
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 font-medium">
                                            Hesap Sahibi:
                                            <span class="text-xs ml-1">(Kontoinhaber)</span>
                                        </span>
                                        <span class="text-gray-900 font-semibold">{{ $settings['account_holder'] }}</span>
                                    </div>
                                    @endif
                                    @if($settings['bank_iban'])
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 font-medium">
                                            IBAN:
                                            <span class="text-xs ml-1">(IBAN)</span>
                                        </span>
                                        <span class="text-gray-900 font-mono font-semibold">{{ $settings['bank_iban'] }}</span>
                                    </div>
                                    @endif
                                    @if($settings['bank_bic'])
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 font-medium">
                                            BIC/SWIFT:
                                            <span class="text-xs ml-1">(BIC/SWIFT)</span>
                                        </span>
                                        <span class="text-gray-900 font-mono font-semibold">{{ $settings['bank_bic'] }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($settings['bank_iban'])
                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 font-medium">
                                    IBAN Kopyala:
                                    <span class="text-xs ml-1">(IBAN kopieren)</span>
                                </span>
                                <button onclick="copyToClipboard('{{ $settings['bank_iban'] }}', this)"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-copy mr-2"></i>
                                    Kopyala
                                    <span class="text-xs ml-1">(Kopieren)</span>
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($settings['paypal_link'])
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fab fa-paypal text-blue-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wider">
                                    PayPal Bağış
                                    <span class="text-xs ml-1">(PayPal Spende)</span>
                                </label>
                                <p class="text-gray-700 text-sm mb-4">
                                    Online bağış yapmak için PayPal formunu kullanabilirsiniz.
                                    <span class="text-xs ml-1">(Sie können das PayPal-Formular für Online-Spenden verwenden.)</span>
                                </p>

                                <!-- PayPal Donation Form -->
                                <a href="{{ $settings['paypal_link'] }}" target="_blank" class="block">
                                    <button type="button" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                        <i class="fab fa-paypal mr-2"></i>
                                        PayPal ile Bağış Yap
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!$settings['bank_name'] && !$settings['paypal_link'])
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-info-circle text-yellow-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-yellow-800 mb-2">
                                    Bağış Bilgileri Henüz Eklenmemiş
                                    <span class="text-sm ml-1">(Spendeninformationen noch nicht hinzugefügt)</span>
                                </h3>
                                <p class="text-yellow-700 text-sm">
                                    Bağış bilgileri yönetici tarafından henüz eklenmemiş. Lütfen daha sonra tekrar kontrol edin.
                                    <span class="text-xs ml-1">(Die Spendeninformationen wurden vom Administrator noch nicht hinzugefügt. Bitte überprüfen Sie es später erneut.)</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="mt-12 bg-white border border-gray-200 rounded-2xl shadow-lg p-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-info-circle text-white text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">
                    Önemli Bilgiler
                    <span class="text-sm text-gray-600 ml-2">(Wichtige Informationen)</span>
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-start">
                        <i class="fas fa-lightbulb text-blue-600 text-xl mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-blue-900 mb-2">
                                Bağış Yaparken
                                <span class="text-xs ml-1">(Beim Spenden)</span>
                            </h4>
                            <ul class="text-blue-800 text-sm space-y-1">
                                <li>• IBAN numarasını doğru kopyaladığınızdan emin olun</li>
                                <li>• Ödeme açıklamasında adınızı belirtin</li>
                                <li>• Bağış tutarını belirtmek isterseniz açıklamaya yazın</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <div class="flex items-start">
                        <i class="fas fa-shield-alt text-green-600 text-xl mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-green-900 mb-2">
                                Güvenlik
                                <span class="text-xs ml-1">(Sicherheit)</span>
                            </h4>
                            <ul class="text-green-800 text-sm space-y-1">
                                <li>• Sadece resmi banka hesabımızı kullanın</li>
                                <li>• Şüpheli linklere tıklamayın</li>
                                <li>• Sorularınız için bizimle iletişime geçin</li>
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
