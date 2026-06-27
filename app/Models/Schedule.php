<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'film_id',
        'activity_name',
        'activity_type',
        'date',
        'start_time',
        'end_time',
        'location',
        'pic',
        'attendees',
        'discussion_materials',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
        ];
    }

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }
}
