<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables;
use App\Models\TypePart;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\TypePartResource\Pages;
use Filament\Tables\Columns\TextColumn;

class TypePartResource extends Resource
{
    protected static ?string $model = TypePart::class;
    protected static ?string $pluralModelLabel = 'Tipe Part';
    protected static ?string $navigationLabel = 'Tipe Part';
    protected static ?string $navigationIcon = 'heroicon-o-color-swatch';
    protected static ?string $navigationGroup = 'Parts';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama')
                    ->columnSpan('full')
                    ->unique(ignorable: fn ($record) => $record)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalButton('Ubah')
                    ->modalWidth('md')
                    ->modalHeading('Edit Tipe Part'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalButton('Hapus')
                    ->modalHeading('Hapus Tipe Part')
                    ->modalSubheading('Apakah Anda yakin ingin melakukan ini?')
                    ->before(function (Tables\Actions\DeleteAction $action, $record) {
                        if ($record->incoming_items()->exists()) {
                            Notification::make()
                                ->danger()
                                ->title('Tidak Dapat Menghapus Data')
                                ->body('Hapus terlebih dahulu data Barang Masuk yang terkait dengan Tipe Part ini!')
                                ->persistent()
                                ->actions([
                                    Action::make('incoming_items')
                                        ->button()
                                        ->label('Pergi Ke Barang Masuk')
                                        ->url("incoming-items?tableFilters[part][type_part_id]=$record->id", shouldOpenInNewTab: false),
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
            'index' => Pages\ManageTypeParts::route('/'),
        ];
    }
}
