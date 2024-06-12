<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusExdismentie extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    public function incoming_items(): HasMany
    {
        return $this->hasMany(IncomingItem::class);
    }

    public function exit_items(): HasMany
    {
        return $this->hasMany(ExitItem::class);
    }
}
