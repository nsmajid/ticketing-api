<?php

namespace App\Application\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'url' => $this->url,
            'created_at' => optional($this->created_at)
                ->toDateTimeString(),
            'updated_at' => optional($this->updated_at)
                ->toDateTimeString(),

        ];
    }
}
