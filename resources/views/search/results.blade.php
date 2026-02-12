<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site İçi Arama - {{ $orgName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="min-h-screen bg-slate-50">
@include('partials.header-menu-wrapper')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-slate-800">
                <i class="fas fa-search mr-2 text-teal-600"></i>
                Site İçi Arama
            </h1>
            <div class="relative flex items-center">
                <input type="text" id="search-input-results" value="{{ $q }}" placeholder="{{ __('common.search_placeholder') }}"
                       class="w-64 pl-10 pr-12 py-2 rounded-lg bg-slate-100 placeholder-slate-400 text-slate-800 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-teal-300 transition" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <i class="fas fa-search"></i>
                </span>
                <div id="search-loading-results" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hidden">
                    <i class="fas fa-spinner fa-spin text-xs"></i>
                </div>
            </div>
        </div>

        @if($q === '')
            <p class="text-slate-600">Aramak istediğiniz kelimeyi yukarıya yazın.</p>
        @else
            <p class="text-slate-600 mb-6">Aranan ifade: <span class="font-semibold">"{{ $q }}"</span></p>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Duyurular -->
                <div class="col-span-1 lg:col-span-1">
                    <h2 class="text-lg font-semibold text-slate-800 mb-3">Duyurular</h2>
                    <div class="space-y-3">
                        @forelse($announcementResults as $a)
                            <a href="{{ route('announcements.detail', $a->id) }}" class="block p-4 rounded-lg border border-slate-200 hover:border-teal-400 hover:bg-teal-50 transition">
                                <div class="font-medium text-slate-900">{{ $a->title }}</div>
                                <div class="text-sm text-slate-600 mt-1">{{ Str::limit(strip_tags($a->content), 120) }}</div>
                            </a>
                        @empty
                            <div class="text-slate-500 text-sm">Sonuç bulunamadı.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Haberler -->
                <div class="col-span-1 lg:col-span-1">
                    <h2 class="text-lg font-semibold text-slate-800 mb-3">Haberler</h2>
                    <div class="space-y-3">
                        @forelse($newsResults as $n)
                            <a href="{{ route('news.detail', $n->id) }}" class="block p-4 rounded-lg border border-slate-200 hover:border-teal-400 hover:bg-teal-50 transition">
                                <div class="font-medium text-slate-900">{{ $n->title }}</div>
                                <div class="text-sm text-slate-600 mt-1">{{ Str::limit(strip_tags($n->content), 120) }}</div>
                            </a>
                        @empty
                            <div class="text-slate-500 text-sm">Sonuç bulunamadı.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Sayfalar -->
                <div class="col-span-1 lg:col-span-1">
                    <h2 class="text-lg font-semibold text-slate-800 mb-3">Sayfalar</h2>
                    <div class="space-y-3">
                        @forelse($pageResults as $p)
                            <a href="{{ route('page.show', $p->slug) }}" class="block p-4 rounded-lg border border-slate-200 hover:border-teal-400 hover:bg-teal-50 transition">
                                <div class="font-medium text-slate-900">{{ $p->title }}</div>
                                <div class="text-sm text-slate-600 mt-1">{{ Str::limit(strip_tags($p->content), 120) }}</div>
                            </a>
                        @empty
                            <div class="text-slate-500 text-sm">Sonuç bulunamadı.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@include('partials.footer')

<script>
    // Auto-search with debounce for results page
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input-results');
        const searchLoading = document.getElementById('search-loading-results');
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();

                // Clear previous timeout
                clearTimeout(searchTimeout);

                if (query.length >= 2) {
                    // Show loading spinner
                    searchLoading.classList.remove('hidden');

                    // Set new timeout for search
                    searchTimeout = setTimeout(() => {
                        window.location.href = '{{ route("search") }}?q=' + encodeURIComponent(query);
                    }, 500); // 500ms delay
                } else if (query.length === 0) {
                    // Clear search if input is empty
                    clearTimeout(searchTimeout);
                    searchLoading.classList.add('hidden');
                } else {
                    // Hide loading for short queries
                    searchLoading.classList.add('hidden');
                }
            });

            // Handle Enter key
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();
                    if (query.length > 0) {
                        window.location.href = '{{ route("search") }}?q=' + encodeURIComponent(query);
                    }
                }
            });
        }
    });
</script>

</body>
</html>
