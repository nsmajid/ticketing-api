<?php

namespace App\TicketProgress\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketProgressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            'ticket_id' => $this->ticket_id,

            'ticket_status' => [

                'id' => $this->ticketStatus?->id,

                'name' => $this->ticketStatus?->name,

                'code' => $this->ticketStatus?->code,

            ],

            'ticket_waiting_for' => $this->when(

                $this->ticket_waiting_for_id,

                fn () => [

                    'id' => $this->ticketWaitingFor?->id,

                    'name' => $this->ticketWaitingFor?->name,

                    'code' => $this->ticketWaitingFor?->code,

                ]

            ),

            'user' => [

                'id' => $this->user?->id,

                'name' => $this->user?->name,

            ],

            /*
            |--------------------------------------------------------------------------
            | Progress
            |--------------------------------------------------------------------------
            */

            'progress_notes' => $this->progress_notes,

            'resolution_notes' => $this->resolution_notes,

            /*
            |--------------------------------------------------------------------------
            | Timestamp
            |--------------------------------------------------------------------------
            */

            'created_at' => $this->created_at?->toDateTimeString(),

            'updated_at' => $this->updated_at?->toDateTimeString(),

        ];
    }
}