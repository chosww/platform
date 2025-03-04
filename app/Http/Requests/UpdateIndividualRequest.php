<?php

namespace App\Http\Requests;

use App\Enums\ConsultingService;
use App\Enums\ProvinceOrTerritory;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateIndividualRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->individual);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'locality' => 'nullable|string|max:255',
            'region' => [
                'required',
                new Enum(ProvinceOrTerritory::class),
            ],
            'pronouns' => 'nullable|array:'.implode(',', $this->individual->languages),
            'bio' => 'required|array:'.implode(',', $this->individual->languages).'|required_array_keys:'.$this->individual->user->locale,
            'working_languages' => 'nullable|array',
            'consulting_services' => [
                'nullable',
                'array',
                Rule::requiredIf(fn () => $this->individual->isConsultant()),
                Rule::excludeIf(fn () => ! $this->individual->isConsultant()),
            ],
            'consulting_services.*' => [
                new Enum(ConsultingService::class),

            ],
            'social_links.*' => 'nullable|url',
            'website_link' => 'nullable|url',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'social_links' => array_map('normalize_url', $this->social_links ?? []),
            'website_link' => normalize_url($this->website_link),
        ]);
    }
}
