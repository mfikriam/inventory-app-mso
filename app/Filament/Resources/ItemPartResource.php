<?php

namespace App\Filament\Resources;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables;
use App\Models\ItemPart;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\ItemPartResource\Pages;

class ItemPartResource extends Resource
{
    protected static ?string $model = ItemPart::class;
    protected static ?string $pluralModelLabel = 'Item Part';
    protected static ?string $navigationLabel = 'Item Part';
    protected static ?string $navigationIcon = 'heroicon-o-hashtag';
    protected static ?string $navigationGroup = 'Parts';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Part')
                    ->columnSpan('full')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (\Closure $set, $state) {
                        $set('alias', Str::slug($state));
                    }),
                TextInput::make('alias')
                    ->label('Alias Part')
                    ->columnSpan('full')
                    ->required(),
                FileUpload::make('image')
                    ->label('Gambar Part')
                    ->image()
                    ->maxSize(1024)
                    ->columnSpan('full')
                    ->directory('item-part-images')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->width(50)->height(50),
                TextColumn::make('name')->label('Nama Part')->searchable()->sortable(),
                TextColumn::make('alias')->label('Alias Part')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalButton('Ubah')
                    ->modalWidth('md')
                    ->modalHeading('Edit Item Part'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalButton('Hapus')
                    ->modalHeading('Hapus Item Part')
                    ->modalSubheading('Apakah Anda yakin ingin melakukan ini?')
                    ->before(function (Tables\Actions\DeleteAction $action, $record) {
                        if ($record->incoming_items()->exists()) {
                            Notification::make()
                                ->danger()
                                ->title('Tidak Dapat Menghapus Data')
                                ->body('Hapus terlebih dahulu data Barang Masuk yang terkait dengan Item Part ini!')
                                ->persistent()
                                ->actions([
                                    Action::make('incoming_items')
                                        ->button()
                                        ->label('Pergi Ke Barang Masuk')
                                        ->url("incoming-items?tableFilters[part][item_part_id]=$record->id", shouldOpenInNewTab: false),
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
            'index' => Pages\ManageItemParts::route('/'),
        ];
    }
}
