<?php
namespace App\TicketPriority\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketPriorityResource extends JsonResource
{
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'name' => $this->name,
            
            'code' => $this->code,

            'description' => $this->description,

            'response_hours' => $this->response_hours,

            'resolution_hours' => $this->resolution_hours,

            'color' => $this->color,

            'is_active' => $this->is_active,

            'sort_order' => $this->sort_order,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
