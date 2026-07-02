<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens;
    use HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Tickets created by this user.
     */
    public function requestedTickets(): HasMany
    {
        return $this->hasMany(
            Ticket::class,
            'requester_id'
        );
    }

    /**
     * Tickets reviewed by this user.
     */
    public function reviewedTickets(): HasMany
    {
        return $this->hasMany(
            Ticket::class,
            'reviewed_by'
        );
    }

    /**
     * Assignments received by this user.
     */
    public function assignedTickets(): HasMany
    {
        return $this->hasMany(
            TicketAssignment::class,
            'assigned_to'
        );
    }

    /**
     * Assignments created by this user.
     */
    public function createdAssignments(): HasMany
    {
        return $this->hasMany(
            TicketAssignment::class,
            'assigned_by'
        );
    }
}
