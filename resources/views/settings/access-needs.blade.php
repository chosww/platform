<x-app-layout>
    <x-slot name="title">{{ __('Access needs') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Access needs') }}
        </h1>
    </x-slot>

    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('settings.update-access-needs') }}" novalidate method="post">
        @csrf
        @method('put')

        <fieldset class="field @error('general_access_needs') field--error @enderror" x-data="{other: {{ old('other', !is_null($individual->other_access_need) && $individual->other_access_need !== '') ? 'true' : 'false' }}}">
            <legend><h2>{{ __('General access needs') }}</h2></legend>

            <x-hearth-checkboxes name="general_access_needs" :options="$generalAccessSupports" :checked="old('general_access_needs', $selectedAccessSupports)" />
            <div class="field @error('general_access_needs') field--error @enderror">
                <x-hearth-checkbox name="other" :checked="old('other', !is_null($individual->other_access_need) && $individual->other_access_need !== '' || 1)" x-model="other" /> <x-hearth-label for='other'>{{ __('Other (please describe)') }}</x-hearth-label>
            </div>
            <div class="field__subfield @error('other_access_need') field--error @enderror stack" x-show="other" x-cloak>
                <x-hearth-label for="other_access_need">{{ __('Access need') }}</x-hearth-label>
                <x-hearth-textarea name="other_access_need" :value="old('other_access_need', $individual->other_access_need)" :aria-invalid="$errors->has('general_access_needs')" />
            </div>
        </fieldset>

        <fieldset class="field @error('meeting_access_needs') field--error @enderror" x-data="{
            interpretationSigned: {{ in_array($signLanguageInterpretation, old('meeting_access_needs', $selectedAccessSupports ?? [])) ? 'true' : 'false' }},
            interpretationSpoken: {{ in_array($spokenLanguageInterpretation, old('meeting_access_needs', $selectedAccessSupports ?? [])) ? 'true' : 'false' }}
        }">
            <legend><h2>{{ __('For meetings') }}</h2></legend>
            @foreach($meetingAccessSupports as $option)
                <div class="field">
                    @if($option['value'] === $signLanguageInterpretation)
                        <x-hearth-checkbox name="meeting_access_needs[]" id="meeting_access_needs-{{ $option['value'] }}" value="{{ $option['value'] }}" :checked="in_array($option['value'], old('meeting_access_needs', $selectedAccessSupports ?? []))" x-model="interpretationSigned" /> <x-hearth-label for="meeting_access_needs-{{ $option['value'] }}">{{ $option['label'] }}</x-hearth-label>
                        <div class="field__subfield @error('signed_language_for_interpretation') field--error @enderror stack" x-show="interpretationSigned" x-cloak>
                            <x-hearth-label for="signed_language_for_interpretation">{{ __('Signed language for interpretation') }}</x-hearth-label>
                            <x-hearth-select name="signed_language_for_interpretation" :options="$signedLanguages" :selected="old('signed_language_for_interpretation', $individual->signed_language_for_interpretation ?? $guessedSignedLanguage)" hinted />
                            <x-hearth-error for="signed_language_for_interpretation" />
                        </div>
                    @elseif($option['value'] === $spokenLanguageInterpretation)
                        <x-hearth-checkbox name="meeting_access_needs[]" id="meeting_access_needs-{{ $option['value'] }}" value="{{ $option['value'] }}" :checked="in_array($option['value'], old('meeting_access_needs', $selectedAccessSupports ?? []))" x-model="interpretationSpoken" /> <x-hearth-label for="meeting_access_needs-{{ $option['value'] }}">{{ $option['label'] }}</x-hearth-label>
                        <div class="field__subfield @error('spoken_language_for_interpretation') field--error @enderror stack" x-show="interpretationSpoken" x-cloak>
                            <x-hearth-label for="spoken_language_for_interpretation">{{ __('Spoken language for interpretation') }}</x-hearth-label>
                            <x-hearth-select name="spoken_language_for_interpretation" :options="$spokenOrWrittenLanguages" :selected="old('spoken_language_for_interpretation', $individual->spoken_language_for_interpretation ?? $guessedSpokenOrWrittenLanguage)" hinted />
                            <x-hearth-error for="spoken_language_for_interpretation" />
                        </div>
                    @else
                    <x-hearth-checkbox name="meeting_access_needs[]" id="meeting_access_needs-{{ $option['value'] }}" value="{{ $option['value'] }}" :checked="in_array($option['value'], old('meeting_access_needs', $selectedAccessSupports ?? []))" /> <x-hearth-label for="meeting_access_needs-{{ $option['value'] }}">{{ $option['label'] }}</x-hearth-label>
                    @endif
                    @if(isset($option['hint']) && !empty($option['hint']))
                        <x-hearth-hint for="meeting_access_needs-{{ $option['value'] }}">{{ $option['hint'] }}</x-hearth-hint>
                    @endif
                </div>
            @endforeach
        </fieldset>

        <fieldset class="field @error('in_person_access_needs') field--error @enderror">
            <legend><h2>{{ __('For in-person meetings') }}</h2></legend>
            @foreach($inPersonAccessSupports as $option)
                <div class="field">
                    <x-hearth-checkbox name="in_person_access_needs[]" id="in_person_access_needs-{{ $option['value'] }}" value="{{ $option['value'] }}" :checked="in_array($option['value'], old('in_person_access_needs', $selectedAccessSupports ?? []))" /> <x-hearth-label for="in_person_access_needs-{{ $option['value'] }}">{{ $option['label'] }}</x-hearth-label>
                    @if(isset($option['hint']) && !empty($option['hint']))
                        <x-hearth-hint for="in_person_access_needs-{{ $option['value'] }}">{{ $option['hint'] }}</x-hearth-hint>
                    @endif
                </div>
            @endforeach
        </fieldset>

        <fieldset class="field @error('document_access_needs') field--error @enderror" x-data="{
            translationSigned: {{ in_array($signLanguageTranslation, old('document_access_needs', $selectedAccessSupports)) ? 'true' : 'false' }},
            translationWritten: {{ in_array($writtenLanguageTranslation, old('document_access_needs', $selectedAccessSupports)) ? 'true' : 'false' }},
            printedVersion: {{ in_array($printedVersion, old('document_access_needs', $selectedAccessSupports)) ? 'true' : 'false' }}
        }">
            <legend><h2>{{ __('For engagement documents') }}</h2></legend>
            @foreach($documentAccessSupports as $option)
                <div class="field">
                    @if($option['value'] === $signLanguageTranslation)
                        <x-hearth-checkbox name="document_access_needs[]" id="document_access_needs-{{ $option['value'] }}" value="{{ $option['value'] }}" :checked="in_array($option['value'], old('document_access_needs', $selectedAccessSupports ?? []))" x-model="translationSigned" /> <x-hearth-label for="document_access_needs-{{ $option['value'] }}">{{ $option['label'] }}</x-hearth-label>
                        <div class="field__subfield @error('signed_language_for_translation') field--error @enderror stack" x-show="translationSigned" x-cloak>
                            <x-hearth-label for="signed_language_for_translation">{{ __('Signed language for translation') }}</x-hearth-label>
                            <x-hearth-select name="signed_language_for_translation" :options="$signedLanguages" :selected="old('signed_language_for_translation', $individual->signed_language_for_translation ?? $guessedSignedLanguage)" hinted />
                            <x-hearth-error for="signed_language_for_translation" />
                        </div>
                    @elseif($option['value'] === $writtenLanguageTranslation)
                        <x-hearth-checkbox name="document_access_needs[]" id="document_access_needs-{{ $option['value'] }}" value="{{ $option['value'] }}" :checked="in_array($option['value'], old('document_access_needs', $selectedAccessSupports ?? []))" x-model="translationWritten" /> <x-hearth-label for="document_access_needs-{{ $option['value'] }}">{{ $option['label'] }}</x-hearth-label>
                        <div class="field__subfield @error('written_language_for_translation') field--error @enderror stack" x-show="translationWritten" x-cloak>
                            <x-hearth-label for="written_language_for_translation">{{ __('Written language for translation') }}</x-hearth-label>
                            <x-hearth-select name="written_language_for_translation" :options="$spokenOrWrittenLanguages" :selected="old('written_language_for_translation', $individual->written_language_for_translation ?? $guessedSpokenOrWrittenLanguage)" hinted />
                            <x-hearth-error for="written_language_for_translation" />
                        </div>
                    @elseif($option['value'] === $printedVersion)
                        <x-hearth-checkbox name="document_access_needs[]" id="document_access_needs-{{ $option['value'] }}" value="{{ $option['value'] }}" :checked="in_array($option['value'], old('document_access_needs', $selectedAccessSupports ?? []))" x-model="printedVersion" /> <x-hearth-label for="document_access_needs-{{ $option['value'] }}">{{ $option['label'] }}</x-hearth-label>
                        <div class="field__subfield stack" x-show="printedVersion" x-cloak>
                            <div class="field @error('street_address') field--error @enderror">
                                <x-hearth-label for="street_address">{{ __('Street address') }}</x-hearth-label>
                                <x-hearth-input name="street_address" :value="old('street_address', $individual->street_address)" required />
                                <x-hearth-error for="street_address" />
                            </div>
                            <div class="field @error('unit_apartment_suite') field--error @enderror">
                                <x-hearth-label for="unit_apartment_suite">{{ __('Unit, apartment, or suite') }}</x-hearth-label>
                                <x-hearth-input name="unit_apartment_suite" :value="old('unit_apartment_suite', $individual->unit_apartment_suite)" required />
                                <x-hearth-error for="unit_apartment_suite" />
                            </div>
                            <div class="field @error('locality') field--error @enderror">
                                <x-hearth-label for="locality" :value="__('City or town')" />
                                <x-hearth-input type="text" name="locality" value="{{ old('locality', $individual->locality) }}" required />
                                <x-hearth-error for="locality" />
                            </div>
                            <div class="field @error('region') field--error @enderror">
                                <x-hearth-label for="region" :value="__('Province or territory')" />
                                <x-hearth-select name="region" :options="$regions" :selected="old('region', $individual->region)" required />
                                <x-hearth-error for="region" />
                            </div>
                            <div class="field @error('postal_code') field--error @enderror">
                                <x-hearth-label for="postal_code" :value="__('Postal code')" />
                                <x-hearth-input type="text" name="postal_code" value="{{ old('postal_code', $individual->postal_code) }}" required />
                                <x-hearth-error for="postal_code" />
                            </div>
                        </div>
                    @else
                        <x-hearth-checkbox name="document_access_needs[]" id="document_access_needs-{{ $option['value'] }}" value="{{ $option['value'] }}" :checked="in_array($option['value'], old('document_access_needs', $selectedAccessSupports ?? []))" /> <x-hearth-label for="document_access_needs-{{ $option['value'] }}">{{ $option['label'] }}</x-hearth-label>
                    @endif
                    @if(isset($option['hint']) && !empty($option['hint']))
                        <x-hearth-hint for="document_access_needs-{{ $option['value'] }}">{{ $option['hint'] }}</x-hearth-hint>
                    @endif
                </div>
            @endforeach
        </fieldset>

        <fieldset>
            <legend><h2>{{ __('Additional needs or concerns') }}</h2></legend>
            <x-hearth-checkbox name="additional_needs_or_concerns" :value="$additionalNeedsOrConcerns->id" :checked="old('additional_needs_or_concerns') || in_array($additionalNeedsOrConcerns->id, $selectedAccessSupports ?? [])" /> <x-hearth-label for='additional_needs_or_concerns'>{{ $additionalNeedsOrConcerns->name }}</x-hearth-label>
        </fieldset>

        <button>{{ __('Save') }}</button>
    </form>

</x-app-layout>
