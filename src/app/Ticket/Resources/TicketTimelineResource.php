<?php

namespace App\Ticket\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class TicketTimelineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(
        Request $request
    ): array {

        return [

            'time' => $this['time']
                ? Carbon::parse($this['time'])->format('Y-m-d H:i:s')
                : null,
            'action' => $this['action'],

            'title' => $this['title'],

            'description' => $this['description'],

            'user' => [

                'id' => $this['user']?->id,

                'name' => $this['user']?->name,

            ],

            // 'status' => $this['status'],

            'metadata' => $this['metadata'],

        ];
    }
}
