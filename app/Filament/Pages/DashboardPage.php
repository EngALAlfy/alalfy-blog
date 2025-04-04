<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard;

class DashboardPage extends Dashboard
{
    public static function getNavigationGroup(): ?string
    {
        return __('Home');
    }
}
