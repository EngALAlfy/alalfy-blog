<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Traits\RedirectToTableTrait;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    use RedirectToTableTrait;

    protected static string $resource = CategoryResource::class;
}
