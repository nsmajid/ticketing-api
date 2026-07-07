<?php

namespace App\TicketComment\Resources;

use App\Attachment\Resources\AttachmentResource;
use App\User\Resources\UserSimpleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'user' => UserSimpleResource::make(
                $this->whenLoaded('user')
            ),

            'content' => $this->content,

            'attachments' => AttachmentResource::collection(

                $this->whenLoaded('attachmentUsages')
                    ->pluck('attachment')
                    ->filter()
                    ->values()

            ),

            'created_at' => $this->created_at?->format(
                'Y-m-d H:i:s'
            ),

            'updated_at' => $this->updated_at?->format(
                'Y-m-d H:i:s'
            ),

            'is_edited' =>

            $this->updated_at?->ne(
                $this->created_at
            ),

        ];
    }
}
