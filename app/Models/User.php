<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role', 'photo', 'bio',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isLeader(): bool
    {
        return $this->role === 'leader';
    }

    public function masterClasses(): HasMany
    {
        return $this->hasMany(MasterClass::class, 'leader_id');
    }

    public function registeredMasterClasses(): BelongsToMany
    {
        return $this->belongsToMany(MasterClass::class, 'registrations')
            ->withPivot('status', 'created_at')
            ->withTimestamps();
    }
}
