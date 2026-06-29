<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
     protected $fillable = [
        'name',
        'code',
        'description',
        'url',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

//     public function features(): HasMany
//     {
//         // return $this->hasMany(ApplicationFeature::class);
//     }

//     public function tickets(): HasMany
//     {
//         return $this->hasMany(Ticket::class);
//     }
}
