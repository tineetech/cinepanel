<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Film extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'genre',
        'year',
        'director',
        'producer',
        'budget',
        'status',
        'start_date',
        'end_date',
        'synopsis',
        'poster',
        'image',
        'is_focus',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'budget' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'is_focus' => 'boolean',
        ];
    }

    public function castMembers(): HasMany
    {
        return $this->hasMany(CastMember::class);
    }

    public function crews(): HasMany
    {
        return $this->hasMany(Crew::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function rabItems(): HasMany
    {
        return $this->hasMany(RabItem::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function scripts(): HasMany
    {
        return $this->hasMany(Script::class);
    }

    public function shotLists(): HasMany
    {
        return $this->hasMany(ShotList::class);
    }
}
