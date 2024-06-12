<?php

namespace App\Filament\Resources;

use App\Models\Site;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables;
use App\Models\Datel;
use App\Models\Witel;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\SiteResource\Pages;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;
    protected static ?string $pluralModelLabel = "Site-ID";
    protected static ?string $navigationLabel = 'Site ID';
    protected static ?string $navigationIcon = 'heroicon-o-office-building';
    protected static ?string $navigationGroup = 'Wilayah';
    protected static ?int $navigationSort = 4;

    protected static function shouldRegisterNavigation():bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('witel_id')
                    ->label('Witel')
                    ->required()
                    ->searchable()
                    ->options(Witel::all()->pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('datel_id', null)),
                Select::make('datel_id')
                    ->label('Datel')
                    ->required()
                    ->searchable()
                    ->options(function (callable $get) {
                        $witel = Witel::find($get('witel_id'));
                        if (!$witel) {
                            return null;
                        }
                        return $witel->datels->pluck('name', 'id');
                    }),
                TextInput::make('site_id')
                    ->label('Site ID')
                    ->unique(ignorable: fn ($record) => $record)
                    ->required(),
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('site_id')->label('Site ID')->searchable(),
                TextColumn::make('datel.name')->label('Nama Datel'),
                TextColumn::make('datel.witel.name')->label('Nama Witel'),
            ])
            ->filters([
                Filter::make('datel_id')
                    ->label('Datel')->form([
                        Select::make('datel_id')
                            ->label('Datel')
                            ->required()
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search) => Datel::where('name', 'like', "%{$search}%")->limit(5)->pluck('name', 'id'))
                            ->getOptionLabelUsing(fn ($value): ?string => Datel::find($value)?->name),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['datel_id'], fn($query) => $query->where('datel_id', $data['datel_id']));
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalButton('Ubah')
                    ->modalHeading('Edit Site ID')
                    ->mutateRecordDataUsing(function (array $data): array {
                        $datel = Datel::find($data['datel_id']);

                        $data['witel_id'] = $datel->witel->id;

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalButton('Hapus')
                    ->modalHeading('Hapus Site ID')
                    ->modalSubheading('Apakah Anda yakin ingin melakukan ini?')
                    ->before(function (Tables\Actions\DeleteAction $action, $record) {
                        if ($record->incoming_items()->exists()) {
                            $witel_id = $record->datel->witel_id;
                            $datel_id = $record->datel->id;
                            Notification::make()
                                ->danger()
                                ->title('Tidak Dapat Menghapus Data')
                                ->body('Hapus terlebih dahulu data Barang Masuk yang terkait dengan SiteID ini!')
                                ->persistent()
                                ->actions([
                                    Action::make('incoming_items')
                                        ->button()
                                        ->label('Pergi Ke Barang Masuk')
                                        ->url("incoming-items?tableFilters[wilayah][witel_id]=$witel_id&tableFilters[wilayah][from_id]=$datel_id&tableFilters[wilayah][site_id]=$record->id"),
                                ])
                                ->send();

                            $action->cancel();
                        }

                        if ($record->exit_items()->exists()) {
                            $witel_id = $record->datel->witel_id;
                            $datel_id = $record->datel->id;
                            Notification::make()
                                ->danger()
                                ->title('Tidak Dapat Menghapus Data')
                                ->body('Hapus terlebih dahulu data Barang Masuk yang terkait dengan SiteID ini!')
                                ->persistent()
                                ->actions([
                                    Action::make('incoming_items')
                                        ->button()
                                        ->label('Pergi Ke Barang Masuk')
                                        ->url("exit-items?tableFilters[wilayah][witel_id]=$witel_id&tableFilters[wilayah][to_id]=$datel_id&tableFilters[wilayah][site_id]=$record->id"),
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
            'index' => Pages\ManageSites::route('/'),
        ];
    }
}
