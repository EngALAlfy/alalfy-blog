<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Traits\RedirectToTableTrait;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    use RedirectToTableTrait;

    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['author_id'] = auth()->id();

        if(isset($data["status"])){
            $data['status_at'] = now();
        }

        return $data;
    }
}
