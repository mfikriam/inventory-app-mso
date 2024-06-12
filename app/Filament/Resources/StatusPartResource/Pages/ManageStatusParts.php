<?php

namespace App\Filament\Resources\StatusPartResource\Pages;

use App\Filament\Resources\StatusPartResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStatusParts extends ManageRecords
{
    protected static string $resource = StatusPartResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->disableCreateAnother()
                ->label('Buat Status Part')
                ->modalWidth('md')
                ->modalHeading('Buat Status Part')
                ->modalButton('Buat'),
        ];
    }
}
