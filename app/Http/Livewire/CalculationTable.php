<?php

namespace App\Http\Livewire;

use App\Models\ExitItem;
use App\Models\Witel;
use Livewire\Component;

class CalculationTable extends Component
{
    public $allMounths = null;
    public $locationID = null;
    public $yearID = null;

    public function mount($allMounths, $locationID, $yearID)
    {
        $this->allMounths = $allMounths;
        $this->locationID = $locationID;
        $this->yearID = $yearID;
    }

    public function render()
    {
        $allMounths = $this->allMounths;
        $exitsItemCountByMounths = ExitItem::with('');
        $witel = Witel::with(['datels.sites.exit_items'])->find($this->locationID);
        $exitItemCountByMonth = $witel->datels->flatMap(function ($datel) {
            return $datel->sites->flatMap(function ($site) {
                return $site->exit_items->filter(function ($item) {
                    return date('Y', strtotime($item['date_out_date'])) == $this->yearID;
                });
            });
        })->groupBy(function ($exitItem) {
            $dateOut = \Carbon\Carbon::parse($exitItem->date_out_date);
            return $dateOut->format('m');
        })->map(function ($groupedExitItems) {
            return $groupedExitItems->count();
        });
        $exitItemCountByMonth = $allMounths->mapWithKeys(function ($month) use ($exitItemCountByMonth) {
            return [$month => $exitItemCountByMonth->get($month, 0)];
        });
        return view('livewire.calculation-table', compact('allMounths', 'exitItemCountByMonth', 'witel'));
    }
}
