@php
    $menuService = app(\App\Services\MenuService::class);
    $mainMenu = $menuService->getMainMenu();
@endphp

<!-- Menu Theme Styles - Theme variables are now in theme-styles partial -->
<style>
    /* Menu Theme Styles */
    .menu-dropdown-item:hover {
        background-color: rgba(13, 148, 136, 0.1) !important;
        color: var(--theme-link-color, #0d9488) !important;
    }
    .menu-link:hover {
        color: var(--theme-secondary-color, #0a7b73) !important;
    }
</style>

<div class="relative z-50" style="background-color: var(--theme-primary-color); border-top: 1px solid var(--theme-hover-color);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Desktop -->
        <nav class="hidden md:flex items-center justify-between h-16 relative z-50">
            <div class="flex items-center space-x-8">
                @foreach($mainMenu as $menuItem)
                    <div class="relative group">
                        <a href="{{ $menuService->buildMenuUrl($menuItem) }}"
                           class="flex items-center text-white transition-colors font-semibold text-base uppercase tracking-wide py-4" 
                           style="font-family: 'Inter', system-ui, sans-serif;"
                           onmouseover="this.style.color='{{ $themeSecondaryColor ?? '#0a7b73' }}'"
                           onmouseout="this.style.color='white'">
                            {{ $menuItem->title }}
                            @if($menuItem->has_dropdown && $menuItem->children->count() > 0)
                                <i class="fas fa-chevron-down ml-2 text-sm"></i>
                            @endif
                        </a>

                        @if($menuItem->has_dropdown && $menuItem->children->count() > 0)
                            <div class="absolute top-full left-0 bg-white shadow-xl rounded-lg py-2 min-w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[10002] border border-gray-100">
                                @foreach($menuItem->children as $subMenuItem)
                                    <a href="{{ $menuService->buildMenuUrl($subMenuItem) }}"
                                       class="text-gray-800 transition-colors text-sm font-medium px-4 py-3 block first:rounded-t-lg last:rounded-b-lg" 
                                       style="font-family: 'Inter', system-ui, sans-serif;"
                                       onmouseover="this.style.backgroundColor='rgba(13, 148, 136, 0.1)'; this.style.color='{{ $themeLinkColor ?? '#0d9488' }}';"
                                       onmouseout="this.style.backgroundColor='transparent'; this.style.color='#1f2937';">
                                        {{ $subMenuItem->title }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
                <!-- Donate -->
                <div class="ml-4 flex items-center">
                    <!-- Donate button with PayPal form -->
                    <div class="relative group">
                        <button onclick="toggleDonateForm()" class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-semibold text-sm uppercase tracking-wide px-4 py-2 rounded-lg shadow-sm transition-colors" style="font-family: 'Inter', system-ui, sans-serif;">
                            <i class="fas fa-heart mr-2"></i>
                            {{ __('common.donate') }}
                        </button>

                        <!-- PayPal Donation Form -->
                        <div id="donate-form" class="absolute top-full right-0 bg-white shadow-xl rounded-lg p-4 min-w-80 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[10002] border border-gray-100 hidden">
                            <div class="text-center mb-4">
                                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('common.make_donation') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('common.donate_with_paypal_desc') }}</p>
                            </div>

                            @php
                                $paypalLink = \App\Models\Settings::get('paypal_link', 'https://www.paypal.com/donate/?hosted_button_id=3YKN7GEZWBFKG');
                            @endphp
                            <a href="{{ $paypalLink }}" target="_blank" class="block">
                                <button type="button" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                    <i class="fab fa-paypal mr-2"></i>
                                    {{ __('common.donate_with_paypal') }}
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile -->
        <div class="md:hidden py-3">
            <div class="flex items-center justify-between">
                <!-- Mobile Menu Button -->
                <div class="flex items-center space-x-3">
                    <button id="mobile-menu-toggle" type="button" class="group relative w-12 h-12 rounded-xl bg-gradient-to-br from-teal-700/80 to-teal-800/80 hover:from-teal-600/90 hover:to-teal-700/90 flex items-center justify-center transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:ring-offset-2 focus:ring-offset-teal-800 shadow-lg hover:shadow-xl transform hover:scale-105 border border-teal-600/50 hover:border-teal-500/70">
                        <div class="w-5 h-5 flex flex-col justify-center items-center">
                            <span class="bg-white block transition-all duration-300 ease-out h-0.5 w-5 rounded-sm group-data-[menu-open]:rotate-45 group-data-[menu-open]:translate-y-1"></span>
                            <span class="bg-white block transition-all duration-300 ease-out h-0.5 w-5 rounded-sm my-0.5 group-data-[menu-open]:opacity-0"></span>
                            <span class="bg-white block transition-all duration-300 ease-out h-0.5 w-5 rounded-sm group-data-[menu-open]:-rotate-45 group-data-[menu-open]:-translate-y-1"></span>
                        </div>
                    </button>
                    <span class="text-white font-semibold text-sm uppercase tracking-wide" style="font-family: 'Inter', system-ui, sans-serif;">MENÜ</span>
                </div>

                <!-- Mobile Language Switcher -->
                <div class="flex items-center">
                    <div class="flex items-center space-x-0 bg-white/15 rounded-lg border border-white/30 overflow-hidden">
                        <a href="{{ route('language.switch', 'tr') }}" 
                           class="flex items-center space-x-1.5 px-2.5 py-1.5 transition-all duration-200 {{ app()->getLocale() === 'tr' ? 'text-white' : 'text-white hover:bg-white/20' }}"
                           style="{{ app()->getLocale() === 'tr' ? 'background-color: var(--theme-hover-color);' : '' }}"
                           title="Türkçe">
                            <img src="{{ asset('storage/templates/tr.png') }}" alt="Türkçe" class="w-4 h-3 object-cover rounded-sm">
                            <span class="text-xs font-semibold">TR</span>
                        </a>
                        <span class="w-px h-4 bg-white/30"></span>
                        <a href="{{ route('language.switch', 'de') }}" 
                           class="flex items-center space-x-1.5 px-2.5 py-1.5 transition-all duration-200 {{ app()->getLocale() === 'de' ? 'text-white' : 'text-white hover:bg-white/20' }}"
                           style="{{ app()->getLocale() === 'de' ? 'background-color: var(--theme-hover-color);' : '' }}"
                           title="Deutsch">
                            <img src="{{ asset('storage/templates/de.png') }}" alt="Deutsch" class="w-4 h-3 object-cover rounded-sm">
                            <span class="text-xs font-semibold">DE</span>
                        </a>
                    </div>
                </div>
            </div>
            </div>

            <!-- Mobile Menu Overlay -->
            <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 opacity-0 invisible transition-all duration-300 md:hidden">
                <div id="mobile-menu-panel" class="absolute right-0 top-0 h-full w-80 max-w-[85vw] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out">
                    <!-- Header -->
                    <div class="flex items-center justify-between p-4" style="background-color: var(--theme-primary-color);">
                        <h2 class="text-lg font-bold text-white">Menü</h2>
                        <div class="flex items-center space-x-2">
                            <!-- Mobile Menu Language Switcher -->
                            <div class="flex items-center space-x-0 rounded-lg border overflow-hidden" style="background-color: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.2);">
                                <a href="{{ route('language.switch', 'tr') }}" 
                                   class="flex items-center space-x-1.5 px-2.5 py-1.5 transition-all duration-200 {{ app()->getLocale() === 'tr' ? 'text-white' : 'text-white/80 hover:text-white' }}"
                                   style="{{ app()->getLocale() === 'tr' ? 'background-color: var(--theme-hover-color);' : '' }}"
                                   title="Türkçe">
                                    <img src="{{ asset('storage/templates/tr.png') }}" alt="Türkçe" class="w-4 h-3 object-cover rounded-sm">
                                    <span class="text-xs font-semibold">TR</span>
                                </a>
                                <span class="w-px h-4 bg-white/30"></span>
                                <a href="{{ route('language.switch', 'de') }}" 
                                   class="flex items-center space-x-1.5 px-2.5 py-1.5 transition-all duration-200 {{ app()->getLocale() === 'de' ? 'text-white' : 'text-white/80 hover:text-white' }}"
                                   style="{{ app()->getLocale() === 'de' ? 'background-color: var(--theme-hover-color);' : '' }}"
                                   title="Deutsch">
                                    <img src="{{ asset('storage/templates/de.png') }}" alt="Deutsch" class="w-4 h-3 object-cover rounded-sm">
                                    <span class="text-xs font-semibold">DE</span>
                                </a>
                            </div>
                            <button id="mobile-menu-close" class="w-8 h-8 rounded-full flex items-center justify-center transition-colors" style="background-color: var(--theme-hover-color);">
                                <i class="fas fa-times text-white"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Menu Items -->
                    <div class="py-2 max-h-[calc(100vh-180px)] overflow-y-auto">
                        @foreach($mainMenu as $index => $menuItem)
                            @php $hasChildren = $menuItem->has_dropdown && $menuItem->children->count() > 0; @endphp
                            <div class="menu-item" style="animation-delay: {{ $index * 50 }}ms">
                                <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors group">
                                    <a href="{{ $menuService->buildMenuUrl($menuItem) }}"
                                       class="flex-1 text-gray-900 font-semibold text-base transition-colors" 
                                       style="font-family: 'Inter', system-ui, sans-serif;"
                                       onmouseover="this.style.color='{{ $themeLinkColor ?? '#0d9488' }}'"
                                       onmouseout="this.style.color='#111827'">
                                        {{ $menuItem->title }}
                                    </a>
                                    @if($hasChildren)
                                        <button type="button"
                                                class="submenu-toggle w-8 h-8 rounded-full hover:bg-gray-200 flex items-center justify-center transition-all duration-200"
                                                aria-label="Alt menü"
                                                onclick="toggleSubmenu(this)">
                                            <i class="fas fa-chevron-down text-gray-400 text-sm transition-transform duration-200"></i>
                                        </button>
                                    @endif
                                </div>
                                @if($hasChildren)
                                    <div class="submenu hidden bg-gray-50" data-submenu>
                                        @foreach($menuItem->children as $subIndex => $subMenuItem)
                                            <a href="{{ $menuService->buildMenuUrl($subMenuItem) }}"
                                               class="block px-8 py-2 text-gray-700 hover:bg-white transition-all duration-200 border-l-2 border-transparent font-semibold"
                                               style="animation-delay: {{ ($index * 50) + ($subIndex * 25) }}ms; font-family: 'Inter', system-ui, sans-serif;"
                                               onmouseover="this.style.color='{{ $themeLinkColor ?? '#0d9488' }}'; this.style.borderColor='{{ $themeLinkColor ?? '#0d9488' }}';"
                                               onmouseout="this.style.color='#374151'; this.style.borderColor='transparent';">
                                                {{ $subMenuItem->title }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        <!-- Fixed Donate link at the end (mobile) -->
                        <div class="px-4 pt-2">
                            <button onclick="toggleMobileDonateForm()" class="w-full inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-semibold text-sm uppercase tracking-wide px-4 py-3 rounded-lg shadow-sm transition-colors" style="font-family: 'Inter', system-ui, sans-serif;">
                                <i class="fas fa-heart mr-2"></i>
                                {{ __('common.donate') }}
                            </button>

                            <!-- Mobile PayPal Donation Form -->
                            <div id="mobile-donate-form" class="mt-4 bg-white rounded-lg p-4 shadow-lg border border-gray-200 hidden">
                                <div class="text-center mb-4">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('common.make_donation') }}</h3>
                                    <p class="text-sm text-gray-600">{{ __('common.donate_with_paypal_desc') }}</p>
                                </div>

                                <a href="{{ $paypalLink }}" target="_blank" class="block">
                                    <button type="button" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                        <i class="fab fa-paypal mr-2"></i>
                                        {{ __('common.donate_with_paypal') }}
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Section -->
                    @php
                        $facebook = \App\Models\Settings::get('facebook_url');
                        $instagram = \App\Models\Settings::get('instagram_url');
                        $twitter = \App\Models\Settings::get('twitter_url');
                        $youtube = \App\Models\Settings::get('youtube_url');
                        $whatsapp = \App\Models\Settings::get('whatsapp_number');
                    @endphp

                    @if($facebook || $instagram || $twitter || $youtube || $whatsapp)
                        <div class="border-t border-gray-200 px-4 py-3 mt-4">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">
                                <i class="fas fa-share-alt mr-2"></i>
                                Bizi Takip Edin
                            </h3>
                            <div class="flex items-center space-x-3">
                                @if($facebook)
                                    <a href="{{ $facebook }}" target="_blank" rel="noopener noreferrer"
                                       class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110">
                                        <i class="fab fa-facebook-f text-white text-sm"></i>
                                    </a>
                                @endif
                                @if($instagram)
                                    <a href="{{ $instagram }}" target="_blank" rel="noopener noreferrer"
                                       class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110">
                                        <i class="fab fa-instagram text-white text-sm"></i>
                                    </a>
                                @endif
                                @if($twitter)
                                    <a href="{{ $twitter }}" target="_blank" rel="noopener noreferrer"
                                       class="w-10 h-10 bg-sky-500 hover:bg-sky-600 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110">
                                        <i class="fab fa-twitter text-white text-sm"></i>
                                    </a>
                                @endif
                                @if($youtube)
                                    <a href="{{ $youtube }}" target="_blank" rel="noopener noreferrer"
                                       class="w-10 h-10 bg-red-600 hover:bg-red-700 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110">
                                        <i class="fab fa-youtube text-white text-sm"></i>
                                    </a>
                                @endif
                                @if($whatsapp)
                                    <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener noreferrer"
                                       class="w-10 h-10 bg-green-600 hover:bg-green-700 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110">
                                        <i class="fab fa-whatsapp text-white text-sm"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Contact Information Section -->
                    @php
                        $phone = \App\Models\Settings::get('organization_phone');
                        $email = \App\Models\Settings::get('organization_email');
                        $address = \App\Models\Settings::get('organization_address');
                    @endphp

                    @if($phone || $email || $address)
                        <div class="border-t border-gray-200 px-4 py-3 mt-2">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">
                                <i class="fas fa-address-book mr-2"></i>
                                İletişim
                            </h3>
                            <div class="space-y-2">
                                @if($phone)
                                    <a href="tel:{{ $phone }}" class="flex items-center text-gray-700 hover:text-teal-600 transition-colors group">
                                        <div class="w-7 h-7 bg-teal-100 group-hover:bg-teal-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                            <i class="fas fa-phone text-teal-600 text-xs"></i>
                                        </div>
                                        <span class="text-sm font-medium">{{ $phone }}</span>
                                    </a>
                                @endif
                                @if($email)
                                    <a href="mailto:{{ $email }}" class="flex items-center text-gray-700 hover:text-teal-600 transition-colors group">
                                        <div class="w-7 h-7 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                            <i class="fas fa-envelope text-green-600 text-xs"></i>
                                        </div>
                                        <span class="text-sm font-medium break-all">{{ $email }}</span>
                                    </a>
                                @endif
                                @if($address)
                                    <div class="flex items-start text-gray-700 group">
                                        <div class="w-7 h-7 bg-red-100 rounded-lg flex items-center justify-center mr-3 mt-0.5">
                                            <i class="fas fa-map-marker-alt text-red-600 text-xs"></i>
                                        </div>
                                        <span class="text-sm font-medium leading-relaxed">{{ $address }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function(){
        const toggle = document.getElementById('mobile-menu-toggle');
        const overlay = document.getElementById('mobile-menu-overlay');
        const panel = document.getElementById('mobile-menu-panel');
        const closeBtn = document.getElementById('mobile-menu-close');
        let isMenuOpen = false;

        function openMenu() {
            isMenuOpen = true;
            document.body.style.overflow = 'hidden';
            overlay.classList.remove('invisible', 'opacity-0');
            panel.classList.remove('translate-x-full');
            toggle.setAttribute('data-menu-open', 'true');

            // Animate menu items
            const menuItems = panel.querySelectorAll('.menu-item');
            menuItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(20px)';
                setTimeout(() => {
                    item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, index * 50);
            });
        }

        function closeMenu() {
            isMenuOpen = false;
            document.body.style.overflow = '';
            overlay.classList.add('invisible', 'opacity-0');
            panel.classList.add('translate-x-full');
            toggle.removeAttribute('data-menu-open');

            // Reset menu items animation
            const menuItems = panel.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.style.transition = '';
                item.style.opacity = '';
                item.style.transform = '';
            });
        }

        if(toggle && overlay && panel) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                if (isMenuOpen) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });

            // Close menu when clicking overlay
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    closeMenu();
                }
            });

            // Close menu when clicking close button
            if (closeBtn) {
                closeBtn.addEventListener('click', closeMenu);
            }

            // Close menu when clicking on menu links
            const menuLinks = panel.querySelectorAll('a');
            menuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    setTimeout(closeMenu, 150);
                });
            });

            // Close menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isMenuOpen) {
                    closeMenu();
                }
            });
        }
    })();

    function toggleSubmenu(btn){
        const container = btn.closest('.menu-item').querySelector('[data-submenu]');
        const icon = btn.querySelector('i');

        if(container && icon){
            const isOpen = !container.classList.contains('hidden');

            if (isOpen) {
                // Close submenu
                container.style.maxHeight = '0';
                container.style.opacity = '0';
                setTimeout(() => {
                    container.classList.add('hidden');
                    container.style.maxHeight = '';
                    container.style.opacity = '';
                }, 200);
                icon.classList.remove('rotate-180');
            } else {
                // Open submenu
                container.classList.remove('hidden');
                container.style.maxHeight = '0';
                container.style.opacity = '0';
                container.style.transition = 'max-height 0.3s ease, opacity 0.2s ease';

                setTimeout(() => {
                    container.style.maxHeight = container.scrollHeight + 'px';
                    container.style.opacity = '1';
                }, 10);

                icon.classList.add('rotate-180');
            }
        }
    }

    // Auto-search with debounce
    // Toggle donate form
    function toggleDonateForm() {
        const form = document.getElementById('donate-form');
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
            form.classList.add('opacity-100', 'visible');
        } else {
            form.classList.add('hidden');
            form.classList.remove('opacity-100', 'visible');
        }
    }

    // Handle amount selection
    function handleAmountChange() {
        const select = document.querySelector('select[name="amount"]');
        const customAmount = document.getElementById('custom-amount');

        if (select.value === '') {
            customAmount.classList.remove('hidden');
        } else {
            customAmount.classList.add('hidden');
        }
    }

    // Toggle mobile donate form
    function toggleMobileDonateForm() {
        const form = document.getElementById('mobile-donate-form');
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
        } else {
            form.classList.add('hidden');
        }
    }


    document.addEventListener('DOMContentLoaded', function() {
        // Setup amount selection handler
        const amountSelect = document.querySelector('select[name="amount"]');
        if (amountSelect) {
            amountSelect.addEventListener('change', handleAmountChange);
        }


        // Close donate form when clicking outside
        document.addEventListener('click', function(e) {
            const form = document.getElementById('donate-form');
            const button = document.querySelector('button[onclick="toggleDonateForm()"]');

            if (form && button && !form.contains(e.target) && !button.contains(e.target)) {
                form.classList.add('hidden');
                form.classList.remove('opacity-100', 'visible');
            }
        });

    });
</script>

<style>
    /* Additional CSS for smooth animations */
    .menu-item {
        opacity: 0;
        transform: translateX(20px);
    }

    .submenu {
        transition: max-height 0.3s ease, opacity 0.2s ease;
        overflow: hidden;
    }

    .submenu.hidden {
        max-height: 0 !important;
        opacity: 0 !important;
    }

    /* Hamburger animation styles */
    [data-menu-open] .bg-white:nth-child(1) {
        transform: rotate(45deg) translateY(6px);
    }

    [data-menu-open] .bg-white:nth-child(2) {
        opacity: 0;
    }

    [data-menu-open] .bg-white:nth-child(3) {
        transform: rotate(-45deg) translateY(-6px);
    }
</style>
