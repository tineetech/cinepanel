<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Crew extends Model
{
    protected $fillable = [
        'film_id',
        'name',
        'position',
        'origin',
        'department',
        'image',
        'phone',
        'email',
        'status',
    ];

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }
}
