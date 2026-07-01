<?php

namespace App\ApplicationFeature\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationFeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            'id' => $this->id,

            /*
            |--------------------------------------------------------------------------
            | Relationship
            |--------------------------------------------------------------------------
            */

            'application_id' => $this->application_id,

            'application_name' => $this->whenLoaded(
                'application',
                fn () => $this->application->name
            ),

            /*
            |--------------------------------------------------------------------------
            | Feature Information
            |--------------------------------------------------------------------------
            */

            'name' => $this->name,

            'code' => $this->code,

            'description' => $this->description,

            'sort_order' => $this->sort_order,

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