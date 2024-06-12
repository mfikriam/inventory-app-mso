<?php

namespace App\Filament\Pages;

use App\Models\ExitItem;
use App\Models\Witel;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CalculationMethod extends Page
{
    protected static ?string $title = 'Metode DRP';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.calculation-method';
    protected static function shouldRegisterNavigation():bool
    {
        return false;
    }

    public bool $display_table_calculation = false;
    public $allMounths = [];
    public $locationID;
    public $yearID;

    public function mount()
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Fieldset::make('Data Periode & Wilayah')->schema([
                Grid::make([
                    'sm' => 1,
                    'md' => 2,
                ])->schema([Select::make('location_id')
                    ->label('Pilih Lokasi')->options(Witel::all()->pluck('name', 'id'))
                    ->required(), Select::make('year_id')
                    ->label('Pilih Tahun')->options(ExitItem::select(DB::raw('YEAR(date_out_date) as year'))->pluck('year', 'year'))
                    ->required(),])
            ])];
    }

    public function prediction()
    {
        $data = $this->form->getState();
        $allMonths = collect([]);
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now()->endOfYear();
        while ($startDate->lte($endDate)) {
            $allMonths->push($startDate->format('m'));
            $startDate->addMonth();
        }
        $this->allMounths = $allMonths;
        $this->locationID = $data['location_id'];
        $this->yearID = $data['year_id'];
        $this->display_table_calculation = true;
    }
}
