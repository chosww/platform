<x-app-layout>
    <x-slot name="title">{{ __('Welcome to the Accessibility Exchange') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Welcome to') }}<br />
            {{ __('The Accessibility Exchange') }}
        </h1>

        <h2>{{ __('Create new community organization') }}</h2>
    </x-slot>

    <form class="stack" action="{{ localized_route('organizations.store-type') }}" method="post" novalidate>
        <fieldset class="field @error('type') field--error @enderror">
            <legend>{{ __('What type of organization are you?') }}</legend>
            <x-hearth-radio-buttons name="type" :options="$types" :checked="old('type', '')" />
            <x-hearth-error for="type" />
        </fieldset>

        <button>{{ __('Next') }}</button>

        @csrf
    </form>
</x-app-layout>
