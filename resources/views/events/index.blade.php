<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('common.events') }} - {{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }}</title>

    <!-- Open Graph Meta Tags for Social Media Sharing -->
    <meta property="og:title" content="{{ __('common.events') }}">
    <meta property="og:description" content="{{ __('common.upcoming_events_subtitle') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }}">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ __('common.events') }}">
    <meta name="twitter:description" content="{{ __('common.upcoming_events_subtitle') }}">

    <!-- Additional Meta Tags -->
    <meta name="description" content="{{ __('common.upcoming_events_subtitle') }}">
    <meta name="keywords" content="etkinlikler, {{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }}, cami, üyelik">
    <meta name="author" content="{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }}">
    <meta name="robots" content="index, follow">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @include('partials.theme-styles')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .elegant-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .nav-item {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--theme-link-color);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .nav-item:hover::before {
            transform: scaleY(1);
        }

        .nav-item:hover {
            background: rgba(13, 148, 136, 0.05);
            transform: translateX(4px);
        }

        .elegant-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 24px 0;
        }

        .sticky-sidebar {
            position: sticky;
            top: 2rem;
            max-height: calc(100vh - 4rem);
            overflow-y: auto;
        }
        
        /* Theme color overrides */
        .bg-teal-600 { background-color: var(--theme-link-color) !important; }
        .bg-teal-50 { background-color: rgba(13, 148, 136, 0.05) !important; }
        .text-teal-600 { color: var(--theme-link-color) !important; }
        .text-teal-700 { color: var(--theme-hover-color) !important; }
        .hover\:text-teal-600:hover { color: var(--theme-link-color) !important; }
        .hover\:text-teal-700:hover { color: var(--theme-hover-color) !important; }
        .hover\:border-teal-300:hover { border-color: var(--theme-secondary-color) !important; }
        .from-teal-500 { --tw-gradient-from: var(--theme-link-color) !important; }
        .via-teal-600 { --tw-gradient-stops: var(--tw-gradient-from), var(--theme-link-color), var(--tw-gradient-to) !important; }
        .to-teal-500 { --tw-gradient-to: var(--theme-link-color) !important; }
        .from-teal-50 { --tw-gradient-from: rgba(13, 148, 136, 0.05) !important; }
        .to-teal-100 { --tw-gradient-to: rgba(13, 148, 136, 0.1) !important; }
        .border-teal-200 { border-color: rgba(13, 148, 136, 0.2) !important; }
        .bg-teal-100 { background-color: rgba(13, 148, 136, 0.1) !important; }
        .group-hover\:bg-teal-50:hover { background-color: rgba(13, 148, 136, 0.05) !important; }
        .group-hover\:bg-teal-200:hover { background-color: rgba(13, 148, 136, 0.2) !important; }
        .group-hover\:text-teal-600:hover { color: var(--theme-link-color) !important; }
        
        /* Theme color classes */
        .theme-text { color: var(--theme-link-color) !important; }
        .theme-text-hover { color: var(--theme-hover-color) !important; }
        .theme-bg { background-color: var(--theme-primary-color) !important; }
        .theme-gradient-bg { background: var(--theme-gradient) !important; }
        .theme-border { border-color: var(--theme-link-color) !important; }
    </style>
</head>
<body>
    @include('partials.top-header')
    @include('partials.main-menu')

    <!-- Main Container -->
    <div class="relative min-h-screen">
        <!-- Main Content -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
                <!-- Left Sidebar - Hızlı Menü (Hidden on Mobile) -->
                <div class="hidden lg:block lg:col-span-1">
                    <div class="elegant-card rounded-xl p-6 sticky-sidebar">
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-2">{{ __('common.quick_menu') }}</h2>
                            <div class="elegant-divider"></div>
                        </div>

                        <nav class="space-y-1">
                            <a href="/" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200">
                                {{ __('common.home') }}
                            </a>

                            <a href="/duyurular" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200">
                                {{ __('common.announcements') }}
                            </a>

                            <a href="/haberler" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200">
                                {{ __('common.news') }}
                            </a>

                            <a href="{{ route('events.index') }}" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200 bg-teal-50 text-teal-700">
                                {{ __('common.events') }}
                            </a>

                            <a href="/uyelik-basvuru" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200">
                                {{ __('common.member_application') }}
                            </a>

                            <a href="/uye-giris" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200">
                                {{ __('common.member_login') }}
                            </a>

                            <a href="/iletisim" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200">
                                {{ __('common.contact') }}
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Right Content - Events List -->
                <div class="lg:col-span-3">
                    <!-- Header -->
                    <div class="mb-8">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                            {{ __('common.events') }}
                        </h1>
                    </div>

                    <!-- Tabs -->
                    <div class="flex justify-center mb-8">
                        <div class="inline-flex bg-white rounded-lg shadow-md p-1 border border-gray-200">
                            <a href="{{ route('events.index', ['tab' => 'all']) }}" 
                               class="px-4 py-2 text-sm rounded-lg font-medium transition-all duration-300 {{ $tab === 'all' ? 'bg-teal-600 text-white shadow-md' : 'text-gray-600 hover:text-teal-600 hover:bg-gray-50' }}">
                                {{ __('common.all_events') }}
                            </a>
                            <a href="{{ route('events.index', ['tab' => 'upcoming']) }}" 
                               class="px-4 py-2 text-sm rounded-lg font-medium transition-all duration-300 {{ $tab === 'upcoming' ? 'bg-teal-600 text-white shadow-md' : 'text-gray-600 hover:text-teal-600 hover:bg-gray-50' }}">
                                {{ __('common.upcoming_events') }}
                            </a>
                            <a href="{{ route('events.index', ['tab' => 'past']) }}" 
                               class="px-4 py-2 text-sm rounded-lg font-medium transition-all duration-300 {{ $tab === 'past' ? 'bg-teal-600 text-white shadow-md' : 'text-gray-600 hover:text-teal-600 hover:bg-gray-50' }}">
                                {{ __('common.past_events') }}
                            </a>
                        </div>
                    </div>

                    <!-- Events Grid -->
                    @if($events->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                        @foreach($events as $event)
                        <div class="group relative bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 hover:border-teal-300 transform hover:-translate-y-1 flex flex-col h-full">
                            <!-- Gradient Top Bar -->
                            <div class="h-1.5 bg-gradient-to-r from-teal-500 via-teal-600 to-teal-500 group-hover:from-teal-600 group-hover:via-teal-700 group-hover:to-teal-600 transition-all duration-300"></div>

                            <!-- Event Image - Fixed Height -->
                            <div class="relative h-40 overflow-hidden bg-gray-100">
                                @if($event->image_path)
                                <img src="{{ asset('storage/' . $event->image_path) }}" 
                                     alt="{{ $event->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300 cursor-pointer"
                                     onclick="openPhotoModal('{{ asset('storage/' . $event->image_path) }}', '{{ addslashes($event->title) }}')">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none"></div>
                                @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                    <i class="fas fa-calendar-alt text-gray-400 text-3xl"></i>
                                </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-4 flex-1 flex flex-col">
                                <!-- Date Badge -->
                                <div class="mb-3">
                                    <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-gradient-to-r from-teal-50 to-teal-100 border border-teal-200 group-hover:from-teal-100 group-hover:to-teal-200 transition-all duration-300">
                                        <i class="fas fa-calendar-alt text-teal-600 mr-1.5 text-xs"></i>
                                        <span class="font-semibold text-teal-700 text-xs">
                                            {{ $event->event_date->locale(app()->getLocale())->translatedFormat('d F Y') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Title -->
                                <h3 class="text-lg font-bold text-gray-800 mb-3 group-hover:text-teal-600 transition-colors duration-300 leading-tight line-clamp-2">
                                    {{ $event->title }}
                                </h3>

                                <!-- Description -->
                                @if($event->description)
                                <div class="mb-4 flex-1">
                                    <p class="text-gray-600 text-xs leading-relaxed line-clamp-3 group-hover:text-gray-700 transition-colors">
                                        {{ $event->description }}
                                    </p>
                                </div>
                                @endif

                                <!-- Details Section -->
                                <div class="space-y-2 mb-4">
                                    <!-- Time Only -->
                                    <div class="flex items-center space-x-2 p-2 rounded-lg bg-gray-50 group-hover:bg-teal-50 transition-colors duration-300">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center group-hover:bg-teal-200 transition-colors">
                                            <i class="fas fa-clock text-teal-600 text-xs"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs text-teal-600 font-medium">
                                                <i class="far fa-clock mr-1"></i>{{ $event->event_date->format('H:i') }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Location -->
                                    @if($event->location)
                                    <div class="flex items-center space-x-2 p-2 rounded-lg bg-gray-50 group-hover:bg-teal-50 transition-colors duration-300">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center group-hover:bg-red-200 transition-colors">
                                            <i class="fas fa-map-marker-alt text-red-600 text-xs"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs font-semibold text-gray-800 line-clamp-1">
                                                {{ $event->location }}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Footer -->
                                <div class="pt-3 border-t border-gray-100 mt-auto">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="far fa-calendar-check mr-1.5"></i>
                                            <span>{{ $event->event_date->diffForHumans() }}</span>
                                        </div>
                                        <!-- Share Buttons -->
                                        <div class="flex items-center gap-2">
                                            <button onclick="shareEventToWhatsApp({{ $event->id }}, '{{ addslashes($event->title) }}', '{{ $event->event_date->format('d.m.Y H:i') }}', '{{ addslashes($event->location ?? '') }}')" 
                                                    class="flex items-center justify-center w-7 h-7 bg-green-500 hover:bg-green-600 text-white rounded-full transition-all duration-200 hover:scale-110" 
                                                    title="WhatsApp'ta Paylaş">
                                                <i class="fab fa-whatsapp text-xs"></i>
                                            </button>
                                            <button onclick="shareEventToFacebook({{ $event->id }})" 
                                                    class="flex items-center justify-center w-7 h-7 bg-blue-600 hover:bg-blue-700 text-white rounded-full transition-all duration-200 hover:scale-110" 
                                                    title="Facebook'ta Paylaş">
                                                <i class="fab fa-facebook-f text-xs"></i>
                                            </button>
                                            <button onclick="shareEventToInstagram({{ $event->id }}, '{{ addslashes($event->title) }}', '{{ $event->event_date->format('d.m.Y H:i') }}', '{{ addslashes($event->location ?? '') }}')" 
                                                    class="flex items-center justify-center w-7 h-7 bg-pink-500 hover:bg-pink-600 text-white rounded-full transition-all duration-200 hover:scale-110" 
                                                    title="Instagram'da Paylaş">
                                                <i class="fab fa-instagram text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hover Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-br from-teal-500/0 to-teal-600/0 group-hover:from-teal-500/5 group-hover:to-teal-600/10 transition-all duration-300 pointer-events-none rounded-xl"></div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $events->appends(request()->query())->links() }}
                    </div>
                    @else
                    <!-- Empty State -->
                    <div class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 mb-6">
                            <i class="fas fa-calendar-times text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ __('common.no_events_found') }}</h3>
                        <p class="text-gray-600">{{ __('common.no_events_found_desc') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <!-- Photo Modal -->
    <div id="photoModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
        <div class="relative max-w-4xl w-full">
            <button onclick="closePhotoModal()" class="absolute -top-12 right-0 text-white text-2xl hover:text-gray-300 transition-colors z-10">
                <i class="fas fa-times"></i>
            </button>
            <div class="max-h-[90vh] overflow-auto">
                <img id="modalPhoto" src="" alt="" class="max-w-full max-h-[85vh] w-auto h-auto object-contain rounded-lg mx-auto">
            </div>
            <div id="modalPhotoTitle" class="text-white text-center mt-4 text-lg font-medium"></div>
        </div>
    </div>

    <script>
        // Photo Modal Functions
        function openPhotoModal(imageSrc, imageTitle) {
            const modal = document.getElementById('photoModal');
            const modalPhoto = document.getElementById('modalPhoto');
            const modalPhotoTitle = document.getElementById('modalPhotoTitle');
            
            if (modal && modalPhoto && modalPhotoTitle) {
                modalPhoto.src = imageSrc;
                modalPhoto.alt = imageTitle;
                modalPhotoTitle.textContent = imageTitle;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closePhotoModal() {
            const modal = document.getElementById('photoModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('photoModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closePhotoModal();
                    }
                });
            }

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closePhotoModal();
                }
            });
        });
    </script>

    <script>
        // Event Share Functions - Global scope
        window.shareEventToWhatsApp = function(eventId, title, date, location) {
            const url = window.location.origin + '/etkinlikler-liste';
            const orgName = @json(\App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi'));

            let text = "*" + title + "*\n\n";
            text += "Tarih: " + date + "\n";
            if (location) {
                text += "Konum: " + location + "\n";
            }
            text += "\n" + orgName + "\n\n";
            text += "Detaylı bilgi için: " + url;

            const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(text)}`;
            window.open(whatsappUrl, '_blank');
        };

        window.shareEventToFacebook = function(eventId) {
            const url = window.location.origin + '/etkinlikler-liste';
            const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
            window.open(facebookUrl, '_blank', 'width=600,height=400');
        };

        window.shareEventToInstagram = function(eventId, title, date, location) {
            const url = window.location.origin + '/etkinlikler-liste';
            
            let text = title + "\n\n";
            text += "Tarih: " + date + "\n";
            if (location) {
                text += "Konum: " + location + "\n";
            }
            text += "\n" + url;

            // Instagram uygulamasına yönlendir (mobilde)
            if (/Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                // Mobil cihazda Instagram uygulamasına yönlendir
                const instagramUrl = `instagram://story-camera`;
                window.location.href = instagramUrl;

                // Fallback: Instagram web'e yönlendir
                setTimeout(() => {
                    window.open('https://www.instagram.com/', '_blank');
                }, 1000);
            } else {
                // Desktop'ta Instagram web'e yönlendir
                window.open('https://www.instagram.com/', '_blank');
            }

            // Metni de kopyala
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    showEventToast('Instagram açıldı, metin kopyalandı!', 'success');
                });
            } else {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showEventToast('Instagram açıldı, metin kopyalandı!', 'success');
            }
        };

        function showEventToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 transform translate-x-full flex items-center space-x-2`;

            if (type === 'success') {
                toast.className += ' bg-green-500';
                toast.innerHTML = `<i class="fas fa-check-circle"></i><span>${message}</span>`;
            } else if (type === 'error') {
                toast.className += ' bg-red-500';
                toast.innerHTML = `<i class="fas fa-exclamation-circle"></i><span>${message}</span>`;
            } else {
                toast.className += ' bg-blue-500';
                toast.innerHTML = `<i class="fas fa-info-circle"></i><span>${message}</span>`;
            }

            document.body.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            // Animate out and remove
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (document.body.contains(toast)) {
                        document.body.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>
