<?php

namespace App\Filament\Resources\IncomingItemResource\Pages;

use App\Exports\IncomingItemExport;
use App\Filament\Resources\IncomingItemResource;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Excel;
use Telegram\Bot\Laravel\Facades\Telegram;

class ListIncomingItems extends ListRecords
{
    protected static string $resource = IncomingItemResource::class;

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return null;
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Barang Masuk'),
        ];
    }

    protected function getTableFiltersFormColumns(): int
    {
        return 3;
    }

    protected function getTableHeaderActions(): array
    {
        return [
            CreateAction::make('export_excel_incoming_item')->label('Export Excel')
                ->icon('heroicon-o-document-download')
                ->modalHeading('Export Data Excel')
                ->modalWidth('sm')
                ->modalButton('Export')
                ->disableCreateAnother()
                ->action(function (array $data) {
                    $date = array_key_exists('date', $data) ? $data['date'] : null;
                    $start_date = array_key_exists('start_date', $data) ? $data['start_date'] : null;
                    $end_date = array_key_exists('end_date', $data) ? $data['end_date'] : null;

                    $export = new IncomingItemExport($date, $start_date, $end_date);

                    return $export->download('Barang Masuk.xlsx', Excel::XLSX);
                })
                ->form([
                    Select::make('selected_data')
                        ->label('Pilih Export Data')
                        ->options([
                            'all' => 'Semua',
                            'single_date' => 'Tanggal',
                            'date_range' => 'Rentang Tanggal',
                        ])
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state == 'single_date') {
                                $set('date', null);
                            } else {
                                $set('date', 'hidden');
                            }

                            if ($state == 'date_range') {
                                $set('start_date', null);
                                $set('end_date', null);
                            } else {
                                $set('start_date', 'hidden');
                                $set('end_date', 'hidden');
                            }
                        })
                    ,
                    DatePicker::make('date')
                        ->label('Tanggal')
                        ->displayFormat('d/m/Y')
                        ->hidden(fn (\Closure $get): bool => $get('selected_data') != 'single_date')
                        ->required(),
                    DatePicker::make('start_date')
                        ->label('Tanggal Mulai')
                        ->displayFormat('d/m/Y')
                        ->hidden(fn (\Closure $get): bool => $get('selected_data') != 'date_range')
                        ->required(),
                    DatePicker::make('end_date')
                        ->label('Tanggal Akhir')
                        ->displayFormat('d/m/Y')
                        ->hidden(fn (\Closure $get): bool => $get('selected_data') != 'date_range')
                        ->required(),
                ])
        ];
    }
}
