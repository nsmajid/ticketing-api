<?php

namespace App\TicketStatus\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketStatusRequest extends FormRequest
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
               
            ],

            'code' => [
                'required',
                'alpha_dash',
                'max:100',
                 Rule::unique('ticket_statuses', 'code')
                    ->ignore($this->route('ticket_status'))
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'color' => [
                'nullable',
                'string',
            ],

            'icon' => [
                'nullable',
                'string',
            ],

            'is_initial' => [
                'boolean',
            ],

            'is_closed' => [
                'boolean',
            ],

            'is_resolved' => [
                'boolean',
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
