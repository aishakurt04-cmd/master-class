<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MasterClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'craft_id',
        'leader_id',
        'name',
        'description',
        'date',
        'start_time',
        'end_time',
        'max_participants',
        'current_participants',
        'price',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function getRussianDateAttribute(): string
    {
        return $this->date->translatedFormat('d').' '.mb_strtolower($this->date->translatedFormat('F'));
    }

    public function craft(): BelongsTo
    {
        return $this->belongsTo(Craft::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'registrations')
            ->withPivot('status', 'created_at')
            ->withTimestamps();
    }

    public function hasFreePlaces(): bool
    {
        return $this->current_participants < $this->max_participants;
    }

    public function getAvailablePlaces(): int
    {
        return $this->max_participants - $this->current_participants;
    }
}
