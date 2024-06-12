<?php

namespace App\Filament\Resources\TypePartResource\Pages;

use App\Filament\Resources\TypePartResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTypeParts extends ManageRecords
{
    protected static string $resource = TypePartResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->disableCreateAnother()
                ->label('Buat Tipe Part')
                ->modalHeading('Buat Tipe Part')
                ->modalButton('Buat')
                ->modalWidth('md'),
        ];
    }
}
