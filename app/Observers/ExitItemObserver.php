<?php

namespace App\Observers;

use App\Models\ExitItem;
use Illuminate\Support\Facades\Storage;

class ExitItemObserver
{
    public function saved(ExitItem $exitItem): void
    {
        if ($exitItem->isDirty('image') && ($exitItem->getOriginal('image') !== null)) {
            Storage::disk('public')->delete($exitItem->getOriginal('image'));
        }
    }

    public function deleted(ExitItem $exitItem): void
    {
        if (! is_null($exitItem->image)) {
            Storage::disk('public')->delete($exitItem->image);
        }
    }
}
