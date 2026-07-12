<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['topic_id', 'label'];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
