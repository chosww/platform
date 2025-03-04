<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'team_size' => 'nullable|array',
            'team_size.*' => 'nullable|string',
            'team_has_disability_or_deaf_lived_experience' => 'nullable|boolean',
            'team_languages' => 'required|array|min:1',
            'team_languages.*' => [
                'nullable',
                Rule::in(array_keys(get_available_languages(true))),
            ],
            'team_trainings' => 'nullable|array',
            'team_trainings.*.name' => 'nullable|string',
            'team_trainings.*.date' => 'nullable|date|required_with:trainings.*.name',
            'team_trainings.*.trainer_name' => 'nullable|string|required_with:trainings.*.name',
            'team_trainings.*.trainer_url' => 'nullable|url|required_with:trainings.*.name',
            'contact_person_name' => 'required|string',
            'contact_person_email' => 'nullable|email|required_without:contact_person_phone',
            'contact_person_phone' => 'nullable|phone:CA|required_if:contact_person_vrs,true|required_without:contact_person_email',
            'contact_person_vrs' => 'nullable|boolean',
            'preferred_contact_method' => 'required|in:email,phone',
            'contact_person_response_time' => 'required|array',
            'contact_person_response_time.en' => 'required_without:contact_person_response_time.fr|nullable|string',
            'contact_person_response_time.fr' => 'required_without:contact_person_response_time.en|nullable|string',
        ];
    }
}
