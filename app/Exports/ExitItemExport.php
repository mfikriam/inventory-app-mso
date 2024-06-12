<?php

namespace App\Exports;

use App\Models\ExitItem;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExitItemExport implements FromView, ShouldAutoSize, WithEvents
{
    use Exportable;

    public function forDate(string $date)
    {
        $this->date = $date;

        return $this;
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
        $namaUser = auth()->user()->name;
        $tanggal = $this->date;
        $exitItem = ExitItem::where('user_id', auth()->id())->whereDate('date_out_date', $this->date)->get();

        return view('export-excel.exit-item', compact('exitItem', 'tanggal', 'namaUser'));
    }
}
