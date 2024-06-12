<?php

namespace App\Filament\Resources;

use Filament\Notifications\Notification;
use Filament\Tables;
use App\Models\Datel;
use App\Models\Witel;
use App\Models\TypePart;
use App\Models\ItemPart;
use App\Models\StatusPart;
use App\Models\IncomingItem;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\StatusExdismentie;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Card;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\IncomingItemResource\Pages;
use Telegram\Bot\Laravel\Facades\Telegram;

class IncomingItemResource extends Resource
{
    protected static ?string $model = IncomingItem::class;
    protected static ?string $pluralModelLabel = 'Barang Masuk';
    protected static ?string $navigationLabel = 'Barang Masuk';
    protected static ?string $navigationIcon = 'heroicon-o-sort-descending';
    protected static ?string $navigationGroup = 'Barang';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Grid::make([
                        'sm' => 1,
                        'md' => 3
                    ])
                        ->schema([
                            Grid::make(1)->columnSpan(1)
                                ->schema([
                                    DatePicker::make('date_entry')
                                        ->label('Tanggal Masuk')
                                        ->displayFormat('d/m/Y')
                                        ->required(),
                                    Fieldset::make('Tipe Part')->schema([
                                        Radio::make('type_part_id')
                                            ->disableLabel()
                                            ->options(TypePart::all()->pluck('name', 'id'))
                                            ->required(),
                                    ]),
                                    Select::make('item_part_id')
                                        ->label('Nama Part')
                                        ->required()
                                        ->searchable()
                                        ->options(ItemPart::all()->pluck('name', 'id'))
                                        ->required()->reactive()->afterStateUpdated(function (\Closure $set, $state) {
                                            $set('part_number', function () use ($state) {
                                                $item_part = ItemPart::find($state);
                                                if (!$item_part) {
                                                    return null;
                                                }
                                                return $item_part->alias . '-';
                                            });
                                        }),
                                    Select::make('status_part_id')
                                        ->label('Status Part')
                                        ->required()
                                        ->options(StatusPart::all()->pluck('name', 'id')),
                                    Select::make('status_exdismentie_id')
                                        ->label('Status Exdismentie')
                                        ->required()
                                        ->options(StatusExdismentie::all()->pluck('name', 'id')),
                                    FileUpload::make('image')
                                        ->label('Gambar')
                                        ->image()
                                        ->maxSize(1024)
                                        ->columnSpan('full')
                                        ->directory('incoming-item-images')
                                        ->required()
                                ]),
                            Grid::make(2)->columnSpan(2)
                                ->schema([
                                    TextInput::make('serial_number')
                                        ->label('Serial Number')
                                        ->unique(ignorable: fn($record) => $record)
                                        ->required(),
                                    TextInput::make('part_number')
                                        ->label('Part Number')
                                        ->unique(ignorable: fn($record) => $record)
                                        ->required(),
                                    Fieldset::make('From')->schema([
                                        Select::make('witel_id')
                                            ->label('Witel')
                                            ->required()
                                            ->searchable()
                                            ->options(Witel::all()->pluck('name', 'id'))
                                            ->reactive()
                                            ->afterStateUpdated(fn(callable $set) => $set('from_id', null)),
                                        Select::make('from_id')
                                            ->label('Datel')
                                            ->required()
                                            ->searchable()
                                            ->options(function (callable $get) {
                                                $witel = Witel::find($get('witel_id'));
                                                if (!$witel) {
                                                    return null;
                                                }
                                                return $witel->datels->pluck('name', 'id');
                                            })
                                            ->afterStateUpdated(fn(callable $set) => $set('site_id', null))
                                    ]),
                                    Select::make('site_id')
                                        ->label('Side ID')
                                        ->required()
                                        ->searchable()
                                        ->options(function (callable $get) {
                                            $datel = Datel::find($get('from_id'));
                                            if (!$datel) {
                                                return null;
                                            }
                                            return $datel->sites->pluck('site_id', 'id');
                                        }),
                                    TextInput::make('nuisance_ticket')
                                        ->label('Tiket Gangguan')
                                        ->required(),
                                    Textarea::make('description')
                                        ->label('Deskripsi')->columnSpan('full')
                                ])
                        ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('Gambar')->view('Tables.image-colum'),
                TextColumn::make('item_part.name')->label('Nama Part'),
                TextColumn::make('nuisance_ticket')->label('Tiket Gangguan'),
                TextColumn::make('part_number')->label('Part Number')->searchable(),
                TextColumn::make('serial_number')->label('Serial Number')->searchable(),
                TextColumn::make('status_part')->label('Status Part')
                    ->formatStateUsing(fn($record) => new HtmlString('
                            <div class="flex items-center gap-2 mt-1">
                                <span class="filament-tables-color-column relative flex h-6 w-6 rounded-md"
                                    style="background-color: ' . $record->status_part->color . '"></span>
                                <span>' . $record->status_part->name . '</span>
                            </div>
                    ')),
                TextColumn::make('status_exdismentie')->label('Status Exdismentie')
                    ->formatStateUsing(fn($record) => new HtmlString('
                            <div class="flex items-center gap-2 mt-1">
                                <span class="filament-tables-color-column relative flex h-6 w-6 rounded-md"
                                    style="background-color: ' . $record->status_exdismentie->color . '"></span>
                                <span>' . $record->status_exdismentie->name . '</span>
                            </div>
                    ')),
                TextColumn::make('site.datel.witel.name')->label('Witel'),
                TextColumn::make('site.datel.name')->label('Datel'),
                TextColumn::make('site.site_id')->label('SiteID'),
            ])
            ->filters([
                Filter::make('status')->label('Status')
                    ->form([
                        Fieldset::make('Status')->schema([
                            Grid::make(1)->schema([
                                Select::make('status_part_id')
                                    ->label('Status Part')
                                    ->required()
                                    ->searchable()
                                    ->options(StatusPart::all()->pluck('name', 'id')),
                                Select::make('status_exdismentie_id')
                                    ->label('Status Exdismentie')
                                    ->required()
                                    ->searchable()
                                    ->options(StatusExdismentie::all()->pluck('name', 'id')),
                            ])
                        ]),
                    ])->query(function ($query, array $data) {
                        return $query
                            ->when($data['status_part_id'], fn($query) => $query->where('status_part_id', $data['status_part_id']))
                            ->when($data['status_exdismentie_id'], fn($query) => $query->where('status_exdismentie_id', $data['status_exdismentie_id']));
                    }),
                Filter::make('part')->label('Part')
                    ->form([
                        Fieldset::make('Part')->schema([
                            Grid::make(1)->schema([
                                Select::make('type_part_id')
                                    ->label('Tipe Part')
                                    ->required()
                                    ->searchable()
                                    ->options(TypePart::all()->pluck('name', 'id')),
                                Select::make('item_part_id')
                                    ->label('Item Part')
                                    ->required()
                                    ->searchable()
                                    ->options(ItemPart::all()->pluck('name', 'id')),
                            ])
                        ]),
                    ])->query(function ($query, array $data) {
                        return $query
                            ->when($data['type_part_id'], fn($query) => $query->where('type_part_id', $data['type_part_id']))
                            ->when($data['item_part_id'], fn($query) => $query->where('item_part_id', $data['item_part_id']));
                    }),
                Filter::make('wilayah')->label('Witel')
                    ->form([
                        Fieldset::make('Wilayah')->schema([
                            Grid::make(1)->schema([
                                Select::make('witel_id')
                                    ->label('Witel')
                                    ->required()
                                    ->searchable()
                                    ->options(Witel::all()->pluck('name', 'id'))
                                    ->reactive()
                                    ->afterStateUpdated(fn(callable $set) => $set('from_id', null)),
                                Select::make('from_id')
                                    ->label('Datel')
                                    ->required()
                                    ->searchable()
                                    ->options(function (callable $get) {
                                        $witel = Witel::find($get('witel_id'));
                                        if (!$witel) {
                                            return null;
                                        }
                                        return $witel->datels->pluck('name', 'id');
                                    })
                                    ->afterStateUpdated(fn(callable $set) => $set('site_id', null)),
                                Select::make('site_id')
                                    ->label('Side ID')
                                    ->required()
                                    ->searchable()
                                    ->options(function (callable $get) {
                                        $datel = Datel::find($get('from_id'));
                                        if (!$datel) {
                                            return null;
                                        }
                                        return $datel->sites->pluck('site_id', 'id');
                                    }),
                            ])
                        ])
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['witel_id'],
                                fn(Builder $query, $witel): Builder => $query->whereHas('site.datel.witel', function ($query) use ($witel) {
                                    $query->where('id', $witel);
                                })
                            )
                            ->when(
                                $data['from_id'],
                                fn(Builder $query, $datel): Builder => $query->whereHas('site.datel', function ($query) use ($datel) {
                                    $query->where('id', $datel);
                                })
                            )
                            ->when(
                                $data['site_id'],
                                fn(Builder $query, $site): Builder => $query->whereHas('site', function ($query) use ($site) {
                                    $query->where('id', $site);
                                })
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function () {
                        Telegram::sendMessage([
                            'chat_id' => env('TELEGRAM_CHAT_ID', '-1001542563732'),
                            'text' => 'asdjhkjhkas'
                        ]);
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalButton('Hapus')
                    ->modalHeading('Hapus Barang Masuk')
                    ->modalSubheading('Apakah Anda yakin ingin melakukan ini?')
                    ->before(function (Tables\Actions\DeleteAction $action, $record) {
                        if ($record->exit_item()->exists()) {
                            Notification::make()
                                ->danger()
                                ->title('Tidak Dapat Menghapus Data')
                                ->body('Hapus terlebih dahulu data Barang Keluar yang terkait dengan Barang Masuk ini!')
                                ->persistent()
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
            'index' => Pages\ListIncomingItems::route('/'),
            'create' => Pages\CreateIncomingItem::route('/create'),
            'edit' => Pages\EditIncomingItem::route('/{record}/edit'),
        ];
    }
}
