<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price'];

    public function witels(): HasMany
    {
        return $this->hasMany(Witel::class);
    }

}
