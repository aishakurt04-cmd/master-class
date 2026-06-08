<?php

namespace App\Models;


use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role', 'photo', 'bio'
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
