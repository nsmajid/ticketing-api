<?php

namespace App\TicketStatus\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketStatusRequest extends FormRequest
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
