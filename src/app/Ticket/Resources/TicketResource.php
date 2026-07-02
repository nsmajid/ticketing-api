<?php

namespace App\Ticket\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Ticket
            |--------------------------------------------------------------------------
            */

            'id' => $this->id,

            'ticket_number' => $this->ticket_number,

            /*
            |--------------------------------------------------------------------------
            | Requester
            |--------------------------------------------------------------------------
            */

            'requester_id' => $this->requester_id,

            'requester_name' => $this->whenLoaded(
                'requester',
                fn() => $this->requester->name
            ),

            /*
            |--------------------------------------------------------------------------
            | Application
            |--------------------------------------------------------------------------
            */

            'application_id' => $this->whenLoaded(
                'applicationFeature.application',
                fn() => $this->applicationFeature->application->id
            ),

            'application_name' => $this->whenLoaded(
                'applicationFeature.application',
                fn() => $this->applicationFeature->application->name
            ),

            /*
            |--------------------------------------------------------------------------
            | Feature
            |--------------------------------------------------------------------------
            */

            'application_feature_id' => $this->application_feature_id,

            'application_feature_name' => $this->whenLoaded(
                'applicationFeature',
                fn() => $this->applicationFeature->name
            ),

            /*
            |--------------------------------------------------------------------------
            | Category
            |--------------------------------------------------------------------------
            */

            'ticket_category_id' => $this->ticket_category_id,

            'ticket_category_name' => $this->whenLoaded(
                'category',
                fn() => $this->category->name
            ),

            /*
            |--------------------------------------------------------------------------
            | Priority
            |--------------------------------------------------------------------------
            */

            'ticket_priority_id' => $this->ticket_priority_id,

            'ticket_priority_name' => $this->whenLoaded(
                'priority',
                fn() => $this->priority->name
            ),

            'ticket_priority_color' => $this->whenLoaded(
                'priority',
                fn() => $this->priority->color
            ),

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'ticket_status_id' => $this->ticket_status_id,

            'ticket_status_name' => $this->whenLoaded(
                'status',
                fn() => $this->status->name
            ),

            'ticket_status_code' => $this->whenLoaded(
                'status',
                fn() => $this->status->code
            ),

            'ticket_status_color' => $this->whenLoaded(
                'status',
                fn() => $this->status->color
            ),


            /*
|--------------------------------------------------------------------------
| Review
|--------------------------------------------------------------------------
*/

            'reviewed_by' => $this->reviewed_by,

            'reviewer_name' => $this->whenLoaded(
                'reviewer',
                fn() => $this->reviewer->name
            ),

            'reviewed_at' => $this->reviewed_at?->toDateTimeString(),

            'review_notes' => $this->review_notes,


            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */

            'contact_name' => $this->contact_name,

            'contact_phone' => $this->contact_phone,

            /*
            |--------------------------------------------------------------------------
            | Ticket Detail
            |--------------------------------------------------------------------------
            */

            'subject' => $this->subject,

            'description' => $this->description,

            /*
            |--------------------------------------------------------------------------
            | SLA
            |--------------------------------------------------------------------------
            */

            'submitted_at' => $this->submitted_at?->toDateTimeString(),

            'response_due_at' => $this->response_due_at?->toDateTimeString(),

            'resolution_due_at' => $this->resolution_due_at?->toDateTimeString(),

            'resolved_at' => $this->resolved_at?->toDateTimeString(),

            'closed_at' => $this->closed_at?->toDateTimeString(),

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            'created_at' => $this->created_at?->toDateTimeString(),

            'updated_at' => $this->updated_at?->toDateTimeString(),

        ];
    }
}
