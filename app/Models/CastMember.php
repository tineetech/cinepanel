<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CastMember extends Model
{
    protected $fillable = [
        'film_id',
        'name',
        'character_name',
        'origin',
        'role_type',
        'age',
        'phone',
        'image',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
        ];
    }

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    public function shotLists(): HasMany
    {
        return $this->hasMany(ShotList::class, 'cast_id');
    }
}
