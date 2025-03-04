<x-nav-dropdown {{ $attributes->merge() }}>
    <x-slot name="trigger">
        <x-heroicon-o-translate aria-hidden="true" /> <span>{{ __('Language') }}</span>
    </x-slot>

    <x-slot name="content">
        @foreach ($locales as $key => $locale )
        <li>
            <x-nav-link rel="alternate" hreflang="{{ $key }}" :href="trans_current_route($key, route($key . '.welcome'))" :active="request()->routeIs($key . '.*')">
                {{ $locale }}
            </x-nav-link>
        </li>
        @endforeach
    </x-slot>
</x-nav-dropdown>
