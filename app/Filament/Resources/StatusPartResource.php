<?php

namespace App\Filament\Resources;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables;
use App\Models\StatusPart;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use App\Filament\Resources\StatusPartResource\Pages;

class StatusPartResource extends Resource
{
    protected static ?string $model = StatusPart::class;
    protected static ?string $pluralModelLabel = 'Status Part';
    protected static ?string $navigationLabel = 'Status Part';
    protected static ?string $navigationIcon = 'heroicon-o-status-online';
    protected static ?string $navigationGroup = 'Status';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->columnSpan('full')
                    ->label('Nama Status')
                    ->unique(ignorable: fn($record) => $record)
                    ->required(),
                ColorPicker::make('color')
                    ->columnSpan('full')
                    ->label('Warna Status')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Status'),
                ColorColumn::make('color')
                    ->label('Warna Status')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('md')
                    ->modalButton('Ubah')
                    ->modalHeading('Edit Status Part'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalButton('Hapus')
                    ->modalHeading('Hapus Status Part')
                    ->modalSubheading('Apakah Anda yakin ingin melakukan ini?')
                    ->before(function (Tables\Actions\DeleteAction $action, $record) {
                        if ($record->incoming_items()->exists()) {
                            Notification::make()
                                ->danger()
                                ->title('Tidak Dapat Menghapus Data')
                                ->body('Hapus terlebih dahulu data Barang Masuk yang terkait dengan Status Part ini!')
                                ->persistent()
                                ->actions([
                                    Action::make('incoming_items')
                                        ->button()
                                        ->label('Pergi Ke Barang Masuk')
                                        ->url("incoming-items?tableFilters[status][status_part_id]=$record->id", shouldOpenInNewTab: false),
                                ])
                                ->send();

                            $action->cancel();
                        }

                        if ($record->exit_items()->exists()) {
                            Notification::make()
                                ->danger()
                                ->title('Tidak Dapat Menghapus Data')
                                ->body('Hapus terlebih dahulu data Barang Keluar yang terkait dengan Status Exdismentie ini!')
                                ->persistent()
                                ->actions([
                                    Action::make('exit_items')
                                        ->button()
                                        ->label('Pergi Ke Barang Keluar')
                                        ->url("exit-items?tableFilters[status][status_part_id]=$record->id", shouldOpenInNewTab: false),
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
            'index' => Pages\ManageStatusParts::route('/'),
        ];
    }
}
