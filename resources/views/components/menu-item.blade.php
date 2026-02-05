@props(['menuItem'])

<div class="relative group">
    <a href="{{ $menuItem->url ?: ($menuItem->route_name && Route::has($menuItem->route_name) ? route($menuItem->route_name) : '#') }}"
       class="flex items-center text-white hover:text-teal-200 transition-colors font-bold text-base uppercase tracking-wide py-4">
        {{ $menuItem->title }}
        @if($menuItem->has_dropdown && $menuItem->children->count() > 0)
            <i class="fas fa-chevron-down ml-2 text-sm"></i>
        @endif
    </a>

    @if($menuItem->has_dropdown && $menuItem->children->count() > 0)
        <div class="absolute top-full left-0 bg-white shadow-lg rounded-md py-2 min-w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                                                                            @foreach($menuItem->children as $subMenuItem)
                                            <a href="{{ $subMenuItem->url ?: ($subMenuItem->route_name && Route::has($subMenuItem->route_name) ? route($subMenuItem->route_name) : '#') }}"
                                               class="text-gray-800 hover:bg-teal-50 hover:text-teal-600 transition-colors text-base font-medium px-4 py-2 block">
                                                {{ $subMenuItem->title }}
                                            </a>
                                        @endforeach
        </div>
    @endif
</div>
