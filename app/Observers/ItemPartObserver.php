<?php

namespace App\Observers;

use App\Models\ItemPart;
use Illuminate\Support\Facades\Storage;

class ItemPartObserver
{
    public function saved(ItemPart $item_part): void
    {
        if ($item_part->isDirty('image') && ($item_part->getOriginal('image') !== null)) {
            Storage::disk('public')->delete($item_part->getOriginal('image'));
        }
    }

    public function deleted(ItemPart $itemPart): void
    {
        if (! is_null($itemPart->image)) {
            Storage::disk('public')->delete($itemPart->image);
        }
    }

    public function forceDeleted(ItemPart $itemPart): void
    {
        //
    }
}
