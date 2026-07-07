<?php

namespace App\Models;

use App\Shared\Enums\Attachment\AttachmentOwnerType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttachmentUsage extends Model
{
     /**
     * Mass Assignment.
     */
    protected $fillable = [

        'attachment_id',

        'owner_type',

        'owner_id',

    ];

    /**
     * Cast.
     */
    protected function casts(): array
    {
        return [

            'owner_type' => AttachmentOwnerType::class,

        ];
    }

    /**
     * Attachment.
     */
    public function attachment(): BelongsTo
    {
        return $this->belongsTo(
            Attachment::class
        );
    }
}
