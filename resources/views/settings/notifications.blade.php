<x-app-wide-tabbed-layout>
    <x-slot name="title">{{ __('Notifications') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1 id="notifications">
            {{ __('Notifications') }}
        </h1>
    </x-slot>

    @if($user->context === 'individual')
    <nav aria-labelledby="notifications" class="full bg-white shadow-md">
        <div class="center center:wide">
            <ul role="list" class="flex gap-6 -mt-4">
                <li class="w-1/2">
                    <x-nav-link class="inline-flex items-center justify-center w-full border-t-0" :href="localized_route('settings.edit-notification-preferences')" :active="request()->localizedRouteIs('settings.edit-notification-preferences')">{{ __('Manage notifications') }}</x-nav-link>
                </li>
                <li class="w-1/2">
                    <x-nav-link class="inline-flex items-center justify-center w-full border-t-0" :href="localized_route('notification-list.show')" :active="request()->localizedRouteIs('notification-list.show')">{{ __('Notification list') }}</x-nav-link>
                </li>
            </ul>
        </div>
    </nav>
    @endif

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <h2>{{ __('Manage my notifications') }}</h2>
    <p>{{ __('The Accessibility Exchange will occasionally send you notifications, based on what you chose to be notified of here.') }}</p>

    @include('settings.notifications.'.$user->context)
</x-app-wide-tabbed-layout>
