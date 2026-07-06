<?php

namespace App\TicketComment\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules.
     */
    public function rules(): array
    {
        return [

            'content' => [

                'required',

                'string',

            ],

        ];
    }

    /**
     * Validation Messages.
     */
    public function messages(): array
    {
        return [

            'content.required' => 'Comment is required.',

            'content.string' => 'Comment must be a string.',

        ];
    }

    /**
     * Validation Attributes.
     */
    public function attributes(): array
    {
        return [

            'content' => 'comment',

        ];
    }
}