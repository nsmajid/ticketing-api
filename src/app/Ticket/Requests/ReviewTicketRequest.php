<?php

namespace App\Ticket\Requests;

use App\Shared\Enums\Ticket\TicketStatusCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewTicketRequest extends FormRequest
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

            'result' => [

                'required',

                'string',

                Rule::in([

                    TicketStatusCode::Reviewed->value,

                    TicketStatusCode::Rejected->value,

                ]),

            ],

            'review_notes' => [

                'required',

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

            'result.required'
                => 'Review result is required.',

            'result.in'
                => 'Review result must be reviewed or rejected.',

            'review_notes.required'
                => 'Review notes are required.',

        ];
    }
}