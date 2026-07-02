<?php

namespace App\TicketProgress\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartProgressRequest extends FormRequest
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

            'progress_notes' => [

                'nullable',

                'string',

            ],

        ];
    }
}