<?php

namespace App\Filament\Widgets;

use App\Models\IncomingItem;
use App\Models\StatusExdismentie;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class TypePart extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getCards(): array
    {
        $cards = [];
        $total_exdismentie = 0;

//        $type_parts = \App\Models\TypePart::all();
//        foreach ($type_parts as $type_part) {
//            $cards[] = Card::make('PART' . $type_part->name, $type_part->incoming_items->count());
//        }

        $cards[] = Card::make('TOTAL BARANG DIGUDANG', IncomingItem::doesnthave('exit_item')->count());

        return $cards;
    }
}
