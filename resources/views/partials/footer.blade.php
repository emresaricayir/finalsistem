<footer class="relative text-white overflow-hidden" style="background: linear-gradient(135deg, #1e293b 0%, var(--theme-primary-color, #0d9488) 50%, #1e293b 100%);">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <!-- Top Border -->
    <div class="absolute top-0 left-0 right-0 h-1" style="background: var(--theme-gradient, linear-gradient(to right, var(--theme-secondary-color, #14b8a6), var(--theme-primary-color, #0d9488), var(--theme-secondary-color, #14b8a6)));"></div>
    <!-- Main Footer Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 md:py-8 lg:py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-6 md:gap-8">
            <!-- Organization Info -->
            <div class="sm:col-span-2 lg:col-span-2 space-y-4 sm:space-y-6 md:space-y-8">
                <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                    @if(\App\Models\Settings::hasLogo())
                        <div class="relative">
                            <img src="{{ \App\Models\Settings::getLogoUrl() }}" alt="{{ \App\Models\Settings::get('organization_name') }}" class="w-12 h-12 sm:w-16 sm:h-16 object-contain bg-white/10 backdrop-blur-sm rounded-xl p-2 sm:p-3 mx-auto sm:mx-0 border border-white/20">
                            <div class="absolute inset-0 rounded-xl" style="background: linear-gradient(135deg, rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2), transparent);"></div>
                        </div>
                    @else
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl flex items-center justify-center mx-auto sm:mx-0 shadow-lg" style="background: var(--theme-gradient, linear-gradient(135deg, var(--theme-primary-color, #0d9488), var(--theme-secondary-color, #14b8a6))); border: 1px solid rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.3);">
                            <i class="fas fa-mosque text-white text-lg sm:text-2xl"></i>
                        </div>
                    @endif
                    <div class="text-center sm:text-left">
                        <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-white mb-1 sm:mb-2">{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }}</h3>
                        @if(\App\Models\Settings::get('organization_subtitle'))
                            <p class="text-xs sm:text-sm md:text-base font-medium" style="color: var(--theme-secondary-color, #14b8a6);">{{ \App\Models\Settings::get('organization_subtitle') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="space-y-3 sm:space-y-4 md:space-y-5">
                    @if(\App\Models\Settings::get('address'))
                        <div class="flex items-start space-x-3 sm:space-x-4 group">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors" style="background: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2);" onmouseover="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.3)'" onmouseout="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2)'">
                                <i class="fas fa-map-marker-alt text-xs sm:text-sm" style="color: var(--theme-secondary-color, #14b8a6);"></i>
                            </div>
                            <span class="text-gray-300 text-xs sm:text-sm md:text-base leading-relaxed">{{ \App\Models\Settings::get('address') }}</span>
                        </div>
                    @endif
                    @if(\App\Models\Settings::get('phone'))
                        <div class="flex items-center space-x-3 sm:space-x-4 group">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors" style="background: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2);" onmouseover="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.3)'" onmouseout="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2)'">
                                <i class="fas fa-phone text-xs sm:text-sm" style="color: var(--theme-secondary-color, #14b8a6);"></i>
                            </div>
                            <a href="tel:{{ \App\Models\Settings::get('phone') }}" class="text-gray-300 hover:text-white transition-colors text-xs sm:text-sm md:text-base">{{ \App\Models\Settings::get('phone') }}</a>
                        </div>
                    @endif
                    @if(\App\Models\Settings::get('email'))
                        <div class="flex items-center space-x-3 sm:space-x-4 group">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors" style="background: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2);" onmouseover="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.3)'" onmouseout="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2)'">
                                <i class="fas fa-envelope text-xs sm:text-sm" style="color: var(--theme-secondary-color, #14b8a6);"></i>
                            </div>
                            <a href="mailto:{{ \App\Models\Settings::get('email') }}" class="text-gray-300 hover:text-white transition-colors text-xs sm:text-sm md:text-base break-all">{{ \App\Models\Settings::get('email') }}</a>
                        </div>
                    @endif
                </div>

                <!-- Mini Map -->
                @if(\App\Models\Settings::get('map_latitude') && \App\Models\Settings::get('map_longitude'))
                    <div class="mt-4 sm:mt-6 md:mt-8">
                        <div class="relative">
                            <h5 class="text-xs sm:text-sm md:text-base font-semibold text-white mb-2 sm:mb-3 flex items-center">
                                <i class="fas fa-map-marked-alt mr-1 sm:mr-2 text-xs sm:text-sm" style="color: var(--theme-secondary-color, #14b8a6);"></i>
                                {{ __('common.our_location') }}
                            </h5>
                            <div class="relative w-full sm:w-4/5 h-28 sm:h-36 md:h-40 lg:h-48 bg-gray-800 rounded-lg overflow-hidden border border-white/10 group">
                                <div id="footer-map" class="w-full h-full"></div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none"></div>

                                <!-- Map Overlay Info -->
                                <div class="absolute top-1 right-1 sm:top-2 sm:right-2 bg-white/90 backdrop-blur-sm rounded-lg px-1 py-0.5 sm:px-2 sm:py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-map-marker-alt text-xs" style="color: var(--theme-link-color, #0d9488);"></i>
                                        <span class="text-xs font-medium text-gray-700">{{ \App\Models\Settings::get('organization_name', 'Konum') }}</span>
                                    </div>
                                </div>

                                <!-- Click to View Full Map -->
                                <div class="absolute bottom-1 left-1 right-1 sm:bottom-2 sm:left-2 sm:right-2">
                                    <a href="/iletisim" class="block text-white text-xs font-medium py-0.5 px-1 sm:py-1 sm:px-2 rounded text-center transition-colors duration-200" style="background: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.9);" onmouseover="this.style.background='var(--theme-link-color, #0d9488)'" onmouseout="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.9)'">
                                        <i class="fas fa-expand mr-1"></i>
                                        <span class="hidden sm:inline">{{ __('common.detailed_map') }}</span>
                                        <span class="sm:hidden">{{ __('common.map') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Quick Links -->
            <div class="space-y-3 sm:space-y-4 md:space-y-6">
                <div class="relative">
                    <h4 class="text-base sm:text-lg md:text-xl font-bold text-white mb-3 sm:mb-4 md:mb-6 relative">
                        <span class="relative z-10">{{ __('common.quick_access') }}</span>
                        <div class="absolute bottom-0 left-0 w-8 sm:w-12 h-0.5" style="background: var(--theme-gradient, linear-gradient(to right, var(--theme-secondary-color, #14b8a6), var(--theme-primary-color, #0d9488)));"></div>
                    </h4>
                </div>
                <ul class="space-y-2 sm:space-y-3 md:space-y-4">
                    <li>
                        <a href="/" class="text-gray-300 hover:text-white transition-all duration-300 text-xs sm:text-sm md:text-base flex items-center group">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center mr-2 sm:mr-3 transition-colors" style="background: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2);" onmouseover="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.3)'" onmouseout="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2)'">
                                <i class="fas fa-home text-xs" style="color: var(--theme-secondary-color, #14b8a6);"></i>
                            </div>
                            <span class="group-hover:translate-x-1 transition-transform duration-300">{{ __('common.home') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="/duyurular" class="text-gray-300 hover:text-white transition-all duration-300 text-xs sm:text-sm md:text-base flex items-center group">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center mr-2 sm:mr-3 transition-colors" style="background: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2);" onmouseover="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.3)'" onmouseout="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2)'">
                                <i class="fas fa-bullhorn text-xs" style="color: var(--theme-secondary-color, #14b8a6);"></i>
                            </div>
                            <span class="group-hover:translate-x-1 transition-transform duration-300">{{ __('common.announcements') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="/haberler" class="text-gray-300 hover:text-white transition-all duration-300 text-xs sm:text-sm md:text-base flex items-center group">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center mr-2 sm:mr-3 transition-colors" style="background: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2);" onmouseover="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.3)'" onmouseout="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2)'">
                                <i class="fas fa-newspaper text-xs" style="color: var(--theme-secondary-color, #14b8a6);"></i>
                            </div>
                            <span class="group-hover:translate-x-1 transition-transform duration-300">{{ __('common.news') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="/yonetim-kurulu" class="text-gray-300 hover:text-white transition-all duration-300 text-xs sm:text-sm md:text-base flex items-center group">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center mr-2 sm:mr-3 transition-colors" style="background: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2);" onmouseover="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.3)'" onmouseout="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2)'">
                                <i class="fas fa-users text-xs" style="color: var(--theme-secondary-color, #14b8a6);"></i>
                            </div>
                            <span class="group-hover:translate-x-1 transition-transform duration-300">{{ __('common.board_of_directors') }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Member Services -->
            <div class="space-y-3 sm:space-y-4 md:space-y-6">
                <div class="relative">
                    <h4 class="text-base sm:text-lg md:text-xl font-bold text-white mb-3 sm:mb-4 md:mb-6 relative">
                        <span class="relative z-10">{{ __('common.member_services') }}</span>
                        <div class="absolute bottom-0 left-0 w-8 sm:w-12 h-0.5" style="background: var(--theme-gradient, linear-gradient(to right, var(--theme-secondary-color, #14b8a6), var(--theme-primary-color, #0d9488)));"></div>
                    </h4>
                </div>
                <ul class="space-y-2 sm:space-y-3 md:space-y-4">
                    <li>
                        <a href="{{ route('member.application') }}" class="text-gray-300 hover:text-white transition-all duration-300 text-xs sm:text-sm md:text-base flex items-center group">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center mr-2 sm:mr-3 transition-colors" style="background: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2);" onmouseover="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.3)'" onmouseout="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2)'">
                                <i class="fas fa-user-plus text-xs" style="color: var(--theme-secondary-color, #14b8a6);"></i>
                            </div>
                            <span class="group-hover:translate-x-1 transition-transform duration-300">{{ __('common.member_application') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('member.login') }}" class="text-gray-300 hover:text-white transition-all duration-300 text-xs sm:text-sm md:text-base flex items-center group">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center mr-2 sm:mr-3 transition-colors" style="background: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2);" onmouseover="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.3)'" onmouseout="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2)'">
                                <i class="fas fa-sign-in-alt text-xs" style="color: var(--theme-secondary-color, #14b8a6);"></i>
                            </div>
                            <span class="group-hover:translate-x-1 transition-transform duration-300">{{ __('common.member_login') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="/sayfa/hakkimizda" class="text-gray-300 hover:text-white transition-all duration-300 text-xs sm:text-sm md:text-base flex items-center group">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center mr-2 sm:mr-3 transition-colors" style="background: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2);" onmouseover="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.3)'" onmouseout="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2)'">
                                <i class="fas fa-info-circle text-xs" style="color: var(--theme-secondary-color, #14b8a6);"></i>
                            </div>
                            <span class="group-hover:translate-x-1 transition-transform duration-300">{{ __('common.about_us') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="/iletisim" class="text-gray-300 hover:text-white transition-all duration-300 text-xs sm:text-sm md:text-base flex items-center group">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center mr-2 sm:mr-3 transition-colors" style="background: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2);" onmouseover="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.3)'" onmouseout="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2)'">
                                <i class="fas fa-envelope text-xs" style="color: var(--theme-secondary-color, #14b8a6);"></i>
                            </div>
                            <span class="group-hover:translate-x-1 transition-transform duration-300">{{ __('common.contact') }}</span>
                        </a>
                    </li>
                </ul>

                <!-- Social Media -->
                @if(\App\Models\Settings::get('facebook_url') || \App\Models\Settings::get('twitter_url') || \App\Models\Settings::get('instagram_url') || \App\Models\Settings::get('youtube_url'))
                    <div class="mt-4 sm:mt-6 md:mt-8">
                        <h5 class="text-xs sm:text-sm md:text-base font-semibold text-white mb-2 sm:mb-3 md:mb-4">{{ __('common.social_media') }}</h5>
                        <div class="flex flex-wrap gap-1.5 sm:gap-2 justify-center sm:justify-start">
                            @if(\App\Models\Settings::get('facebook_url'))
                                <a href="{{ \App\Models\Settings::get('facebook_url') }}" target="_blank" rel="noopener noreferrer"
                                   class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-105 hover:shadow-lg group">
                                    <i class="fab fa-facebook-f text-white text-xs sm:text-sm group-hover:scale-110 transition-transform duration-300"></i>
                                </a>
                            @endif
                            @if(\App\Models\Settings::get('instagram_url'))
                                <a href="{{ \App\Models\Settings::get('instagram_url') }}" target="_blank" rel="noopener noreferrer"
                                   class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-105 hover:shadow-lg group">
                                    <i class="fab fa-instagram text-white text-xs sm:text-sm group-hover:scale-110 transition-transform duration-300"></i>
                                </a>
                            @endif
                            @if(\App\Models\Settings::get('youtube_url'))
                                <a href="{{ \App\Models\Settings::get('youtube_url') }}" target="_blank" rel="noopener noreferrer"
                                   class="w-8 h-8 sm:w-10 sm:h-10 bg-red-600 hover:bg-red-700 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-105 hover:shadow-lg group">
                                    <i class="fab fa-youtube text-white text-xs sm:text-sm group-hover:scale-110 transition-transform duration-300"></i>
                                </a>
                            @endif
                            @if(\App\Models\Settings::get('twitter_url'))
                                <a href="{{ \App\Models\Settings::get('twitter_url') }}" target="_blank" rel="noopener noreferrer"
                                   class="w-8 h-8 sm:w-10 sm:h-10 bg-sky-500 hover:bg-sky-600 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-105 hover:shadow-lg group">
                                    <i class="fab fa-twitter text-white text-xs sm:text-sm group-hover:scale-110 transition-transform duration-300"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Copyright Section -->
    <div class="relative border-t border-white/10 py-4 sm:py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-2 sm:space-y-3">
                <!-- Legal Links -->
                <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-4 mb-2 sm:mb-3">
                    <a href="/sayfa/datenschutz" class="text-gray-300 hover:text-white transition-colors duration-200 text-xs sm:text-sm">
                        {{ __('common.privacy_policy') }}
                    </a>
                    <span class="text-gray-500">|</span>
                    <a href="/sayfa/impressum" class="text-gray-300 hover:text-white transition-colors duration-200 text-xs sm:text-sm">
                        {{ __('common.impressum') }}
                    </a>
                    @if(\App\Models\Page::where('slug', 'cerez-politikasi')->exists())
                    <span class="text-gray-500">|</span>
                    <a href="/sayfa/cerez-politikasi" class="text-gray-300 hover:text-white transition-colors duration-200 text-xs sm:text-sm">
                        {{ __('common.cookie_policy') }}
                    </a>
                    @endif
                </div>
                
                <p class="text-gray-300 font-medium text-xs sm:text-sm md:text-base">
                    &copy; {{ date('Y') }} {{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }} - {{ __('common.all_rights_reserved') }}.
                </p>
                <p class="text-gray-400 text-xs sm:text-xs md:text-sm flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-0">
                    <span class="sm:mr-2">{{ __('common.design_and_coding') }}</span>
                    <span class="sm:mx-2" style="color: white !important;"><i class="fas fa-code"></i></span>
                    <a href="https://web.derneksistemi.de" target="_blank" rel="noopener noreferrer" class="font-medium transition-colors duration-200" style="color: white !important;" onmouseover="this.style.color='white'" onmouseout="this.style.color='white'">derneksistemi.de</a>
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Footer Map Script -->
@if(\App\Models\Settings::get('map_latitude') && \App\Models\Settings::get('map_longitude'))
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const latitude = {{ \App\Models\Settings::get('map_latitude', 0) }};
    const longitude = {{ \App\Models\Settings::get('map_longitude', 0) }};
    const zoom = {{ \App\Models\Settings::get('map_zoom', 15) }};

    if (latitude && longitude) {
        // Create map with limited interaction for footer
        const map = L.map('footer-map', {
            zoomControl: false,
            attributionControl: false,
            dragging: false,
            touchZoom: false,
            doubleClickZoom: false,
            scrollWheelZoom: false,
            boxZoom: false,
            keyboard: false
        }).setView([latitude, longitude], zoom);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Custom marker icon (similar to contact page)
        const customIcon = L.divIcon({
            className: 'custom-marker',
            html: `
                <div style="
                    background: var(--theme-gradient, linear-gradient(135deg, var(--theme-primary-color, #0d9488), var(--theme-secondary-color, #14b8a6)));
                    width: 24px;
                    height: 24px;
                    border-radius: 50% 50% 50% 0;
                    transform: rotate(-45deg);
                    border: 3px solid white;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <div style="
                        transform: rotate(45deg);
                        color: white;
                        font-size: 12px;
                        font-weight: bold;
                    ">📍</div>
                </div>
            `,
            iconSize: [24, 24],
            iconAnchor: [12, 12],
            popupAnchor: [0, -12]
        });

        // Add marker with custom icon
        const marker = L.marker([latitude, longitude], { icon: customIcon }).addTo(map);

        // Add popup with organization info
        const orgName = '{{ \App\Models\Settings::get("organization_name", "Cami") }}';
        const orgAddress = '{{ \App\Models\Settings::get("address", "") }}';

        const popupContent = `
            <div style="padding: 8px; min-width: 150px; text-align: center;">
                <h3 style="margin: 0 0 4px 0; font-weight: 600; color: #1f2937; font-size: 14px;">${orgName}</h3>
                ${orgAddress ? `<p style="margin: 0 0 8px 0; font-size: 12px; color: #6b7280;">${orgAddress}</p>` : ''}
                <div style="margin-top: 8px;">
                    <a href="/iletisim" style="
                        display: inline-block;
                        background: var(--theme-link-color, #0d9488);
                        color: white;
                        padding: 4px 8px;
                        border-radius: 4px;
                        text-decoration: none;
                        font-size: 11px;
                        font-weight: 500;
                    ">{{ __('common.detailed_map') }}</a>
                </div>
            </div>
        `;

        marker.bindPopup(popupContent);

        // Style the map container
        map.getContainer().style.borderRadius = '8px';
    }
});
</script>
@endif
