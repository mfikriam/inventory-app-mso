<?php

namespace App\Exports;

use App\Models\DeliveryType;
use App\Models\ExitItem;
use App\Models\Witel;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DeliveryTypeExport implements FromView, ShouldAutoSize, WithEvents
{
    use Exportable;

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;

                $sheet->getStyle('A:J')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A:J')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }
        ];
    }

    public function forWitel($witel_id)
    {
        $this->witel_id = $witel_id;
        return $this;
    }

    public function view(): View
    {
        $total_shipping_costs = 0;
        $total_holding_cost = 0;

        $witelsWithExitItemCount = Witel::with(['datels.sites.exit_items'])->get()->map(function ($witel) {
            $exitItemCount = $witel->datels->flatMap(function ($datel) {
                return $datel->sites->flatMap(function ($site) {
                    return $site->exit_items;
                });
            })->count();

            return [
                'witel' => $witel,
                'exitItemCount' => $exitItemCount,
            ];
        });

        foreach ($witelsWithExitItemCount as $item) {
            $total_shipping_costs += $item['witel']->delivery_type->price * $item['exitItemCount'];
        }

        foreach ($witelsWithExitItemCount as $item) {
            $total_holding_cost += ($item['witel']->delivery_type->price * (0.42 / 100)) / 2 * $item['exitItemCount'];
        }

        $allMonths = collect([]);

        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now()->endOfYear();

        while ($startDate->lte($endDate)) {
            $allMonths->push($startDate->format('m'));
            $startDate->addMonth();
        }

        $witel = Witel::with(['datels.sites.exit_items'])->find($this->witel_id);
        $exitItemCountByMonth = $witel->datels->flatMap(function ($datel) {
            return $datel->sites->flatMap(function ($site) {
                return $site->exit_items;
            });
        })->groupBy(function ($exitItem) {
            $dateOut = \Carbon\Carbon::parse($exitItem->date_out_date);
            return $dateOut->format('m');
        })->map(function ($groupedExitItems) {
            return $groupedExitItems->count();
        });

        $exitItemCountByMonth = $allMonths->mapWithKeys(function ($month) use ($exitItemCountByMonth) {
            return [$month => $exitItemCountByMonth->get($month, 0)];
        });

        return view('export-excel.delivery-type', compact('witelsWithExitItemCount', 'total_shipping_costs', 'total_holding_cost', 'witel', 'exitItemCountByMonth', 'allMonths'));
    }
}
