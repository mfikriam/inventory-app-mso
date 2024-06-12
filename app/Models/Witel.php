<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Witel extends Model
{
    use HasFactory;

    protected $fillable = ['delivery_type_id', 'name'];

    public function delivery_type(): BelongsTo
    {
        return $this->belongsTo(DeliveryType::class);
    }

    public function datels(): HasMany
    {
        return $this->hasMany(Datel::class);
    }
}
