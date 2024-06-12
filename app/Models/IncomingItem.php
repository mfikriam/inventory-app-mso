<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IncomingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'site_id',
        'date_entry',
        'part_number',
        'description',
        'type_part_id',
        'item_part_id',
        'serial_number',
        'status_part_id',
        'nuisance_ticket',
        'status_exdismentie_id',
    ];

    public function status_part(): BelongsTo
    {
        return $this->belongsTo(StatusPart::class);
    }

    public function status_exdismentie(): BelongsTo
    {
        return $this->belongsTo(StatusExdismentie::class);
    }

    public function item_part(): BelongsTo
    {
        return $this->belongsTo(ItemPart::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function type_part(): BelongsTo
    {
        return $this->belongsTo(TypePart::class);
    }

    public function exit_item(): HasOne
    {
        return $this->hasOne(ExitItem::class);
    }
}
