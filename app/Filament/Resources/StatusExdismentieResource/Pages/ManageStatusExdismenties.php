<?php

namespace App\Filament\Resources\StatusExdismentieResource\Pages;

use App\Filament\Resources\StatusExdismentieResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStatusExdismenties extends ManageRecords
{
    protected static string $resource = StatusExdismentieResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->disableCreateAnother()
                ->label('Buat Status Exdismentie')
                ->modalWidth('md')
                ->modalHeading('Buat Status Exdismentie')
                ->modalButton('Buat'),
        ];
    }
}
