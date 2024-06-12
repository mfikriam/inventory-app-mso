<?php

namespace App\Filament\Resources\DatelResource\Pages;

use App\Filament\Resources\DatelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDatels extends ManageRecords
{
    protected static string $resource = DatelResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->disableCreateAnother()
                ->label('Buat Datel')
                ->modalHeading('Buat Datel')
                ->modalButton('Buat'),
        ];
    }
}
