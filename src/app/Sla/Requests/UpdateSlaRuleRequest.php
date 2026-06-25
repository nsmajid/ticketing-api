<?php

namespace App\Sla\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSlaRuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            'ticket_category_id' => [
                'required',
                'exists:ticket_categories,id',
            ],

            'ticket_priority_id' => [
                'required',
                'exists:ticket_priorities,id',

                Rule::unique('sla_rules')
                    ->where(function ($query) {
                        return $query
                            ->where(
                                'ticket_category_id',
                                $this->ticket_category_id
                            )
                            ->where(
                                'ticket_priority_id',
                                $this->ticket_priority_id
                            );
                    })
                    ->ignore($this->route('sla_rule')),
            ],

            'response_hours' => [
                'required',
                'integer',
                'min:1',
            ],

            'resolution_hours' => [
                'required',
                'integer',
                'gte:response_hours',
            ],

            'is_active' => [
                'boolean',
            ],

        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            'ticket_category_id.required' => 'Ticket category is required.',
            'ticket_category_id.exists' => 'Selected ticket category is invalid.',

            'ticket_priority_id.required' => 'Ticket priority is required.',
            'ticket_priority_id.exists' => 'Selected ticket priority is invalid.',
            'ticket_priority_id.unique' => 'SLA Rule for this category and priority already exists.',

            'response_hours.required' => 'Response hours is required.',
            'response_hours.integer' => 'Response hours must be an integer.',
            'response_hours.min' => 'Response hours must be at least 1 hour.',

            'resolution_hours.required' => 'Resolution hours is required.',
            'resolution_hours.integer' => 'Resolution hours must be an integer.',
            'resolution_hours.gte' => 'Resolution hours must be greater than or equal to response hours.',

        ];
    }
}