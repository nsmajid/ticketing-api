<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attachment extends Model
{
    use HasFactory;

    /**
     * Indicates if the model's ID is auto-incrementing.
     */
    public $incrementing = true;

    /**
     * The "type" of the auto-incrementing ID.
     */
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [

        'ulid',

        // 'disk',

        'directory',

        'path',

        'filename',

        'original_filename',

        'extension',

        'mime_type',

        'size',

        'checksum',

        'uploaded_by',

        'is_temporary',

        'expired_at',

    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [

        'is_temporary' => 'boolean',

        'expired_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Uploader.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    /**
     * Attachment usages.
     */
    // public function usages(): HasMany
    // {
    //     return $this->hasMany(
    //         AttachmentUsage::class
    //     );
    // }

    /**
     * Resolve attachment by ULID.
     */
    public static function findByUlidOrFail(
        string $ulid
    ): self {

        return static::query()
            ->where('ulid', $ulid)
            ->firstOrFail();
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
