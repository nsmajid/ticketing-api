<?php

namespace App\TicketPriority\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketPriorityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ticket_priorities', 'name')
                    ->ignore($this->route('ticket_priority'))
            ],

            'code' => [
                'required',
                'alpha_dash',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'response_hours' => [
                'required',
                'integer',
                'min:1',
            ],

            'resolution_hours' => [
                'required',
                'integer',
                'min:1',
            ],

            'color' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'boolean',
            ],

            'sort_order' => [
                'integer',
            ],
        ];
    }
}
