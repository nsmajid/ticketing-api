<?php

namespace App\TicketPriority\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketPriorityRequest extends FormRequest
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
            'unique:ticket_priorities,name',
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