<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShotList extends Model
{
    protected $fillable = [
        'film_id',
        'scene',
        'shot_order',
        'shot_description',
        'camera_type',
        'camera_movement',
        'estimated_duration',
        'location_id',
        'cast_id',
        'sound',
        'shoot_time',
        'status',
        'director_notes',
    ];

    protected function casts(): array
    {
        return [
            'sound' => 'array',
        ];
    }

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function cast(): BelongsTo
    {
        return $this->belongsTo(CastMember::class, 'cast_id');
    }
}
