<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Traits\RedirectToTableTrait;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    use RedirectToTableTrait;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['author_id'] = auth()->id();

        if(isset($data["status"]) && $data["status"] != $this->getRecord()->status){
            $data['status_at'] = now();
        }

        return $data;
    }
}
