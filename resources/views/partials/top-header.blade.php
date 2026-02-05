@php
    $orgName = \App\Models\Settings::get('organization_name', 'Dernek Adı');
    $orgSubtitle = \App\Models\Settings::get('organization_subtitle');
    $facebook = \App\Models\Settings::get('facebook_url');
    $twitter = \App\Models\Settings::get('twitter_url');
    $instagram = \App\Models\Settings::get('instagram_url');
    $youtube = \App\Models\Settings::get('youtube_url');
@endphp

<div class="bg-white"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center justify-between relative">
        <a href="{{ route('welcome') }}" class="flex items-center space-x-4 -ml-2 hover:opacity-80 transition-opacity duration-200">
            @if(\App\Models\Settings::hasLogo())
                <img src="{{ \App\Models\Settings::getLogoUrl() }}" alt="{{ $orgName }}" class="h-16 w-auto">
            @else
                <div class="h-16 w-16 rounded flex items-center justify-center" style="background-color: var(--theme-link-color, {{ $themeLinkColor ?? '#0d9488' }});">
                    <i class="fas fa-mosque text-white text-3xl"></i>
                </div>
            @endif
            <div>
                @if(!empty($orgSubtitle))
                    <div class="text-xs text-red-600 tracking-widest">{{ $orgSubtitle }}</div>
                @endif
                <div class="text-lg font-semibold text-gray-900">{{ $orgName }}</div>
            </div>
        </a>

        <!-- Center Image -->
        <div class="absolute left-1/2 transform -translate-x-1/2 -top-1 hidden md:block">
            <img src="{{ asset('storage/templates/desen.png') }}" alt="Suliet" class="h-20 w-auto">
        </div>

        <div class="hidden md:flex items-center space-x-6">
            <!-- Social Media Icons Grid -->
            <div class="grid grid-cols-2 gap-2">
                <!-- First Row -->
                <div class="flex space-x-2">
                    @if($facebook)
                        <a href="{{ $facebook }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-105 hover:shadow-lg group">
                            <i class="fab fa-facebook-f text-sm group-hover:scale-110 transition-transform duration-300"></i>
                        </a>
                    @endif
                    @if($instagram)
                        <a href="{{ $instagram }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-105 hover:shadow-lg group">
                            <i class="fab fa-instagram text-sm group-hover:scale-110 transition-transform duration-300"></i>
                        </a>
                    @endif
                </div>

                <!-- Second Row -->
                <div class="flex space-x-2">
                    @if($youtube)
                        <a href="{{ $youtube }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-red-600 hover:bg-red-700 text-white rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-105 hover:shadow-lg group">
                            <i class="fab fa-youtube text-sm group-hover:scale-110 transition-transform duration-300"></i>
                        </a>
                    @endif
                    @if($twitter)
                        <a href="{{ $twitter }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-sky-500 hover:bg-sky-600 text-white rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-105 hover:shadow-lg group">
                            <i class="fab fa-twitter text-sm group-hover:scale-110 transition-transform duration-300"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Language Switcher - En sağda -->
            <div class="flex items-center space-x-0 bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <a href="{{ route('language.switch', 'tr') }}" 
                   class="flex items-center space-x-2 px-4 py-2.5 transition-all duration-200 {{ app()->getLocale() === 'tr' ? 'text-white' : 'text-gray-700 hover:bg-gray-50' }}"
                   style="{{ app()->getLocale() === 'tr' ? 'background-color: var(--theme-link-color, ' . ($themeLinkColor ?? '#0d9488') . ');' : '' }}"
                   title="Türkçe">
                    <img src="{{ asset('storage/templates/tr.png') }}" alt="Türkçe" class="w-5 h-4 object-cover rounded-sm">
                    <span class="text-sm font-semibold">Türkçe</span>
                </a>
                <span class="w-px h-6 bg-gray-300"></span>
                <a href="{{ route('language.switch', 'de') }}" 
                   class="flex items-center space-x-2 px-4 py-2.5 transition-all duration-200 {{ app()->getLocale() === 'de' ? 'text-white' : 'text-gray-700 hover:bg-gray-50' }}"
                   style="{{ app()->getLocale() === 'de' ? 'background-color: var(--theme-link-color, ' . ($themeLinkColor ?? '#0d9488') . ');' : '' }}"
                   title="Deutsch">
                    <img src="{{ asset('storage/templates/de.png') }}" alt="Deutsch" class="w-5 h-4 object-cover rounded-sm">
                    <span class="text-sm font-semibold">Deutsch</span>
                </a>
            </div>
        </div>
    </div>
</div>
