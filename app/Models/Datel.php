<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Datel extends Model
{
    use HasFactory;

    protected $fillable = ['witel_id', 'name'];

    public function witel(): BelongsTo
    {
        return $this->belongsTo(Witel::class);
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }
}
