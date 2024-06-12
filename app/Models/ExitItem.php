<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExitItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'site_id',
        'incoming_item_id',
        'status_part_id',
        'status_exdismentie_id',
        'image',
        'nuisance_ticket',
        'date_out_date',
        'description',
    ];

    public function status_part(): BelongsTo
    {
        return $this->belongsTo(StatusPart::class);
    }

    public function status_exdismentie(): BelongsTo
    {
        return $this->belongsTo(StatusExdismentie::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function incoming_item(): BelongsTo
    {
        return $this->belongsTo(IncomingItem::class);
    }
}
