<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{

    protected static string $view = 'filament.pages.dashboard';

    protected function getWidgets(): array
    {
        // return default widgets
        return Filament::getWidgets();
    }
}
