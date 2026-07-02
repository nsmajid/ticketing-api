<?php

namespace App\TicketProgress\Requests;

use App\Models\TicketWaitingFor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PendingTicketRequest extends FormRequest
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

            'ticket_waiting_for_id' => [

                'required',

                Rule::exists(
                    TicketWaitingFor::class,
                    'id'
                ),

            ],

            'progress_notes' => [

                'required',

                'string',

            ],

        ];
    }
}