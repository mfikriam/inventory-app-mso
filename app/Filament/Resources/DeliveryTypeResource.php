<?php

namespace App\Filament\Resources;

use App\Forms\Components\Money;
use Filament\Tables;
use App\Models\DeliveryType;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\DeliveryTypeResource\Pages;
use Livewire\Livewire;

class DeliveryTypeResource extends Resource
{
    protected static ?string $model = DeliveryType::class;
    protected static ?string $pluralModelLabel = 'Tipe Pengiriman';
    protected static ?string $navigationLabel = 'Tipe Pengiriman';
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Perhitungan';
    protected static ?int $navigationSort = 1;

    protected static function shouldRegisterNavigation():bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama')
                    ->unique(ignorable: fn ($record) => $record)
                    ->required(),
                Money::make('price')
                    ->label('Harga')
                    ->prefix('Rp.')
                    ->unique(ignorable: fn ($record) => $record)
                    ->required(),

            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama'),
                TextColumn::make('price')
                    ->label('Harga')
                    ->getStateUsing(fn($record) => 'Rp. ' . number_format($record->price, 0, ',', '.')),


            ])


    

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalButton('Ubah')
                    ->modalHeading('Edit Tipe Pengiriman')
                    ->mutateRecordDataUsing(function (array $data): array {
                        $data['price'] = number_format($data['price'], 0, ',', '.');

                        return $data;
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['price'] = $data['price'] = str_replace([',', '.'], '', $data['price']);

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalButton('Hapus')
                    ->modalHeading('Hapus Tipe Pengiriman')
                    ->modalSubheading('Apakah Anda yakin ingin melakukan ini?')
                    ->before(function (Tables\Actions\DeleteAction $action, $record) {
                        if ($record->witels()->exists()) {
                            Notification::make()
                                ->danger()
                                ->title('Tidak Dapat Menghapus Data')
                                ->body('Hapus terlebih dahulu data Witel yang terkait dengan Tipe Pengiriman ini!')
                                ->persistent()
                                ->actions([
                                    Action::make('witels')
                                        ->button()
                                        ->label('Pergi Ke Witel')
                                        ->url("witels?tableFilters[delivery_type_id][delivery_type_id]=$record->id", shouldOpenInNewTab: false),
                                ])
                                ->send();

                            $action->cancel();
                        }
                    })
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }





    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDeliveryTypes::route('/'),
        ];
    }
}
