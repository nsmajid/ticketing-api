<?php

namespace App\TicketCategory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketCategoryRequest extends FormRequest
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
                'max:255',
                'unique:ticket_categories,name'
            ],

            'description' => [
                'nullable'
            ],

            'is_active' => [
                'boolean'
            ],

            'sort_order' => [
                'nullable',
                'integer'
            ],
        ];
    }
}