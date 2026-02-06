<!DOCTYPE html>
<html lang="tr" class="h-full bg-gradient-to-br from-slate-50 to-blue-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cami Üyelik Sistemi') - Admin Panel</title>

    <!-- Favicon -->
    @if(\App\Models\Settings::hasFavicon())
        <link rel="icon" type="image/x-icon" href="{{ \App\Models\Settings::getFaviconUrl() }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ \App\Models\Settings::getFaviconUrl() }}">
    @endif

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Fonts -->
    <link href="//fonts.googleapis.com/css?family=Titillium+Web:400,600&amp;subset=latin-ext" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TR:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Noto Sans TR', 'Titillium Web', 'Inter', sans-serif; }

        /* Tarih input'ları için Türkçe ayarları */
        input[type="date"] {
            direction: ltr;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            color: #6b7280;
        }
        .glass-effect { backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.8); }
        .sidebar-gradient {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-right: 1px solid rgba(148, 163, 184, 0.1);
        }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .btn-primary:hover { background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%); }
        .btn-success { background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); }
        .btn-success:hover { background: linear-gradient(135deg, #38a169 0%, #2f855a 100%); }
        .btn-danger { background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); }
        .btn-danger:hover { background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%); }
        .btn-warning { background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); }
        .btn-warning:hover { background: linear-gradient(135deg, #dd6b20 0%, #c05621 100%); }
        .btn-info { background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); }
        .btn-info:hover { background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%); }

        /* Modern Dropdown menu animations */
        .dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateY(-10px);
            border-radius: 0.75rem;
            margin-top: 0.5rem;
        }

        .dropdown-content.open {
            max-height: 500px;
            opacity: 1;
            transform: translateY(0);
        }

        .dropdown-arrow {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown-arrow.rotate {
            transform: rotate(180deg);
        }

        /* Dropdown item animations */
        .dropdown-content a {
            transform: translateX(-10px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown-content.open a {
            transform: translateX(0);
            opacity: 1;
        }

        .dropdown-content.open a:nth-child(1) { transition-delay: 0.1s; }
        .dropdown-content.open a:nth-child(2) { transition-delay: 0.15s; }
        .dropdown-content.open a:nth-child(3) { transition-delay: 0.2s; }
        .dropdown-content.open a:nth-child(4) { transition-delay: 0.25s; }
        .dropdown-content.open a:nth-child(5) { transition-delay: 0.3s; }
        .dropdown-content.open a:nth-child(6) { transition-delay: 0.35s; }



        /* Custom scrollbar */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(148, 163, 184, 0.3);
            border-radius: 2px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(148, 163, 184, 0.5);
        }

        /* Modern Menu Styles */
        .menu-item {
            position: relative;
            transition: all 0.2s ease;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 0 2px 2px 0;
            transition: height 0.2s ease;
        }

        .menu-item.active::before {
            height: 70%;
        }

        .menu-link {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .menu-link:hover {
            background: rgba(59, 130, 246, 0.1);
            transform: translateX(4px);
        }

        .menu-link.active {
            background: rgba(59, 130, 246, 0.15);
            border-left: 3px solid #3b82f6;
        }

        .sub-menu-item {
            position: relative;
            transition: all 0.2s ease;
            border-radius: 8px;
        }

        .sub-menu-item:hover {
            background: rgba(59, 130, 246, 0.08);
            transform: translateX(2px);
        }

        .sub-menu-item::before {
            content: '';
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            background: rgba(148, 163, 184, 0.4);
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .sub-menu-item:hover::before {
            background: #3b82f6;
            transform: translateY(-50%) scale(1.2);
        }

        /* Modern Sidebar Styles */
        .sidebar-gradient {
            position: relative;
            overflow: hidden;
        }

        .sidebar-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.1) 0%, rgba(14, 165, 233, 0.1) 100%);
            pointer-events: none;
        }

        /* Corporate Menu animations */
        .menu-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .menu-item:hover {
            transform: translateX(2px);
        }

        /* Subtle hover effects */
        .sidebar-btn:hover {
            background: rgba(255, 255, 255, 0.08) !important;
        }

        /* Modern button styles */
        .sidebar-btn {
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .sidebar-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .sidebar-btn:hover::before {
            left: 100%;
        }

        /* Badge animations */
        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .pulse-badge {
            animation: pulse-badge 2s infinite;
        }
    </style>
</head>
<body class="h-full">
    <div class="flex h-screen bg-gradient-to-br from-slate-50 to-blue-50">
        <!-- Desktop Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col md:h-screen">
            <div class="sidebar-gradient shadow-xl border-r border-slate-600/30 h-full flex flex-col">
                <div class="flex items-center h-24 px-6 flex-shrink-0 border-b border-slate-600/20">
                    <div class="w-full pt-4">
                        <h1 class="text-white font-bold text-xl tracking-wide">{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik') }}</h1>
                        <p class="text-slate-400 text-xs font-medium uppercase tracking-wider mt-1">Yönetim Paneli</p>
                    </div>
                </div>

                <nav class="flex-1 flex flex-col overflow-hidden">
                    <div class="flex-1 px-4 py-4 overflow-y-auto custom-scrollbar">
                    <div class="space-y-2 flex-1 pb-4">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}"
                           class="menu-item sidebar-btn flex items-center px-4 py-3 text-slate-200 rounded-lg transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 shadow-lg backdrop-blur-sm border-l-4 border-cyan-400' : 'hover:bg-white/5 hover:border-l-4 hover:border-white/30' }}">
                            <div class="w-6 h-6 flex items-center justify-center mr-3">
                                <i class="fas fa-tachometer-alt w-4 h-4"></i>
                            </div>
                            <span class="font-medium text-sm">Dashboard</span>
                        </a>

                        <!-- Üyelik Yönetimi -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'accountant']))
                        <div class="menu-item">
                            <button onclick="toggleDropdown('membership')"
                                    class="sidebar-btn w-full flex items-center justify-between px-4 py-3 text-slate-200 rounded-lg transition-all duration-300 hover:bg-white/5 {{ request()->routeIs('admin.members.*') || request()->routeIs('admin.dues.*') || request()->routeIs('admin.payments.*') || request()->routeIs('admin.monthly-payments') || request()->routeIs('admin.elections.*') || request()->routeIs('admin.access-logs.*') ? 'bg-white/10 border-l-4 border-cyan-400' : 'hover:border-l-4 hover:border-white/30' }}">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 flex items-center justify-center mr-3">
                                        <i class="fas fa-users w-4 h-4"></i>
                                    </div>
                                    <span class="font-medium text-sm">Üyelik Yönetimi</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow transition-transform duration-300 text-xs" id="membership-arrow"></i>
                            </button>
                            <div class="dropdown-content {{ request()->routeIs('admin.members.*') || request()->routeIs('admin.dues.*') || request()->routeIs('admin.payments.*') || request()->routeIs('admin.monthly-payments') || request()->routeIs('admin.elections.*') || request()->routeIs('admin.access-logs.*') ? 'open' : '' }}" id="membership-menu">
                                <div class="ml-4 mt-2 space-y-1">
                                    <a href="{{ route('admin.members.index') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.members.index') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-user-friends w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Üye Listesi</span>
                                    </a>
                                    <a href="{{ route('admin.members.pending-applications') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.members.pending-applications') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-clock w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Online Başvurular</span>
                                    </a>
                                    <a href="{{ route('admin.dues.overdue') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dues.overdue') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-exclamation-triangle w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Gecikmiş Aidatlar</span>
                                    </a>
                                    <a href="{{ route('admin.payments.index') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.payments.*') && !request()->routeIs('admin.monthly-payments') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-plus w-4 h-4 mr-3 text-green-300"></i>
                                        <span class="text-sm text-green-300 font-medium">Ödenmiş Aidatlar</span>
                                    </a>
                                    <a href="{{ route('admin.monthly-payments') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.monthly-payments') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-minus w-4 h-4 mr-3 text-red-300"></i>
                                        <span class="text-sm text-red-300 font-medium">Ödenmemiş Aidatlar</span>
                                    </a>
                                    <a href="{{ route('admin.elections.index') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.elections.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-vote-yea w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Seçim</span>
                                    </a>
                                    @if(auth()->user()->isSuperAdmin())
                                    <a href="{{ route('admin.access-logs.index') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.access-logs.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-clipboard-list w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Veri Erişim Logları</span>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Eğitim Aidatları -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'accountant', 'education']))
                        <div class="menu-item">
                            <button onclick="toggleEducationDropdown()"
                                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-xl transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.education-members.*') || request()->routeIs('admin.education-payments.*') ? 'bg-white bg-opacity-20' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-graduation-cap w-5 h-5 mr-3"></i>
                                    <span class="font-medium">Eğitim Aidatları</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow transition-transform duration-300 text-xs" id="education-arrow"></i>
                            </button>
                            <div class="dropdown-content {{ request()->routeIs('admin.education-members.*') || request()->routeIs('admin.education-payments.*') ? 'open' : '' }}" id="education-dropdown">
                                <div class="ml-4 mt-2 space-y-1">
                                    <a href="{{ route('admin.education-members.index') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.education-members.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-users w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Veliler</span>
                                    </a>
                                    <a href="{{ route('admin.education-payments.index') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.education-payments.index') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-receipt w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Ödeme Geçmişi</span>
                                    </a>
                                    <a href="{{ route('admin.education-payments.bulk') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.education-payments.bulk') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-money-bill-wave w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Toplu Ödeme</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- İçerik Yönetimi -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'editor']))
                        <div class="menu-item">
                            <button onclick="toggleDropdown('content')"
                                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-xl transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.announcements.*') || request()->routeIs('admin.news.*') || request()->routeIs('admin.quick-access.*') || request()->routeIs('admin.menu.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.prayer-times.*') ? 'bg-white bg-opacity-20' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-edit w-5 h-5 mr-3"></i>
                                    <span class="font-medium">İçerik Yönetimi</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow" id="content-arrow"></i>
                            </button>
                            <div class="dropdown-content {{ request()->routeIs('admin.announcements.*') || request()->routeIs('admin.news.*') || request()->routeIs('admin.quick-access.*') || request()->routeIs('admin.menu.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.prayer-times.*') ? 'open' : '' }}" id="content-menu">
                                <div class="ml-4 mt-2 space-y-1">
                                    <a href="{{ route('admin.announcements.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.announcements.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-bullhorn w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Duyurular</span>
                                    </a>
                                    <a href="{{ route('admin.news.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.news.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-newspaper w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Haberler</span>
                                    </a>
                                    <a href="{{ route('admin.prayer-times.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.prayer-times.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-mosque w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Namaz Vakitleri</span>
                                    </a>
                                    <a href="{{ route('admin.quick-access.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.quick-access.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-bolt w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Hızlı Menü</span>
                                    </a>
                                    <a href="{{ route('admin.menu.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.menu.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-bars w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Menü Ayarları</span>
                                    </a>
                                    <a href="{{ route('admin.pages.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.pages.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-file-alt w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Sayfalar</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Medya Galerisi -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'editor']))
                        <div class="menu-item">
                            <button onclick="toggleDropdown('media')"
                                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-xl transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.gallery-categories.*') || request()->routeIs('admin.gallery-images.*') || request()->routeIs('admin.video-categories.*') || request()->routeIs('admin.video-gallery.*') ? 'bg-white bg-opacity-20' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-images w-5 h-5 mr-3"></i>
                                    <span class="font-medium">Medya Galerisi</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow" id="media-arrow"></i>
                            </button>
                            <div class="dropdown-content {{ request()->routeIs('admin.gallery-categories.*') || request()->routeIs('admin.gallery-images.*') || request()->routeIs('admin.video-categories.*') || request()->routeIs('admin.video-gallery.*') ? 'open' : '' }}" id="media-menu">
                                <div class="ml-4 mt-2 space-y-1">
                                    <a href="{{ route('admin.gallery-categories.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.gallery-categories.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-folder-open w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Foto Kategorileri</span>
                                    </a>
                                    <a href="{{ route('admin.gallery-images.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.gallery-images.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-image w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Resim Galerisi</span>
                                    </a>
                                    <a href="{{ route('admin.video-categories.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.video-categories.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-folder w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Video Kategorileri</span>
                                    </a>
                                    <a href="{{ route('admin.video-gallery.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.video-gallery.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-video w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Video Galeri</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Yönetim Kurulu -->
                        @if(auth()->user()->isSuperAdmin())
                        <div class="menu-item">
                            <button onclick="toggleDropdown('board-members')"
                                    class="sidebar-btn w-full flex items-center justify-between px-4 py-3 text-slate-200 rounded-lg transition-all duration-300 hover:bg-white/5 {{ request()->routeIs('admin.board-members.*') || request()->routeIs('admin.personnel-categories.*') ? 'bg-white/10 border-l-4 border-cyan-400' : 'hover:border-l-4 hover:border-white/30' }}">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 flex items-center justify-center mr-3">
                                        <i class="fas fa-users w-4 h-4"></i>
                                    </div>
                                    <span class="font-medium text-sm">Yönetim Kurulu</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow transition-transform duration-300 text-xs" id="board-members-arrow"></i>
                            </button>
                            <div class="dropdown-content {{ request()->routeIs('admin.board-members.*') || request()->routeIs('admin.personnel-categories.*') ? 'open' : '' }}" id="board-members-menu">
                                <div class="ml-4 mt-2 space-y-1">
                                    <a href="{{ route('admin.board-members.index') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.board-members.index') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-user-friends w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Tüm Kişiler</span>
                                    </a>
                                    <a href="{{ route('admin.personnel-categories.index') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.personnel-categories.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-tags w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Kategoriler</span>
                                    </a>
                                    <a href="{{ route('admin.board-members.create') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.board-members.create') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-plus w-4 h-4 mr-3 text-green-300"></i>
                                        <span class="text-sm text-green-300 font-medium">Yeni Kişi Ekle</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Raporlar -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'accountant']))
                        <a href="{{ route('admin.reports.index') }}"
                           class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                            <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                            <span class="font-medium">Raporlar</span>
                        </a>
                        @endif

                        <!-- Ayarlar -->
                        @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.settings.index') }}"
                           class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings.*') && !request()->routeIs('admin.theme-settings.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                            <i class="fas fa-cog w-5 h-5 mr-3"></i>
                            <span class="font-medium">Ayarlar</span>
                        </a>
                        
                        <!-- Tema Ayarları -->
                        <a href="{{ route('admin.theme-settings.index') }}"
                           class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('admin.theme-settings.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                            <i class="fas fa-palette w-5 h-5 mr-3"></i>
                            <span class="font-medium">Tema Ayarları</span>
                        </a>
                        @endif




                        <!-- WhatsApp Hatırlatmaları -->
                        {{-- @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'accountant']))
                        <a href="{{ route('admin.whatsapp.due-reminders') }}"
                           class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('admin.whatsapp.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                            <i class="fab fa-whatsapp w-5 h-5 mr-3"></i>
                            <span class="font-medium">WhatsApp</span>
                        </a>
                        @endif --}}

                        <!-- Kullanıcı Yönetimi -->
                        @if(auth()->user()->isSuperAdmin())
                        <div class="menu-item">
                            <button onclick="toggleDropdown('users')"
                                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-xl transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.admin-users.*') ? 'bg-white bg-opacity-20' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-users-cog w-5 h-5 mr-3"></i>
                                    <span class="font-medium">Kullanıcı Yönetimi</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow" id="users-arrow"></i>
                            </button>
                            <div class="dropdown-content {{ request()->routeIs('admin.admin-users.*') ? 'open' : '' }}" id="users-menu">
                                <div class="ml-4 mt-2 space-y-1">
                                    <a href="{{ route('admin.admin-users.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.admin-users.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-users w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Admin Kullanıcıları</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- TV Üye Yansıt -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'accountant']))
                        <div class="menu-item">
                            <button onclick="toggleDropdown('tv')"
                                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-xl transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.tv-display.*') || request()->routeIs('admin.settings.tv-display') ? 'bg-white bg-opacity-20' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-tv w-5 h-5 mr-3"></i>
                                    <span class="font-medium">Aidat TV Yansıtma</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow" id="tv-arrow"></i>
                            </button>
                            <div class="dropdown-content {{ request()->routeIs('admin.tv-display.*') || request()->routeIs('admin.settings.tv-display*') ? 'open' : '' }}" id="tv-menu">
                                <div class="ml-4 mt-2 space-y-1">
                                    <a href="{{ route('admin.tv-display.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.tv-display.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}"
                                       target="_blank">
                                        <i class="fas fa-tv w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Ekrana Yansıt</span>
                                    </a>
                                    @if(auth()->user()->hasAnyRole(['super_admin', 'admin']))
                                
                                    <a href="{{ route('admin.settings.tv-display-settings.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings.tv-display-settings.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-cog w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Ayarlar</span>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Etkinlik Yönetimi -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'editor']))
                        <div class="space-y-1" x-data="{ open: {{ request()->routeIs('admin.events.*') || request()->routeIs('admin.event-advertisements.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('admin.events.*') || request()->routeIs('admin.event-advertisements.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <div class="flex items-center">
                            <i class="fas fa-calendar-alt w-5 h-5 mr-3"></i>
                            <span class="font-medium">ETKİNLİK</span>
                                </div>
                                <i class="fas fa-chevron-down w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </button>

                            <!-- Dropdown menü -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="ml-6 space-y-1 mt-2">
                                <a href="{{ route('admin.events.index') }}"
                                   class="sub-menu-item flex items-center px-4 py-2 text-slate-300 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.events.*') ? 'bg-blue-500 bg-opacity-15 text-white' : 'hover:bg-blue-500 hover:bg-opacity-10 hover:text-white' }}">
                                    <i class="fas fa-calendar w-4 h-4 mr-3 text-green-400"></i>
                                    <span class="font-normal">Etkinlikler</span>
                                </a>
                                <a href="{{ route('admin.event-advertisements.index') }}"
                                   class="sub-menu-item flex items-center px-4 py-2 text-slate-300 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.event-advertisements.*') ? 'bg-blue-500 bg-opacity-15 text-white' : 'hover:bg-blue-500 hover:bg-opacity-10 hover:text-white' }}">
                                    <i class="fas fa-ad w-4 h-4 mr-3 text-yellow-400"></i>
                                    <span class="font-normal">Reklamlar</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(auth()->user()->hasAnyRole(['super_admin', 'editor']))
                        <div class="menu-item {{ request()->routeIs('admin.vefas.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.vefas.index') }}"
                               class="menu-link flex items-center px-4 py-3 text-white font-medium transition-all duration-200 {{ request()->routeIs('admin.vefas.*') ? 'active bg-blue-500 bg-opacity-15' : 'hover:bg-blue-500 hover:bg-opacity-10' }}">
                                <i class="fas fa-heart w-5 h-5 mr-3 text-red-400"></i>
                                <span>VEFA</span>
                        </a>
                        </div>
                        @endif
                    </div>

                    <!-- User Info and Logout -->
                    <div class="mt-8 pt-6 border-t border-slate-600/20">
                        <div class="flex items-center px-4 py-3 bg-gradient-to-r from-blue-500/10 to-purple-500/10 rounded-xl mx-2 mb-4 border border-blue-500/20">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-white font-semibold text-sm">{{ Auth::user()->name }}</p>
                                <p class="text-slate-300 text-xs">Yönetici</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="px-2">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-red-500/10 rounded-xl transition-all duration-200 group border border-transparent hover:border-red-500/20">
                                <i class="fas fa-sign-out-alt w-5 h-5 mr-3 group-hover:text-red-400 transition-colors"></i>
                                <span class="font-medium">Çıkış Yap</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Mobile Sidebar -->
        <div class="md:hidden fixed inset-0 z-50" id="mobile-sidebar" style="display: none;">
            <div class="fixed inset-0 bg-black bg-opacity-50"></div>
            <div class="sidebar-gradient w-64 h-full relative z-10">
                <div class="flex items-center justify-between h-16 px-6">
                    <div class="flex items-center space-x-3">
                        @if(\App\Models\Settings::hasLogo())
                            <img src="{{ \App\Models\Settings::getLogoUrl() }}"
                                 alt="{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik') }}"
                                 class="w-10 h-10 object-contain bg-white rounded-lg p-1">
                        @else
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                            <i class="fas fa-mosque text-2xl text-purple-600"></i>
                        </div>
                        @endif
                        <div>
                            <h1 class="text-white font-bold text-lg">{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik') }}</h1>
                            <p class="text-purple-200 text-xs">Admin Panel</p>
                        </div>
                    </div>
                    <button class="text-white" onclick="toggleMobileSidebar()">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <nav class="mt-8 px-4 h-full flex flex-col overflow-y-auto custom-scrollbar">
                    <div class="space-y-2 flex-1 pb-4">
                        <!-- Dashboard -->
                        <div class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}"
                               class="menu-link flex items-center px-4 py-3 text-white font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'active bg-blue-500 bg-opacity-15' : 'hover:bg-blue-500 hover:bg-opacity-10' }}">
                                <i class="fas fa-tachometer-alt w-5 h-5 mr-3 text-blue-400"></i>
                            <span>Dashboard</span>
                        </a>
                        </div>

                        <!-- Üyelik Yönetimi -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'accountant']))
                        <div class="menu-item">
                            <button onclick="toggleMobileDropdown('mobile-membership')"
                                    class="w-full flex items-center justify-between px-4 py-3 text-white font-bold rounded-xl transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.members.*') || request()->routeIs('admin.dues.*') || request()->routeIs('admin.payments.*') || request()->routeIs('admin.monthly-payments') || request()->routeIs('admin.elections.*') || request()->routeIs('admin.access-logs.*') ? 'bg-white bg-opacity-20' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-users w-5 h-5 mr-3"></i>
                                    <span>Üyelik Yönetimi</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow text-sm" id="mobile-membership-arrow"></i>
                            </button>
                            <div class="dropdown-content {{ request()->routeIs('admin.members.*') || request()->routeIs('admin.dues.*') || request()->routeIs('admin.payments.*') || request()->routeIs('admin.monthly-payments') || request()->routeIs('admin.elections.*') || request()->routeIs('admin.access-logs.*') ? 'open' : '' }}" id="mobile-membership-menu">
                                <div class="ml-4 mt-2 space-y-1">
                        <a href="{{ route('admin.members.index') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.members.index') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-user-friends w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Üye Listesi</span>
                        </a>
                        <a href="{{ route('admin.members.pending-applications') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.members.pending-applications') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-clock w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Online Başvurular</span>
                                    </a>
                        <a href="{{ route('admin.dues.overdue') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dues.overdue') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-exclamation-triangle w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Gecikmiş Aidatlar</span>
                        </a>
                        <a href="{{ route('admin.payments.index') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.payments.*') && !request()->routeIs('admin.monthly-payments') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-plus w-4 h-4 mr-3 text-green-300"></i>
                                        <span class="text-sm text-green-300 font-medium">Ödenmiş Aidatlar</span>
                        </a>
                        <a href="{{ route('admin.monthly-payments') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.monthly-payments') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-minus w-4 h-4 mr-3 text-red-300"></i>
                                        <span class="text-sm text-red-300 font-medium">Ödenmemiş Aidatlar</span>
                                    </a>
                                    <a href="{{ route('admin.elections.index') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.elections.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-vote-yea w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Seçim</span>
                                    </a>
                                    @if(auth()->user()->isSuperAdmin())
                                    <a href="{{ route('admin.access-logs.index') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.access-logs.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-clipboard-list w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Veri Erişim Logları</span>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Eğitim Aidatları -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'accountant', 'education']))
                        <div class="menu-item">
                            <button onclick="toggleDropdownMobile('education')"
                                    class="w-full flex items-center justify-between px-4 py-3 text-white font-bold rounded-xl transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.education-members.*') || request()->routeIs('admin.education-payments.*') ? 'bg-white bg-opacity-20' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-graduation-cap w-5 h-5 mr-3"></i>
                                    <span>Eğitim Aidatları</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow transition-transform duration-300 text-xs" id="education-arrow-mobile"></i>
                            </button>
                            <div class="dropdown-content {{ request()->routeIs('admin.education-members.*') || request()->routeIs('admin.education-payments.*') ? 'open' : '' }}" id="education-dropdown-mobile">
                                <div class="ml-4 mt-2 space-y-1">
                                    <a href="{{ route('admin.education-members.index') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.education-members.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-users w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Veliler</span>
                                    </a>
                                    <a href="{{ route('admin.education-payments.index') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.education-payments.index') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-receipt w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Ödeme Geçmişi</span>
                                    </a>
                                    <a href="{{ route('admin.education-payments.bulk') }}"
                                       class="flex items-center px-4 py-2 text-white text-opacity-80 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.education-payments.bulk') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-money-bill-wave w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Toplu Ödeme</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- İçerik Yönetimi -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'editor']))
                        <div class="menu-item">
                            <button onclick="toggleMobileDropdown('mobile-content')"
                                    class="w-full flex items-center justify-between px-4 py-3 text-white font-bold rounded-xl transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.announcements.*') || request()->routeIs('admin.news.*') || request()->routeIs('admin.quick-access.*') || request()->routeIs('admin.menu.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.prayer-times.*') ? 'bg-white bg-opacity-20' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-edit w-5 h-5 mr-3"></i>
                                    <span>İçerik Yönetimi</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow text-sm" id="mobile-content-arrow"></i>
                            </button>
                            <div class="dropdown-content {{ request()->routeIs('admin.announcements.*') || request()->routeIs('admin.news.*') || request()->routeIs('admin.quick-access.*') || request()->routeIs('admin.menu.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.prayer-times.*') ? 'open' : '' }}" id="mobile-content-menu">
                                <div class="ml-4 mt-2 space-y-1">
                        <a href="{{ route('admin.announcements.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.announcements.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-bullhorn w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Duyurular</span>
                                    </a>
                                    <a href="{{ route('admin.news.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.news.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-newspaper w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Haberler</span>
                                    </a>
                                    <a href="{{ route('admin.prayer-times.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.prayer-times.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-mosque w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Namaz Vakitleri</span>
                                    </a>
                                    <a href="{{ route('admin.quick-access.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.quick-access.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-bolt w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Hızlı Menü</span>
                                    </a>
                                    <a href="{{ route('admin.menu.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.menu.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-bars w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Menü Ayarları</span>
                                    </a>
                                    <a href="{{ route('admin.pages.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.pages.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-file-alt w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Sayfalar</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Medya Galerisi -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'editor']))
                        <div class="menu-item">
                            <button onclick="toggleMobileDropdown('mobile-media')"
                                    class="w-full flex items-center justify-between px-4 py-3 text-white font-bold rounded-xl transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.gallery-categories.*') || request()->routeIs('admin.gallery-images.*') || request()->routeIs('admin.video-categories.*') || request()->routeIs('admin.video-gallery.*') ? 'bg-white bg-opacity-20' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-images w-5 h-5 mr-3"></i>
                                    <span>Medya Galerisi</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow text-sm" id="mobile-media-arrow"></i>
                            </button>
                            <div class="dropdown-content {{ request()->routeIs('admin.gallery-categories.*') || request()->routeIs('admin.gallery-images.*') || request()->routeIs('admin.video-categories.*') || request()->routeIs('admin.video-gallery.*') ? 'open' : '' }}" id="mobile-media-menu">
                                <div class="ml-4 mt-2 space-y-1">
                                    <a href="{{ route('admin.gallery-categories.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.gallery-categories.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-folder-open w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Foto Kategorileri</span>
                                    </a>
                                    <a href="{{ route('admin.gallery-images.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.gallery-images.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-image w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Resim Galerisi</span>
                                    </a>
                                    <a href="{{ route('admin.video-categories.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.video-categories.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-folder w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Video Kategorileri</span>
                                    </a>
                                    <a href="{{ route('admin.video-gallery.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.video-gallery.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-video w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Video Galeri</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Yönetim Kurulu -->
                        @if(auth()->user()->isSuperAdmin())
                        <div class="menu-item">
                            <button onclick="toggleMobileDropdown('mobile-board-members')"
                                    class="w-full flex items-center justify-between px-4 py-3 text-white font-bold rounded-xl transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.board-members.*') || request()->routeIs('admin.personnel-categories.*') ? 'bg-white bg-opacity-20' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-users w-5 h-5 mr-3"></i>
                                    <span>Yönetim Kurulu</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow text-sm" id="mobile-board-members-arrow"></i>
                            </button>
                            <div class="dropdown-content {{ request()->routeIs('admin.board-members.*') || request()->routeIs('admin.personnel-categories.*') ? 'open' : '' }}" id="mobile-board-members-menu">
                                <div class="ml-4 mt-2 space-y-1">
                                    <a href="{{ route('admin.board-members.index') }}"
                                       class="flex items-center px-4 py-2 text-white font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.board-members.index') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-user-friends w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Tüm Kişiler</span>
                                    </a>
                                    <a href="{{ route('admin.personnel-categories.index') }}"
                                       class="flex items-center px-4 py-2 text-white font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.personnel-categories.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-tags w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Kategoriler</span>
                                    </a>
                                    <a href="{{ route('admin.board-members.create') }}"
                                       class="flex items-center px-4 py-2 text-white font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.board-members.create') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-plus w-4 h-4 mr-3 text-green-300"></i>
                                        <span class="text-sm text-green-300 font-medium">Yeni Kişi Ekle</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Raporlar -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'accountant']))
                        <a href="{{ route('admin.reports.index') }}"
                           class="flex items-center px-4 py-3 text-white font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                            <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                            <span>Raporlar</span>
                        </a>
                        @endif

                        <!-- Ayarlar -->
                        @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.settings.index') }}"
                           class="flex items-center px-4 py-3 text-white font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings.*') && !request()->routeIs('admin.theme-settings.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                            <i class="fas fa-cog w-5 h-5 mr-3"></i>
                            <span>Ayarlar</span>
                        </a>
                        
                        <!-- Tema Ayarları -->
                        <a href="{{ route('admin.theme-settings.index') }}"
                           class="flex items-center px-4 py-3 text-white font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.theme-settings.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                            <i class="fas fa-palette w-5 h-5 mr-3"></i>
                            <span>Tema Ayarları</span>
                        </a>
                        @endif



                        <!-- TV Ekranı -->
                        {{-- @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'accountant']))
                        <a href="{{ route('admin.tv-display.index') }}"
                           class="flex items-center px-4 py-3 text-white font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.tv-display.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                            <i class="fas fa-tv w-5 h-5 mr-3"></i>
                            <span>TV Ekranı</span>
                        </a>
                        @endif --}}

                        <!-- WhatsApp Hatırlatmaları -->
                        {{-- @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'accountant']))
                        <a href="{{ route('admin.whatsapp.due-reminders') }}"
                           class="flex items-center px-4 py-3 text-white font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.whatsapp.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                            <i class="fab fa-whatsapp w-5 h-5 mr-3"></i>
                            <span>WhatsApp</span>
                        </a>
                        @endif --}}

                        <!-- Kullanıcı Yönetimi -->
                        @if(auth()->user()->isSuperAdmin())
                        <div class="menu-item">
                            <button onclick="toggleMobileDropdown('mobile-users')"
                                    class="w-full flex items-center justify-between px-4 py-3 text-white font-bold rounded-xl transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.admin-users.*') ? 'bg-white bg-opacity-20' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-users-cog w-5 h-5 mr-3"></i>
                                    <span>Kullanıcı Yönetimi</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow text-sm" id="mobile-users-arrow"></i>
                            </button>
                            <div class="dropdown-content {{ request()->routeIs('admin.admin-users.*') ? 'open' : '' }}" id="mobile-users-menu">
                                <div class="ml-4 mt-2 space-y-1">
                                    <a href="{{ route('admin.admin-users.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.admin-users.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-users w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Admin Kullanıcıları</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- TV Üye Yansıt -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'accountant']))
                        <div class="menu-item">
                            <button onclick="toggleMobileDropdown('mobile-tv')"
                                    class="w-full flex items-center justify-between px-4 py-3 text-white font-bold rounded-xl transition-all duration-200 hover:bg-white hover:bg-opacity-10 {{ request()->routeIs('admin.tv-display.*') || request()->routeIs('admin.settings.tv-display') ? 'bg-white bg-opacity-20' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-tv w-5 h-5 mr-3"></i>
                                    <span>Üye Aidat Yansıtma</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow" id="mobile-tv-arrow"></i>
                            </button>
                            <div class="dropdown-content {{ request()->routeIs('admin.tv-display.*') || request()->routeIs('admin.settings.tv-display') ? 'open' : '' }}" id="mobile-tv-menu">
                                <div class="ml-4 mt-2 space-y-1">
                                    <a href="{{ route('admin.tv-display.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.tv-display.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}"
                                       target="_blank">
                                        <i class="fas fa-tv w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Ekrana Yansıt</span>
                                    </a>
                                    @if(auth()->user()->hasAnyRole(['super_admin', 'admin']))
                                    <a href="{{ route('admin.settings.tv-display-messages.index') }}"
                                       class="flex items-center px-4 py-2 text-purple-200 font-bold rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings.tv-display-messages.*') ? 'bg-white bg-opacity-15 text-white' : 'hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                                        <i class="fas fa-comments w-4 h-4 mr-3"></i>
                                        <span class="text-sm">Reklamlar</span>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Etkinlik Yönetimi -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'editor']))
                        <div class="space-y-1" x-data="{ open: {{ request()->routeIs('admin.events.*') || request()->routeIs('admin.event-advertisements.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                    class="w-full flex items-center justify-between px-4 py-3 text-white font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.events.*') || request()->routeIs('admin.event-advertisements.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <div class="flex items-center">
                            <i class="fas fa-calendar-alt w-5 h-5 mr-3"></i>
                            <span>ETKİNLİK</span>
                                </div>
                                <i class="fas fa-chevron-down w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </button>

                            <!-- Dropdown menü -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="ml-8 space-y-1">
                                <a href="{{ route('admin.events.index') }}"
                                   class="flex items-center px-3 py-2 text-white text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.events.*') ? 'bg-white bg-opacity-15' : 'hover:bg-white hover:bg-opacity-10' }}">
                                    <i class="fas fa-calendar w-4 h-4 mr-2"></i>
                                    <span class="font-normal">Etkinlikler</span>
                                </a>
                                <a href="{{ route('admin.event-advertisements.index') }}"
                                   class="flex items-center px-3 py-2 text-white text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.event-advertisements.*') ? 'bg-white bg-opacity-15' : 'hover:bg-white hover:bg-opacity-10' }}">
                                    <i class="fas fa-ad w-4 h-4 mr-2"></i>
                                    <span class="font-normal">Reklamlar</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Vefa Yönetimi -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'editor']))
                        <a href="{{ route('admin.vefas.index') }}"
                           class="flex items-center px-4 py-3 text-white font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.vefas.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                            <i class="fas fa-heart w-5 h-5 mr-3"></i>
                            <span>VEFA</span>
                        </a>
                        @endif
                    </div>

                    <!-- User Info and Logout -->
                    <div class="mt-8 pt-8 border-t border-purple-400 border-opacity-30">
                        <div class="flex items-center px-4 py-3">
                            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-purple-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-white font-medium text-sm">{{ Auth::user()->name }}</p>
                                <p class="text-purple-200 text-xs">Admin</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-2 text-white rounded-xl hover:bg-white hover:bg-opacity-10 transition-all duration-200">
                                <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                                <span class="font-medium">Çıkış Yap</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="glass-effect border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-6">
                    <div class="flex items-center">
                        <button id="mobile-menu-button" class="md:hidden text-gray-600 hover:text-gray-900 mr-4">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Anasayfa')</h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- User Dropdown -->
                        <div class="relative">
                            <button id="user-menu-button" class="flex items-center space-x-3 text-gray-600 hover:text-gray-900 transition-colors">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <span class="hidden md:block font-medium">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <!-- User Dropdown Menu -->
                            <div id="user-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 hidden z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Çıkış Yap
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gradient-to-br from-slate-50 to-blue-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const closeButton = mobileSidebar.querySelector('button i.fa-times').parentElement;
            const menuLinks = mobileSidebar.querySelectorAll('nav a');
            const logoutButton = mobileSidebar.querySelector('button[type="submit"]');

            // User dropdown menu
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');

            // User dropdown toggle
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                });
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function() {
                if (userDropdown) userDropdown.classList.add('hidden');
            });

            // Mobile menu toggle
            if (mobileMenuButton && mobileSidebar) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileSidebar.style.display = 'block';
                });
            }

            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    mobileSidebar.style.display = 'none';
                });
            }

            // Close mobile menu when clicking on links
            if (menuLinks) {
                menuLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileSidebar.style.display = 'none';
                    });
                });
            }

            if (logoutButton) {
                logoutButton.addEventListener('click', function() {
                    mobileSidebar.style.display = 'none';
                });
            }

            // Close mobile menu when clicking outside
            if (mobileSidebar) {
                mobileSidebar.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.style.display = 'none';
                    }
                });
            }

        });

        // Toggle dropdown function
        function toggleDropdown(menuId) {
            const menu = document.getElementById(menuId + '-menu');
            const arrow = document.getElementById(menuId + '-arrow');

            if (menu && arrow) {
                menu.classList.toggle('open');
                arrow.classList.toggle('rotate');
            }
        }

        // Toggle mobile dropdown function
        function toggleMobileDropdown(menuId) {
            const menu = document.getElementById(menuId + '-menu');
            const arrow = document.getElementById(menuId + '-arrow');

            if (menu && arrow) {
                menu.classList.toggle('open');
                arrow.classList.toggle('rotate');
            }
        }

        // Toggle education dropdown function (desktop)
        function toggleEducationDropdown() {
            const dropdown = document.getElementById('education-dropdown');
            const arrow = document.getElementById('education-arrow');

            if (dropdown && arrow) {
                dropdown.classList.toggle('open');
                arrow.classList.toggle('rotate');
            }
        }

        // Toggle mobile dropdown function for education menu
        function toggleDropdownMobile(menuId) {
            const dropdown = document.getElementById(menuId + '-dropdown-mobile');
            const arrow = document.getElementById(menuId + '-arrow-mobile');

            if (dropdown && arrow) {
                dropdown.classList.toggle('open');
                arrow.classList.toggle('rotate');
            }
        }

        // Toggle mobile sidebar function
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            if (sidebar) {
                sidebar.style.display = sidebar.style.display === 'none' ? 'block' : 'none';
            }
        }
        </script>
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
