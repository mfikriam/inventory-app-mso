<?php

namespace App\Filament\Resources;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables;
use App\Models\Witel;
use App\Models\Datel;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\DatelResource\Pages;

class DatelResource extends Resource
{
    protected static ?string $model = Datel::class;
    protected static ?string $pluralModelLabel = 'Datel';
    protected static ?string $navigationLabel = 'Datel';
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'Wilayah';
    protected static ?int $navigationSort = 3;

    protected static function shouldRegisterNavigation():bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama')
                    ->unique(ignorable: fn ($record) => $record)
                    ->required(),
                Select::make('witel_id')
                    ->label('Nama Witel')
                    ->required()
                    ->searchable()
                    ->options(Witel::all()->pluck('name', 'id'))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('witel.name')->label('Nama Witel'),
            ])
            ->filters([
                Filter::make('witel_id')
                    ->label('Witel')
                    ->form([
                        Select::make('witel_id')
                            ->label('Witel')
                            ->searchable()
                            ->options(Witel::all()->pluck('name', 'id'))
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['witel_id'], fn($query) => $query->where('witel_id', $data['witel_id']));
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalButton('Ubah')
                    ->modalHeading('Edit Datel'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalButton('Hapus')
                    ->modalHeading('Hapus Datel')
                    ->modalSubheading('Apakah Anda yakin ingin melakukan ini?')
                    ->before(function (Tables\Actions\DeleteAction $action, $record) {
                        if ($record->sites()->exists()) {
                            Notification::make()
                                ->danger()
                                ->title('Tidak Dapat Menghapus Data')
                                ->body('Hapus terlebih dahulu data Site ID yang terkait dengan Datel ini!')
                                ->persistent()
                                ->actions([
                                    Action::make('sites')
                                        ->button()
                                        ->label('Pergi Ke Site ID')
                                        ->url("sites?tableFilters[datel_id][datel_id]=$record->id", shouldOpenInNewTab: false),
                                ])
                                ->send();

                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDatels::route('/'),
        ];
    }
}
