<x-app-wide-layout>
    <x-slot name="title">{{ $organization->getWrittenTranslation('name', $language) }}</x-slot>
    <x-slot name="header">
        <div class="stack">
            <h1 id="regulated-organization">
                {{ $organization->getWrittenTranslation('name', $language) }}
            </h1>
            <p class="meta">
                <strong>{{ Str::ucfirst(__('organization.types.' . $organization->type . '.name')) }}</strong><br />
                @foreach($organization->roles as $role) {{ $role }}@if(!$loop->last), @endif @endforeach<br />
                {{ $organization->locality }}, {{ $organization->region }}
            </p>
            <div class="repel">
                <ul role="list" class="cluster">
                    @if($organization->social_links && count($organization->social_links) > 0 || $organization->website_link)
                        @if($organization->website_link)
                            <li>
                                <a class="weight:semibold with-icon" href="{{ $organization->website_link }}"><x-heroicon-o-globe-alt class="icon" />{{ __('Website', [], !is_signed_language($language) ? $language : locale()) }}</a>
                            </li>
                        @endif
                        @if($organization->social_links)
                            @foreach($organization->social_links as $key => $value)
                                <li>
                                    <a class="weight:semibold with-icon" href="{{ $value }}">@svg('forkawesome-' . str_replace('_', '', $key), 'icon'){{ Str::studly($key) }}</a>
                                </li>
                            @endforeach
                        @endif
                    @endif
                </ul>

                <div class="repel">
                    @can('receiveNotifications')
                        @if(Auth::user()->isReceivingNotificationsFor($organization))
                            <form action="{{ localized_route('notification-list.remove') }}" method="post">
                                @csrf
                                <x-hearth-input type="hidden" name="notificationable_type" :value="get_class($organization)" />
                                <x-hearth-input type="hidden" name="notificationable_id" :value="$organization->id" />

                                <button class="secondary">{{ __('Remove from my notification list') }}</button>
                            </form>
                        @else
                            <form action="{{ localized_route('notification-list.add') }}" method="post">
                                @csrf
                                <x-hearth-input type="hidden" name="notificationable_type" :value="get_class($organization)" />
                                <x-hearth-input type="hidden" name="notificationable_id" :value="$organization->id" />

                                <button class="secondary">{{ __('Add to my notification list') }}</button>
                            </form>
                        @endif
                    @endcan

                    @can('block', $organization)
                        <x-block-modal :blockable="$organization" />
                    @endcan
                </div>
            </div>
        </div>
    </x-slot>

    <x-language-changer :model="$organization" />

    <div class="with-sidebar">
        <nav class="secondary" aria-labelledby="regulated-organization">
            <ul role="list">
                <li>
                    <x-nav-link :href="localized_route('organizations.show', $organization)" :active="request()->localizedRouteIs('organizations.show')">{{ __('About') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('organizations.show-constituencies', $organization)" :active="request()->localizedRouteIs('organizations.show-constituencies')">{{ __('Communities we :represent_or_serve_and_support', ['represent_or_serve_and_support' => ($organization->type === 'representative') ? __('represent') : __('serve and support')]) }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('organizations.show-interests', $organization)" :active="request()->localizedRouteIs('organizations.show-interests')">{{ __('Interests') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('organizations.show-projects', $organization)" :active="request()->localizedRouteIs('organizations.show-projects')">{{ __('Projects') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('organizations.show-contact-information', $organization)" :active="request()->localizedRouteIs('organizations.show-contact-information')">{{ __('Contact information') }}</x-nav-link>
                </li>
            </ul>
        </nav>
        <div class="stack">
            @if(request()->localizedRouteIs('organizations.show'))
                <h2 class="repel">{{ __('About') }} @can('update', $organization)<a class="cta secondary" href="{{ localized_route('organizations.edit', $organization) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('About') . '</span>']) !!}</a>@endcan</h2>
                @include('organizations.partials.about')
            @elseif(request()->localizedRouteIs('organizations.show-constituencies'))
                <h2 class="repel">{{ __('Communities we :represent_or_serve_and_support', ['represent_or_serve_and_support' => ($organization->type === 'representative') ? __('represent') : __('serve and support')]) }} @can('update', $organization)<a class="cta secondary" href="{{ localized_route('organizations.edit', ['organization' => $organization, 'step' => 2]) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Communities we :represent_or_serve_and_support', ['represent_or_serve_and_support' => ($organization->type === 'representative') ? __('represent') : __('serve and support')]) . '</span>']) !!}</a>@endcan</h2>
                @include('organizations.partials.constituencies')
            @elseif(request()->localizedRouteIs('organizations.show-interests'))
                <h2 class="repel">{{ __('Interests') }} @can('update', $organization)<a class="cta secondary" href="{{ localized_route('organizations.edit', ['organization' => $organization, 'step' => 3]) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Interests') . '</span>']) !!}</a>@endcan</h2>
                @include('organizations.partials.interests')
            @elseif(request()->localizedRouteIs('organizations.show-projects'))
                <h2 class="repel">{{ __('Projects') }} @can('update', $organization)<a class="cta secondary" href="{{ $organization->projects->count() > 0 ? localized_route('projects.show-context-selection') : localized_route('projects.show-language-selection') }}">{{ __('Create a project') }}</a>@endcan</h2>
                @include('organizations.partials.projects')
            @elseif(request()->localizedRouteIs('organizations.show-contact-information'))
                <h2 class="repel">{{ __('Contact information') }} @can('update', $organization)<a class="cta secondary" href="{{ localized_route('organizations.edit', ['organization' => $organization, 'step' => 4]) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Contact information') . '</span>']) !!}</a>@endcan</h2>
                @include('organizations.partials.contact-information')
            @endif
        </div>
    </div>
</x-app-wide-layout>
