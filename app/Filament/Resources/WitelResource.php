<?php

namespace App\Filament\Resources;

use App\Models\DeliveryType;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables;
use App\Models\Witel;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\WitelResource\Pages;

class WitelResource extends Resource
{
    protected static ?string $model = Witel::class;
    protected static ?string $pluralModelLabel = 'Witel';
    protected static ?string $navigationLabel = 'Witel';
    protected static ?string $navigationIcon = 'heroicon-s-map';
    protected static ?string $navigationGroup = 'Wilayah';
    protected static ?int $navigationSort = 2;

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
                    ->required()
                    ->unique(ignorable: fn ($record) => $record),
                Select::make('delivery_type_id')
                    ->label('Tipe Pengiriman')
                    ->options(DeliveryType::all()->pluck('name', 'id'))
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama'),
                TextColumn::make('delivery_type.name')->label('Tipe Pengiriman'),
                TextColumn::make('delivery_type.price')
                    ->label('Harga Pengiriman')
                    ->getStateUsing(fn($record) => 'Rp. ' . number_format($record->delivery_type->price, 0, ',', '.')),
            ])
            ->filters([
                Filter::make('delivery_type_id')
                    ->label('Tipe Pengiriman')
                    ->form([
                        Select::make('delivery_type_id')
                            ->label('Tipe Pengiriman')
                           
                            ->options(DeliveryType::all()->pluck('name', 'id'))
                    ])
                    ->query(function ($query, array $data){
                        return $query->when($data['delivery_type_id'], fn ($query) => $query->where('delivery_type_id', $data['delivery_type_id']));
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalButton('Ubah')
                    ->modalHeading('Edit Witel'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalButton('Hapus')
                    ->modalHeading('Hapus Witel')
                    ->modalSubheading('Apakah Anda yakin ingin melakukan ini?')
                    ->before(function (Tables\Actions\DeleteAction $action, $record) {
                        if ($record->datels()->exists()) {
                            Notification::make()
                                ->danger()
                                ->title('Tidak Dapat Menghapus Data')
                                ->body('Hapus terlebih dahulu data Datel yang terkait dengan Witel ini!')
                                ->persistent()
                                ->actions([
                                    Action::make('datels')
                                        ->button()
                                        ->label('Pergi Ke Datel')
                                        ->url("datels?tableFilters[witel_id][witel_id]=$record->id", shouldOpenInNewTab: false),
                                ])
                                ->send();

                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageWitels::route('/'),
        ];
    }
}
