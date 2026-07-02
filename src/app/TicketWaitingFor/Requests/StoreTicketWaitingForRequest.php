<?php

namespace App\TicketWaitingFor\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\TicketWaitingFor;

class StoreTicketWaitingForRequest extends FormRequest
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

            'name' => [

                'required',

                'string',

                'max:255',

            ],

            'code' => [

                'required',

                'string',

                'max:100',
                
                'regex:/^[A-Z0-9_]+$/',

                Rule::unique(
                    TicketWaitingFor::class,
                    'code'
                ),

            ],

            'description' => [

                'nullable',

                'string',

            ],

            'is_active' => [

                'required',

                'boolean',

            ],

            'sort_order' => [

                'nullable',

                'integer',

                'min:0',

            ],

        ];
    }
}
