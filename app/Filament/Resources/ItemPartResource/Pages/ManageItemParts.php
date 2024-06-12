<?php

namespace App\Filament\Resources\ItemPartResource\Pages;

use App\Filament\Resources\ItemPartResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageItemParts extends ManageRecords
{
    protected static string $resource = ItemPartResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->disableCreateAnother()
                ->label('Buat Item Part')
                ->modalHeading('Buat Item Part')
                ->modalButton('Buat')
                ->modalWidth('md'),
        ];
    }
}
