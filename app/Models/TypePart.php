<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypePart extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function incoming_items(): HasMany
    {
        return $this->hasMany(IncomingItem::class);
    }

    public function exit_items(): HasMany
    {
        return $this->hasMany(ExitItem::class);
    }
}
