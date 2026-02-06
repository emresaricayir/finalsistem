<!-- Cookie Consent Banner (DSGVO) -->
<div id="cookieConsentBanner" class="fixed bottom-0 left-0 right-0 z-50 hidden bg-white shadow-2xl border-t-4" style="border-color: var(--theme-primary-color, #0d9488);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 mt-1">
                        <i class="fas fa-cookie-bite text-2xl" style="color: var(--theme-primary-color, #0d9488);"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            {{ __('common.cookie_consent_title') }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-2">
                            {{ __('common.cookie_consent_desc') }}
                        </p>
                        <a href="/sayfa/cerez-politikasi" class="text-sm font-medium underline" style="color: var(--theme-link-color, #0d9488);">
                            {{ __('common.learn_more') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
                <button onclick="acceptCookies()" class="px-4 py-2 text-sm font-semibold text-white rounded-lg transition-all duration-200 hover:shadow-lg" style="background: var(--theme-primary-color, #0d9488);" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                    {{ __('common.accept_cookies') }}
                </button>
                <button onclick="declineCookies()" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg transition-all duration-200 hover:bg-gray-200">
                    {{ __('common.decline_cookies') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Cookie consent management
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = `expires=${date.toUTCString()}`;
        document.cookie = `${name}=${value};${expires};path=/;SameSite=Lax`;
    }

    function acceptCookies() {
        setCookie('cookie_consent', 'accepted', 365);
        document.getElementById('cookieConsentBanner').classList.add('hidden');
    }

    function declineCookies() {
        setCookie('cookie_consent', 'declined', 365);
        document.getElementById('cookieConsentBanner').classList.add('hidden');
    }

    // Check if consent already given
    document.addEventListener('DOMContentLoaded', function() {
        const consent = getCookie('cookie_consent');
        if (!consent) {
            // Show banner after a short delay for better UX
            setTimeout(function() {
                document.getElementById('cookieConsentBanner').classList.remove('hidden');
            }, 1000);
        }
    });
</script>
