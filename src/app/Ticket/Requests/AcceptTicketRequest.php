<?php

namespace App\Ticket\Requests;

use App\Shared\Enums\Ticket\TicketStatusCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AcceptTicketRequest extends FormRequest
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

                Rule::in([

                    TicketStatusCode::Closed->value,

                    TicketStatusCode::Assigned->value,

                ]),

            ],

            'close_notes' => [

                'required',

                'string',

            ],

        ];
    }
}