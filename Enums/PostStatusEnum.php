<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum PostStatusEnum : string implements HasColor , HasLabel
{
    case DRAFT = "draft";
    case ACTIVE = "active";
    case ARCHIVED = "archived";

    public function getColor(): string|array|null
    {
        return match ($this){
            self::DRAFT => Color::Gray,
            self::ACTIVE => "success",
            self::ARCHIVED => "danger",
        };
    }

    public function getLabel(): ?string
    {
        return Str::headline($this->value);
    }
}
