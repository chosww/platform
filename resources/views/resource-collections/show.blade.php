<x-app-layout>
    <x-slot name="title">{{ $resourceCollection->title }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ $resourceCollection->title }}
        </h1>
    </x-slot>

    @markdown
    {{ $resourceCollection->description }}
    @endmarkdown

    <div class="stack">
        <h2>{{ __('Search') }}</h2>
        <form class="search" action="" method="post">
            @csrf
            <label for="search" class="visually-hidden">{{ __('Search') }}</label>
            <input id="search" type="search" />
            <button type="submit">{{ __('Search') }}</button>
        </form>
    </div>
    <div class="resources">
        <div class="filters stack">
            <h2 class="visually-hidden">{{ __('Filters') }}</h2>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Topic') }}</x-slot>
                <ul role="list">
                    @foreach($topics as $topic)
                    <li>
                        <x-hearth-input type="checkbox" id="topics-{{ $topic->id }}" name="topics[]" value="{{ $topic->id }}" />
                        <label for="topics-{{ $topic->id }}">{{ $topic->name }}</label>
                    </li>
                    @endforeach
                </ul>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Content type') }}</x-slot>
                <ul role="list">
                    @foreach($types as $type)
                    <li>
                        <x-hearth-input type="checkbox" id="type-{{ $type->id }}" name="type[]" value="{{ $type->id }}" />
                        <label for="type-{{ $type->id }}">{{ $type->name }}</label>
                    </li>
                    @endforeach
                </ul>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Format') }}</x-slot>
                <ul role="list">
                    @foreach($formats as $format)
                    <li>
                        <x-hearth-input type="checkbox" id="format-{{ $format->id }}" name="format[]" value="{{ $format->id }}" />
                        <label for="format-{{ $format->id }}">{{ $format->name }}</label>
                    </li>
                    @endforeach
                </ul>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Language') }}</x-slot>
                <ul role="list">
                    @foreach($languages as $code)
                    <li>
                        <x-hearth-input type="checkbox" id="language-{{ $code }}" name="language[]" value="{{ $code }}" />
                        <label for="language-{{ $code }}">{{ get_language_exonym($code) }}</label>
                    </li>
                    @endforeach
                </ul>
            </x-expander>
            <x-expander :level="3">
                <x-slot name="summary">{{ __('Phase of consultation') }}</x-slot>
                <ul role="list">
                    @foreach($phases as $phase)
                    <li>
                        <x-hearth-input type="checkbox" id="phase-{{ $phase->id }}" name="phase[]" value="{{ $phase->id }}" />
                        <label for="phase-{{ $phase->id }}">{{ $phase->name }}</label>
                    </li>
                    @endforeach
                </ul>
            </x-expander>
        </div>
        <div class="stack cards">
            @forelse($resources as $resource)
            <article class="box card card--resource">
                <p><span class="visually-hidden">{{ __('Content type') }}:</span> {{ $types->random()->name }}</p>
                <p><span class="visually-hidden">{{ __('Format') }}:</span> {{ $formats->random()->name }} <span aria-hidden="true">&middot;</span> <span class="visually-hidden">{{ __('Language') }}:</span> {{ get_language_exonym(Arr::random($languages)) }}</p>
                <h2>
                    <a href="{{ localized_route('resources.show', $resource) }}">{{ $resource->title }}</a>
                </h2>
                <p>{{ Str::limit($resource->summary, 140); }} <a href="{{ localized_route('resources.show', $resource) }}">{!! __('Read more <span class="visually-hidden">of :title</span>', ['title' => $resource->title]) !!}</a></p>
            </article>
            @empty
            <p>{{ __('resource.none_found') }}</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
