<?php

namespace App\Filament\Resources\DeliveryTypeResource\Pages;

use App\Exports\DeliveryTypeExport;
use App\Exports\ExitItemExport;
use App\Models\Witel;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\DeliveryTypeResource;
use Filament\Tables\Actions\CreateAction;
use Maatwebsite\Excel\Excel;

class ManageDeliveryTypes extends ManageRecords
{
    protected static string $resource = DeliveryTypeResource::class;

    protected function getTableHeaderActions(): array
    {
        return [
            CreateAction::make('export_excel')->label('Perhitungan Export Excel')
                ->icon('heroicon-o-document-download')
                ->modalHeading('Export Data Excel')
                ->modalWidth('sm')
                ->modalButton('Export')
                ->disableCreateAnother()
                ->action(function (array $data) {
                    $nameFile = 'HASIL PERHITUNGAN.xlsx';
                    return (new DeliveryTypeExport())->forWitel($data['witel_id'])->download($nameFile, Excel::XLSX);
                })->form([
                    Select::make('witel_id')->label('Pilih Witel')->options(Witel::all()->pluck('name', 'id'))->required()
                ])
        ];
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->disableCreateAnother()
                ->label('Buat Tipe Pengiriman')
                ->modalHeading('Buat Tipe Pengiriman')
                ->modalButton('Buat')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['price'] = str_replace([',', '.'], '', $data['price']);
                    

                    return $data;
                }),
        ];
    }
}
