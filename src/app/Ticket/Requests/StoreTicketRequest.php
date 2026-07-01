<?php

namespace App\Ticket\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
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

            'application_feature_id' => [

                'required',

                'integer',

                Rule::exists(
                    'application_features',
                    'id'
                ),

            ],

            'ticket_category_id' => [

                'required',

                'integer',

                Rule::exists(
                    'ticket_categories',
                    'id'
                ),

            ],

            'ticket_priority_id' => [

                'required',

                'integer',

                Rule::exists(
                    'ticket_priorities',
                    'id'
                ),

            ],

            'contact_name' => [

                'required',

                'string',

                'max:255',

            ],

            'contact_phone' => [

                'required',

                'string',

                'max:50',

            ],

            'subject' => [

                'required',

                'string',

                'max:255',

            ],

            'description' => [

                'required',

                'string',

            ],

        ];
    }

    /**
     * Validation messages.
     */
    public function messages(): array
    {
        return [

            'application_feature_id.required'
                => 'Application feature is required.',

            'application_feature_id.exists'
                => 'Selected application feature does not exist.',

            'ticket_category_id.required'
                => 'Ticket category is required.',

            'ticket_category_id.exists'
                => 'Selected ticket category does not exist.',

            'ticket_priority_id.required'
                => 'Ticket priority is required.',

            'ticket_priority_id.exists'
                => 'Selected ticket priority does not exist.',

            'contact_name.required'
                => 'Contact name is required.',

            'contact_phone.required'
                => 'Contact phone is required.',

            'subject.required'
                => 'Subject is required.',

            'description.required'
                => 'Description is required.',

        ];
    }
}