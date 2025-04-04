<?php

namespace App\Traits;

trait RedirectToTableTrait
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
