<?php

namespace App\TicketCategory\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route(
            'ticket_category'
        )->id;

        return [

            'name' => [
                'required',
                Rule::unique(
                    'ticket_categories',
                    'name'
                )->ignore($id),
            ],

            'description' => [
                'nullable'
            ],

            'is_active' => [
                'required',
                'boolean'
            ],

            'sort_order' => [
                'nullable',
                'integer'
            ],
        ];
    }
}