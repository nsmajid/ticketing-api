<?php

namespace App\Attachment\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(
        Request $request
    ): array {

        return [

            'id' => $this->id,

            'ulid' => $this->ulid,

            'original_filename' => $this->original_filename,

            'extension' => $this->extension,

            'mime_type' => $this->mime_type,

            'size' => $this->size,

            'preview_url' => route(
                'attachments.preview',
                $this->ulid
            ),

            'download_url' => route(
                'attachments.download',
                $this->ulid
            ),

            'markdown' => sprintf(
                '![%s](attachment://%s)',
                $this->original_filename,
                $this->ulid
            ),

            'created_at' => $this->created_at?->format(
                'Y-m-d H:i:s'
            ),

        ];
    }
}
