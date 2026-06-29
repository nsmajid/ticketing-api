<?php

namespace App\Application\Requests;

use App\Application\Models\Application;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        /** @var Application $application */
        $application = $this->route('application');

        return [

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'code' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('applications', 'code')
                    ->ignore($application),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'url' => [
                'nullable',
                'url',
                'max:255',
            ],

        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            'name.required' => 'Application name is required.',

            'code.required' => 'Application code is required.',
            'code.unique' => 'Application code already exists.',
            'code.alpha_dash' => 'Application code may only contain letters, numbers, dashes and underscores.',

            'url.url' => 'Application URL must be a valid URL.',

        ];
    }
}