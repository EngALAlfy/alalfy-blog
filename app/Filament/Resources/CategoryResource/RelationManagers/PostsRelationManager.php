<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Filament\Resources\PostResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return PostResource::form($form);
    }

    public function table(Table $table): Table
    {
        return PostResource::table($table)
            ->recordTitleAttribute('title')
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return PostResource::infolist($infolist);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
