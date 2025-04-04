<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class MyProfilePage extends \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage
{
    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }
}
