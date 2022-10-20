@props([
    'modelName' => 'name',
])

@if (count($model->languages) > 1)
    <div class="flex justify-between">
        <div class="flex">
            <label class="whitespace-nowrap" for='available_languages'>{{ __('Page also available in:') }}</label>
            <ul class="flex flex-wrap" id='available_languages' role="list">
                @foreach ($model->languages as $code)
                    @if (in_array($code, config('locales.supported')))
                        {{-- Make sure at least the model name is translated to avoid 404 errors. --}}
                        @if (!$model->isTranslatableAttribute($modelName) || !empty($model->getTranslation($modelName, $code, false)))
                            <li class="ml-2"><a
                                    href="{{ localized_route($model->getRoutePrefix() . '.show', $model, $code) }}">{{ get_language_exonym($code) }}</a>
                            </li>
                        @endif
                    @else
                        <li><a
                                href="{{ localized_route($model->getRoutePrefix() . '.show', [Str::camel(class_basename($model)) => $model, 'language' => $code], get_written_language_for_signed_language($code)) }}">{{ get_language_exonym($code) }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        <div>
            <p class="whitespace-nowrap">
                <a href="#TODO">{{ __('Contact support') }}</a>
                {{ __(' if you need this in another language.') }}
            </p>
        </div>
    </div>
@endif
