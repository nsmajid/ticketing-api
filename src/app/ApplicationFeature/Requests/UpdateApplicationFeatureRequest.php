<?php

namespace App\ApplicationFeature\Requests;

use App\Models\ApplicationFeature;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationFeatureRequest extends FormRequest
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
        /** @var ApplicationFeature $applicationFeature */
        $applicationFeature = $this->route('application_feature');

        return [

            'application_id' => [
                'required',
                'integer',
                Rule::exists('applications', 'id'),
            ],

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

                Rule::unique('application_features')
                    ->where(fn ($query) =>

                        $query->where(
                            'application_id',
                            $this->application_id
                        )

                    )
                    ->ignore($applicationFeature),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:1',
            ],

        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            'application_id.required' => 'Application is required.',
            'application_id.exists' => 'Selected application does not exist.',

            'name.required' => 'Feature name is required.',

            'code.required' => 'Feature code is required.',
            'code.alpha_dash' => 'Feature code may only contain letters, numbers, dashes and underscores.',
            'code.unique' => 'Feature code already exists for this application.',

        ];
    }
}