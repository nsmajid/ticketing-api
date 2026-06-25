<?php

namespace App\Sla\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SlaRuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [

            'id' => $this->id,

            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],

            'priority' => [
                'id' => $this->priority->id,
                'name' => $this->priority->name,
                'color' => $this->priority->color,
            ],

            'response_hours' => $this->response_hours,

            'resolution_hours' => $this->resolution_hours,

            'is_active' => $this->is_active,

            'created_at' => $this->created_at?->toISOString(),

            'updated_at' => $this->updated_at?->toISOString(),

        ];
    }
}
