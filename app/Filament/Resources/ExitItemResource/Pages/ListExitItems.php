<?php

namespace App\Filament\Resources\ExitItemResource\Pages;

use App\Exports\ExitItemExport;
use App\Filament\Resources\ExitItemResource;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\CreateAction;
use Maatwebsite\Excel\Excel;

class ListExitItems extends ListRecords
{
    protected static string $resource = ExitItemResource::class;
    protected function getTableRecordUrlUsing(): ?\Closure
    {
        return null;
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Barang Keluar'),
        ];
    }

    protected function getTableFiltersFormColumns(): int
    {
        return 3;
    }

    protected function getTableHeaderActions(): array
    {
        return [
            CreateAction::make('export_excel')->label('Export Excel')
                ->icon('heroicon-o-document-download')
                ->modalHeading('Export Data Excel')
                ->modalWidth('sm')
                ->modalButton('Export')
                ->disableCreateAnother()
                ->action(function (array $data) {
                    $nameFile = auth()->user()->name . '-' . $data['tanggal'] . '.xlsx';
                    return (new ExitItemExport)->forDate($data['tanggal'])->download($nameFile, Excel::XLSX);
                })->form([
                    DatePicker::make('tanggal')
                        ->label('Pilih Tanggal')
                        ->displayFormat('d/m/Y')
                        ->required(),
                ])
        ];
    }
}
