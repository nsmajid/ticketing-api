<?php

namespace App\Ticket\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class AssignTicketRequest extends FormRequest
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
        return [

            'assigned_to' => [

                'required',

                'integer',

                Rule::exists(
                    User::class,
                    'id'
                ),

            ],

            'assignment_notes' => [

                'nullable',

                'string',

                'max:2000',

            ],

        ];
    }

    /**
     * Validation messages.
     */
    public function messages(): array
    {
        return [

            'assigned_to.required'
                => 'Assignee is required.',

            'assigned_to.exists'
                => 'Selected assignee is invalid.',

        ];
    }
}