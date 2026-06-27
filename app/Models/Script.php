<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Script extends Model
{
    protected $fillable = [
        'film_id',
        'title',
        'writer',
        'version',
        'page_count',
        'status',
        'revision_notes',
        'file_path',
    ];

    protected function casts(): array
    {
        return [
            'page_count' => 'integer',
        ];
    }

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }
}
