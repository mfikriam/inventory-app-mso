<?php

namespace App\Filament\Widgets;

use App\Models\ExitItem;
use App\Models\IncomingItem;
use App\Models\ItemPart;
use App\Models\TypePart;
use App\Models\Witel;
use Closure;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class TableIncomingAndExitItem extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 4;
    public int $is_exit_items;

    public function mount(): void
    {
        $this->is_exit_items = 0;
    }

    protected function getTableHeading(): string|Htmlable|Closure|null
    {
        if ($this->is_exit_items == 0) {
            return 'Barang Keluar';
        }

        return 'Barang Masuk';
    }

    protected function getTableHeaderActions(): array
    {
        return [
            CreateAction::make('filter_data')
                ->label('Filter Data Keluaran')
                ->modalHeading('Filter Keluaran Data')
                ->modalWidth('sm')
                ->modalButton('Filter')
                ->disableCreateAnother()
                ->form([
                    Select::make('filter_data')
                        ->label('Pilih Data Keluaran')
                        ->options([
                            0 => 'Barang Keluar',
                            1 => 'Barang Masuk'
                        ])
                        ->default($this->is_exit_items)
                        ->required()
                ])->action(function (array $data) {
                    $this->is_exit_items = $data['filter_data'];
                })
        ];
    }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        if ($this->is_exit_items == 0) {
            return ExitItem::query();
        } else {
            return IncomingItem::query();
        }
    }

    protected function getTableColumns(): array
    {
        if ($this->is_exit_items == 0) {
            return [
                ImageColumn::make('image')->label('Gambar')->width(50)->height(50),
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
            ];
        } else {
            return [
                ImageColumn::make('image')->label('Gambar')->width(50)->height(50),
                TextColumn::make('item_part.name')->label('Nama Part'),
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
            ];
        }
    }

    protected function getTableFilters(): array
    {
        if ($this->is_exit_items == 0) {
            return [
                Filter::make('part')->label('Part')
                    ->form([
                        Fieldset::make('Part')->schema([
                            Grid::make(1)->schema([
                                Select::make('witel_id')
                                    ->label('Witel')
                                    ->required()
                                    ->searchable()
                                    ->options(Witel::all()->pluck('name', 'id')),
                                Select::make('type_part_id')
                                    ->label('Tipe Part')
                                    ->required()
                                    ->searchable()
                                    ->options(TypePart::all()->pluck('name', 'id')),
                            ])
                        ]),
                    ])->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['witel_id'],
                                fn(Builder $query, $witel): Builder => $query->whereHas('site.datel.witel', function ($query) use ($witel) {
                                    $query->where('id', $witel);
                                })
                            )
                            ->when(
                                $data['type_part_id'],
                                fn(Builder $query, $type_part): Builder => $query->whereHas('incoming_item', function ($query) use ($type_part) {
                                    $query->where('type_part_id', $type_part);
                                })
                            );
                    }),
            ];
        } else {
            return [
                Filter::make('part')->label('Part')
                    ->form([
                        Fieldset::make('Part')->schema([
                            Grid::make(1)->schema([
                                Select::make('witel_id')
                                    ->label('Witel')
                                    ->required()
                                    ->searchable()
                                    ->options(Witel::all()->pluck('name', 'id')),
                                Select::make('type_part_id')
                                    ->label('Tipe Part')
                                    ->required()
                                    ->searchable()
                                    ->options(TypePart::all()->pluck('name', 'id')),
                            ])
                        ]),
                    ])->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['witel_id'],
                                fn(Builder $query, $witel): Builder => $query->whereHas('site.datel.witel', function ($query) use ($witel) {
                                    $query->where('id', $witel);
                                })
                            )
                            ->when($data['type_part_id'], fn($query) => $query->where('type_part_id', $data['type_part_id']));
                    }),
            ];
        }
    }
}
