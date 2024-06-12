<?php

namespace App\Exports;

use App\Models\ExitItem;
use App\Models\IncomingItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\In;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class IncomingItemExport implements FromView, ShouldAutoSize, WithEvents
{
    use Exportable;

    protected $date;
    protected $start_date;
    protected $end_date;

    public function __construct($date = null, $start_date = null, $end_date = null)
    {
        $this->date = $date;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

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


    public function view(): View
    {
        $date = $this->date;
        $startDate = $this->start_date;
        $endDate = $this->end_date;

        if ($date) {
            $incomingItem = IncomingItem::where('date_entry', $date)->get();
        } elseif ($startDate && $endDate) {
            $incomingItem = IncomingItem::whereBetween('date_entry', [$startDate, $endDate])->get();
        } else {
            $incomingItem = IncomingItem::all();
        }

        return view('export-excel.incoming-item', compact('incomingItem', 'startDate', 'endDate', 'date'));
    }
}
