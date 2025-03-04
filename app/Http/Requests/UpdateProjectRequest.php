<?php

namespace App\Http\Requests;

use App\Enums\ProvinceOrTerritory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->project);
    }

    public function rules(): array
    {
        return [
            'name.*' => 'nullable|string|max:255|unique_translation:projects',
            'name.en' => 'required_without:name.fr|nullable|string|max:255',
            'name.fr' => 'required_without:name.en|nullable|string|max:255',
            'goals.*' => 'string|nullable',
            'goals.en' => 'required_without:goals.fr|nullable|string',
            'goals.fr' => 'required_without:goals.en|nullable|string',
            'scope.*' => 'string|nullable',
            'scope.en' => 'required_without:scope.fr|nullable|string',
            'scope.fr' => 'required_without:scope.en|nullable|string',
            'regions' => 'required|array',
            'regions.*' => [
                'nullable',
                new Enum(ProvinceOrTerritory::class),
            ],
            'impacts' => 'required|array',
            'impacts.*' => 'nullable|exists:impacts,id',
            'out_of_scope' => 'nullable|array',
            'out_of_scope.*' => 'nullable|string',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'outcome_analysis' => 'nullable|array',
            'outcome_analysis.*' => 'string|in:internal,external',
            'outcome_analysis_other' => 'nullable|array',
            'outcome_analysis_other.*' => 'nullable|string',
            'outcomes' => 'nullable|array',
            'outcomes.*' => 'nullable|string',
            'public_outcomes' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.*.unique_translation' => __('A project with this name already exists.'),
            'name.*.required_without' => __('A project name must be provided in at least one language.'),
            'goals.*.required_without' => __('Project goals must be provided in at least one language.'),
            'scope.*.required_without' => __('Project scope must be provided in at least one language.'),
        ];
    }

    public function prepareForValidation()
    {
        request()->mergeIfMissing([
            'impacts' => [],
            'regions' => [],
        ]);
    }
}
