<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Tables;
use App\Models\Datel;
use App\Models\Witel;
use App\Models\ExitItem;
use App\Models\TypePart;
use App\Models\ItemPart;
use App\Models\StatusPart;
use App\Models\IncomingItem;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\StatusExdismentie;
use Illuminate\Support\HtmlString;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ExitItemResource\Pages;

class ExitItemResource extends Resource
{
    protected static ?string $model = ExitItem::class;
    protected static ?string $pluralModelLabel = 'Barang Keluar';
    protected static ?string $navigationLabel = 'Barang Keluar';
    protected static ?string $navigationIcon = 'heroicon-o-sort-ascending';
    protected static ?string $navigationGroup = 'Barang';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Fieldset::make('Data Gudang')->schema([
                        Grid::make([
                            'sm' => 1,
                            'md' => 3
                        ])->schema([
                            Grid::make(2)
                                ->columnSpan(2)
                                ->schema([
                                    Select::make('incoming_item_id')
                                        ->label('Serial Number')
                                        ->placeholder('Cari Serial Number')
                                        ->searchable()
                                        ->getSearchResultsUsing(fn(string $search) => IncomingItem::where('serial_number', 'like', "%{$search}%")
                                            ->doesntHave('exit_item')
                                            ->limit(5)
                                            ->pluck('serial_number', 'id'))
                                        ->getOptionLabelUsing(fn($value): ?string => IncomingItem::find($value)?->serial_number)
                                        ->required()
                                        ->reactive()
                                        ->unique(ignorable: fn($record) => $record)
                                        ->afterStateUpdated(function (\Closure $set, $state) {
                                            $incoming_items = IncomingItem::find($state);

                                            $set('part_number', $incoming_items->part_number ?? null);
                                            $set('witel', $incoming_items->site->datel->witel->name ?? null);
                                            $set('datel', $incoming_items->site->datel->name ?? null);
                                            $set('site', $incoming_items->site->site_id ?? null);
                                            $set('nuisance_ticket_incoming_items', $incoming_items->nuisance_ticket ?? null);
                                            $set('description_incoming_items', $incoming_items->description ?? null);
                                            $set('date_entry', $incoming_items->date_entry ?? null);
                                            $set('type_part', $incoming_items->type_part->name ?? null);
                                            $set('item_part', $incoming_items->item_part->name ?? null);
                                            $set('status_part', $incoming_items->status_part->name ?? null);
                                            $set('status_exdismentie', $incoming_items->status_exdismentie->name ?? null);
                                        }),
                                    TextInput::make('part_number')
                                        ->label('Part Number')
                                        ->disabled(),
                                    Fieldset::make('From')->schema([
                                        TextInput::make('witel')
                                            ->label('Witel')
                                            ->disabled(),
                                        TextInput::make('datel')
                                            ->label('Datel')
                                            ->disabled(),
                                    ]),
                                    TextInput::make('site')
                                        ->label('SiteID')
                                        ->disabled(),
                                    TextInput::make('nuisance_ticket_incoming_items')
                                        ->label('Tiket Gangguan')
                                        ->disabled(),
                                    Textarea::make('description_incoming_items')
                                        ->label('Deskripsi')
                                        ->rows(5)
                                        ->columnSpan('full')
                                        ->disabled()
                                ]),
                            Grid::make(1)->columnSpan(1)
                                ->schema([
                                    DatePicker::make('date_entry')
                                        ->label('Tanggal Masuk')
                                        ->displayFormat('d/m/Y')
                                        ->disabled(),
                                    Fieldset::make('Tipe Part')->schema([
                                        TextInput::make('type_part')
                                            ->disableLabel()
                                            ->disabled(),
                                    ]),
                                    TextInput::make('status_part')
                                        ->label('Status Part')
                                        ->disabled(),
                                    TextInput::make('status_exdismentie')
                                        ->label('Status Exdismentie')
                                        ->disabled(),
                                ])
                        ]),
                    ])
                ])->columns(1),
                Card::make([
                    Fieldset::make('Barang Keluar')->schema([
                        Grid::make(3)->schema([
                            Grid::make(1)->columnSpan(1)
                                ->schema([
                                    Select::make('user_id')
                                        ->label('Pilih User')
                                        ->options(User::all()->pluck('name', 'id'))
                                        ->hidden(auth()->user()->is_admin != 1)
                                        ->required(),
                                    DatePicker::make('date_out_date')
                                        ->label('Tanggal Keluar')
                                        ->displayFormat('d/m/Y')
                                        ->required(),
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
                                        ->directory('exit-item-images')
                                ]),
                            Grid::make(2)->columnSpan(2)
                                ->schema([
                                    Fieldset::make('To')->schema([
                                        Select::make('witel_id')
                                            ->label('Witel')
                                            ->required()
                                            ->searchable()
                                            ->options(Witel::all()->pluck('name', 'id'))
                                            ->reactive()
                                            ->afterStateUpdated(fn(callable $set) => $set('to_id', null)),
                                        Select::make('to_id')
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
                                            $datel = Datel::find($get('to_id'));
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
                ])->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('Gambar')->view('Tables.image-colum'),
                TextColumn::make('incoming_item.item_part.name')->label('Nama Part'),
                TextColumn::make('incoming_item.part_number')->label('Part Number')->searchable(),
                TextColumn::make('incoming_item.serial_number')->label('Serial Number')->searchable(),
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
                                    ->afterStateUpdated(fn(callable $set) => $set('to_id', null)),
                                Select::make('to_id')
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
                                        $datel = Datel::find($get('to_id'));
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
                                $data['to_id'],
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
                            ->when(
                                $data['type_part_id'],
                                fn(Builder $query, $type_part): Builder => $query->whereHas('incoming_item', function ($query) use ($type_part) {
                                    $query->where('type_part_id', $type_part);
                                })
                            )
                            ->when(
                                $data['item_part_id'],
                                fn(Builder $query, $item_part): Builder => $query->whereHas('incoming_item', function ($query) use ($item_part) {
                                    $query->where('item_part_id', $item_part);
                                })
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        if(auth()->user()->is_admin == 1) {
            return ExitItem::query(); // TODO: Change the autogenerated stub
        } else {
            return ExitItem::where('user_id', auth()->id()); // TODO: Change the autogenerated stub
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExitItems::route('/'),
            'create' => Pages\CreateExitItem::route('/create'),
            'edit' => Pages\EditExitItem::route('/{record}/edit'),
        ];
    }
}
