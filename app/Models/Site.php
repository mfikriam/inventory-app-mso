<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;

    protected $fillable = ['datel_id', 'site_id', 'name'];

    public function datel(): BelongsTo
    {
        return $this->belongsTo(Datel::class);
    }

    public function incoming_items(): HasMany
    {
        return $this->hasMany(IncomingItem::class);
    }

    public function exit_items(): HasMany
    {
        return $this->hasMany(ExitItem::class);
    }
}
