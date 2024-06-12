<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemPart extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'alias', 'image'];

    public function incoming_items(): HasMany
    {
        return $this->hasMany(IncomingItem::class);
    }
}
