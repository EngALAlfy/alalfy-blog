<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Schmeits\FilamentCharacterCounter\Forms\Components\RichEditor;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?int $navigationSort = 1;

    /**
     * @return string
     */
    public static function getNavigationLabel(): string
    {
        return __('Categories');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Content');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Group::make([
                        TextInput::make('name')
                            ->required()
                            ->characterLimit(255)
                            ->label('Category Name'),

                        Textarea::make('short_description')
                            ->maxLength(500)
                            ->label('Short Description'),
                    ]),

                    Group::make([
                        SpatieMediaLibraryFileUpload::make("banner")
                            ->collection("banner")
                            ->label('Banner Image'),

                        SpatieTagsInput::make("tags"),
                    ]),

                    RichEditor::make('description')
                        ->required()
                        ->label('Detailed Description')
                        ->columnSpanFull(),


                ])->columns(2),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Category Name')
                    ->description(function (Model $record): ?string {
                        $words = explode(' ', $record->short_description);
                        $shortened = implode(' ', array_slice($words, 0, 10));
                        return count($words) > 10 ? $shortened . '...' : $shortened;
                    })
                    ->tooltip(function (Model $record): ?string {
                        return count(explode(' ', $record->short_description)) <= 10 ? null : $record->short_description;
                    }),
                Tables\Columns\SpatieMediaLibraryImageColumn::make("banner")
                    ->collection("banner")
                    ->label('Banner'),
                Tables\Columns\TextColumn::make("created_at")->label('Created At'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PostsRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')
                    ->label('Category Name'),

                TextEntry::make('short_description')
                    ->label('Short Description'),

                TextEntry::make('description')
                    ->label('Detailed Description')
                    ->html(),

                SpatieMediaLibraryImageEntry::make('banner')
                    ->collection('banner')
                    ->label('Banner Image'),

                SpatieTagsEntry::make("tags"),

                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->label('Last update at')
                    ->dateTime(),
            ]);
    }

}
