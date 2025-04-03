<?php

namespace App\Filament\Resources;


class UserResource extends \TomatoPHP\FilamentUsers\Resources\UserResource
{
    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }
}
