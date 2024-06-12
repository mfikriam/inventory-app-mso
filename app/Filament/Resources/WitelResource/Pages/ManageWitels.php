<?php

namespace App\Filament\Resources\WitelResource\Pages;

use App\Filament\Resources\WitelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageWitels extends ManageRecords
{
    protected static string $resource = WitelResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->disableCreateAnother()
                ->label('Buat Witel')
                ->modalHeading('Buat Witel')
                ->modalButton('Buat'),
        ];
    }
}
