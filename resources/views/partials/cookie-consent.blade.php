<!-- ============================= -->
<!-- Modern Cookie Consent Banner -->
<!-- ============================= -->

<div id="cookieConsentBanner"
     class="fixed inset-x-0 bottom-6 z-50 hidden flex justify-center px-4 transition-all duration-700 ease-out translate-y-10 opacity-0">

    <div class="w-full max-w-4xl backdrop-blur-xl bg-white/80 border border-white/40 
                shadow-[0_20px_60px_rgba(0,0,0,0.15)] rounded-2xl">

        <div class="p-6 sm:p-8 flex flex-col lg:flex-row gap-6 items-start lg:items-center justify-between">

            <!-- Left Content -->
            <div class="flex items-start gap-4">
                
                <!-- Icon -->
                <div class="w-12 h-12 flex items-center justify-center rounded-xl 
                            bg-gradient-to-br from-teal-500 to-emerald-500 
                            text-white shadow-lg flex-shrink-0">
                    <i class="fas fa-cookie-bite text-xl"></i>
                </div>

                <!-- Text -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                        {{ __('common.cookie_consent_title') }}
                    </h3>

                    <p class="text-sm text-gray-600 leading-relaxed max-w-xl">
                        {{ __('common.cookie_consent_desc') }}
                    </p>

                    <a href="/sayfa/cerez-politikasi"
                       class="inline-block mt-2 text-sm font-medium text-teal-600 hover:text-teal-700 transition-colors">
                        {{ __('common.learn_more') }} →
                    </a>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

                <button onclick="acceptCookies()"
                        class="px-6 py-2.5 text-sm font-semibold text-white 
                               bg-gradient-to-r from-teal-500 to-emerald-500
                               rounded-xl shadow-md 
                               hover:shadow-xl hover:scale-105 
                               transition-all duration-300">
                    {{ __('common.accept_cookies') }}
                </button>

                <button onclick="declineCookies()"
                        class="px-6 py-2.5 text-sm font-semibold text-gray-700 
                               bg-white border border-gray-200
                               rounded-xl 
                               hover:bg-gray-50 hover:border-gray-300
                               transition-all duration-300">
                    {{ __('common.decline_cookies') }}
                </button>

            </div>
        </div>
    </div>
</div>

<script>
(function () {

    if (window.cookieConsentInitialized) return;
    window.cookieConsentInitialized = true;

    const banner = document.getElementById('cookieConsentBanner');
    if (!banner) return;

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = `${name}=${value};${expires};path=/;SameSite=Lax`;
    }

    function showBanner() {
        banner.classList.remove('hidden');

        setTimeout(() => {
            banner.classList.remove('translate-y-10', 'opacity-0');
            banner.classList.add('translate-y-0', 'opacity-100');
        }, 50);
    }

    function hideBanner() {
        banner.classList.remove('translate-y-0', 'opacity-100');
        banner.classList.add('translate-y-10', 'opacity-0');

        setTimeout(() => {
            banner.classList.add('hidden');
        }, 500);
    }

    window.acceptCookies = function () {
        setCookie('cookie_consent', 'accepted', 365);
        hideBanner();
    };

    window.declineCookies = function () {
        setCookie('cookie_consent', 'declined', 365);
        hideBanner();
    };

    function initializeCookieBanner() {
        const consent = getCookie('cookie_consent');

        if (!consent || consent === 'declined') {
            setTimeout(showBanner, 800);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeCookieBanner, { once: true });
    } else {
        initializeCookieBanner();
    }

})();
</script>
