<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplicationFeature extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [

        'application_id',

        'name',

        'code',

        'description',

        'sort_order',

    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [

        'application_id' => 'integer',

        'sort_order' => 'integer',

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the application that owns the feature.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(
            Application::class
        );
    }

    /**
     * Get tickets using this feature.
     *
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(
            Ticket::class
        );
    }
}
