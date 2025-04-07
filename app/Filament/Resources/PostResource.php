<?php

namespace App\Filament\Resources;

use App\Actions\UpdateStatusAction;
use App\Actions\UpdateStatusBulkAction;
use App\Enums\PostStatusEnum;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Model;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 2;

    /**
     * @return string
     */
    public static function getNavigationLabel(): string
    {
        return __('Posts');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Content');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Group::make([
                        \Schmeits\FilamentCharacterCounter\Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->label('Title'),

                        \Schmeits\FilamentCharacterCounter\Forms\Components\Textarea::make('short_description')
                            ->maxLength(500)
                            ->label('Short Description'),

                        Forms\Components\Select::make('status')
                            ->required()
                            ->options(PostStatusEnum::class)
                            ->label('Status'),

                        Forms\Components\Select::make('category_id')
                            ->required()
                            ->relationship("category", "name")
                            ->searchable()
                            ->preload()
                            ->label('Category'),

                        Forms\Components\SpatieTagsInput::make("tags"),
                    ]),

                    Forms\Components\Group::make([

                        SpatieMediaLibraryFileUpload::make("banner")
                            ->collection("banner")
                            ->label('Banner Image'),

                        \Schmeits\FilamentCharacterCounter\Forms\Components\RichEditor::make('description')
                            ->required()
                            ->label('Description'),

                    ]),

                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Title')
                    ->description(function (Model $record): ?string {
                        $words = explode(' ', $record->short_description);
                        $shortened = implode(' ', array_slice($words, 0, 10));
                        return count($words) > 10 ? $shortened . '...' : $shortened;
                    })
                    ->tooltip(function (Model $record): ?string {
                        return count(explode(' ', $record->short_description)) <= 10 ? null : $record->short_description;
                    }),
                TextColumn::make('status')->label('Status')->badge(),
                TextColumn::make('author.name')->label('Author'),
                TextColumn::make('category.name')->label('Category'),
                SpatieMediaLibraryImageColumn::make("banner")->collection("banner")->label('Banner'),
                TextColumn::make("created_at"),
            ])
            ->filters([
                //
            ])
            ->actions([
                UpdateStatusAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    UpdateStatusBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('title')->label('Title'),
                TextEntry::make('short_description')->label('Short Description'),
                TextEntry::make('description')->label('Description')->html(),
                SpatieTagsEntry::make('tags'),
                TextEntry::make('status')->label('Status')->badge(),
                TextEntry::make('status_at')->label('Status At')->dateTime(),
                TextEntry::make('author.name')->label('Author'),
                TextEntry::make('category.name')->label('Category'),
                SpatieMediaLibraryImageEntry::make('banner')->collection('banner')->label('Banner Image'),
            ]);
    }
}
