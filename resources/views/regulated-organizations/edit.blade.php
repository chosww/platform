
<x-app-wide-layout>
    <x-slot name="title">{{ $regulatedOrganization->name }}</x-slot>
    <x-slot name="header">
        <div class="repel">
            <h1>
                {{ $regulatedOrganization->name }}
            </h1>
            @if($regulatedOrganization->checkStatus('draft'))
                <span class="badge">{{ __('Draft mode') }}</span>
            @endif
        </div>
        @if($regulatedOrganization->checkStatus('published'))
            <p>
                <a href="{{ localized_route('regulated-organizations.show', $regulatedOrganization) }}">{{ __('View page') }}</a>
            </p>
        @endif
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <x-translation-manager :model="$regulatedOrganization" />

    <form class="stack" action="{{ localized_route('regulated-organizations.update', $regulatedOrganization) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="with-sidebar with-sidebar:last">
            <div class="stack">
                @can('update', $regulatedOrganization)
                    @if($regulatedOrganization->checkStatus('draft'))
                        <p class="stack">
                            <x-hearth-input type="submit" class="secondary" name="preview" value="{{ __('Preview page') }}" />
                            <x-hearth-input type="submit" name="publish" value="{{ __('Publish page') }}" />
                        </p>
                        <p>{{ __('Once you publish your page, other users on this website can access your page.') }}</p>
                    @else
                        <p class="stack">
                            <x-hearth-input type="submit" name="unpublish" value="{{ __('Unpublish page') }}" />
                        </p>
                    @endif
                @endcan
            </div>

            <div class="stack">
                <button>{{ __('Save') }}</button>

                <h2>{{ __('Organization information') }}</h2>

                <div class="field @error('name') field--error @enderror">
                    <x-translatable-input name="name" :model="$regulatedOrganization" :label="__('Federally regulated organization name')" required />
                </div>

                <fieldset>
                    <legend>{{ __('Your headquarters location (required)') }}</legend>

                    <div class="field">
                        <x-hearth-label for="locality" :value="__('forms.label_locality')" />
                        <x-hearth-input id="locality" type="text" name="locality" :value="old('locality', $regulatedOrganization->locality)" required />
                    </div>
                    <div class="field">
                        <x-hearth-label for="region" :value="__('forms.label_region')" />
                        <x-hearth-select id="region" name="region" :selected="old('region', $regulatedOrganization->region)" required :options="$nullableRegions"/>
                    </div>
                </fieldset>

                <fieldset x-data="enhancedCheckboxes()">
                    <legend>{{ __('Where are your organization’s service areas? (required)') }}</legend>
                    <p class="stack" x-cloak>
                        <button class="secondary" type="button" x-on:click="selectAll()">{{ __('Select all') }}</button>
                        <button class="secondary" type="button" x-on:click="selectNone()">{{ __('Select none') }}</button>
                    </p>
                    <x-hearth-checkboxes name="service_areas" :options="array_filter($regions)" :checked="old('service_areas', $regulatedOrganization->service_areas ?? [])" required />
                </fieldset>

                <fieldset class="field @error('sectors') field--error @enderror">
                    <legend>{{ __('What type of regulated organization are you? (required)') }}</legend>

                    <x-hearth-checkboxes name="sectors" :options="$sectors" :checked="old('sectors', $regulatedOrganization->sectors->pluck('id')->toArray() ?? [])" />
                    <x-hearth-error for="sectors" />
                </fieldset>

                <div class="field @error('about') field--error @enderror">
                    <x-translatable-textarea name="about" :model="$regulatedOrganization" :label="__('About your organization (required)')" :hint="__('Tell us about your organization, its mission, and what you offer.')" required />
                </div>

                <fieldset class="stack">
                    <legend><h2>{{ __('Accessibility and inclusion links (optional)') }}</h2></legend>
                    <p class="field__hint">{{ __('Please include any links that describes the accessibility and inclusion initiatives your regulated entity has. This can include reports, case studies, and more.') }}</p>
                    <livewire:web-links name="accessibility_and_inclusion_links" :links="$regulatedOrganization->accessibility_and_inclusion_links ?? [['title' => '', 'url' => '']]" />
                </fieldset>


                <h2>{{ __('Social media and website links (optional)') }}</h2>

                <fieldset class="stack">
                    <legend><h3>{{ __('Social media') }}</h3></legend>
                    @foreach ([
                        'linked_in',
                        'twitter',
                        'instagram',
                        'facebook'
                    ] as $key)
                        <div class="field @error('social_links.' . $key) field--error @enderror">
                            <x-hearth-label for="social_links_{{ $key }}" :value="__(':service (optional)', ['service' => Str::studly($key)] )" />
                            <x-hearth-input id="social_links_{{ $key }}" name="social_links[{{ $key }}]" :value="old('social_links.' . $key, $regulatedOrganization->social_links[$key] ?? '')" />
                            <x-hearth-error for="social_links_{{ $key }}" />
                        </div>
                    @endforeach
                </fieldset>

                <h3>{{ __('Organization website') }}</h3>

                <div class="field">
                    <x-hearth-label for="website_link" :value="__('Website link (optional)')" />
                    <x-hearth-input type="url" name="website_link" :value="old('website_link', $regulatedOrganization->website_link)" />
                </div>

                <h3>{{ __('Contact information') }}</h3>

                <div class="field @error("contact_person_name") field-error @enderror">
                    <x-hearth-label for="contact_person_name" :value="__('Name of contact person (required)')" />
                    <x-hearth-hint for="contact_person_name">{{ __('This does not have to be their legal name.') }}</x-hearth-hint>
                    <x-hearth-input id="contact_person_name" name="contact_person_name" :value="old('contact_person_name', $regulatedOrganization->contact_person_name)" required hinted />
                    <x-hearth-error for="contact_person_name" field="contact_person_name" />
                </div>
                <div class="field @error('contact_person_email') field-error @enderror">
                    <x-hearth-label for="contact_person_email" :value="__('Contact person’s email')" />
                    <x-hearth-input type="email" name="contact_person_email" :value="old('contact_person_email', $regulatedOrganization->contact_person_email)" />
                    <x-hearth-error for="contact_person_email" />
                </div>
                <div class="field @error('contact_person_phone') field-error @enderror">
                    <x-hearth-label for="contact_person_phone" :value="__('Contact person’s phone number')" />
                    <x-hearth-input type="tel" name="contact_person_phone" :value="old('contact_person_phone', $regulatedOrganization->contact_person_phone?->formatForCountry('CA'))" />
                    <x-hearth-error for="contact_person_phone" />
                </div>

                <div class="field @error('contact_person_vrs') field-error @enderror">
                    <x-hearth-checkbox name="contact_person_vrs" :checked="old('contact_person_vrs', $regulatedOrganization->contact_person_vrs ?? false)" />
                    <x-hearth-label for="contact_person_vrs" :value="__('They require Video Relay Service (VRS) for phone calls')" />
                    <x-hearth-error for="contact_person_vrs" />
                </div>

                <div class="field @error('preferred_contact_method') field-error @enderror">
                    <x-hearth-label for="preferred_contact_method">{{ __('Preferred contact method (required)') }}</x-hearth-label>
                    <x-hearth-select name="preferred_contact_method" :options="Spatie\LaravelOptions\Options::forArray(['email' => __('Email'), 'phone' => __('Phone')])->toArray()" :selected="old('preferred_contact_method', $regulatedOrganization->preferred_contact_method ?? 'email')"/>
                    <x-hearth-error for="preferred_contact_method" />
                </div>

                <button>{{ __('Save') }}</button>
            </div>
        </div>
    </form>
</x-app-wide-layout>
