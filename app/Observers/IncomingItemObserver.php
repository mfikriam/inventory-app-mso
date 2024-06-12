<?php

namespace App\Observers;

use App\Models\IncomingItem;
use Illuminate\Support\Facades\Storage;

class IncomingItemObserver
{
    public function saved(IncomingItem $incoming_item): void
    {
        if ($incoming_item->isDirty('image') && ($incoming_item->getOriginal('image') !== null)) {
            Storage::disk('public')->delete($incoming_item->getOriginal('image'));
        }
    }

    public function deleted(IncomingItem $incoming_item): void
    {
        if (! is_null($incoming_item->image)) {
            Storage::disk('public')->delete($incoming_item->image);
        }
    }
}
