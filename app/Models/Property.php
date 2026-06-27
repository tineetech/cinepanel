<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Property extends Model
{
    protected $fillable = [
        'film_id',
        'name',
        'category',
        'quantity',
        'unit',
        'estimated_price',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'estimated_price' => 'decimal:2',
        ];
    }

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }
}
