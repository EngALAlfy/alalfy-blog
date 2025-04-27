<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Hash;

class AuthorProfilePage extends Page
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.author-profile-page';
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public ?array $data = ["name", "email"];

    public static function getNavigationLabel(): string
    {
        return __("Author Profile");
    }

    public function getBreadcrumbs(): array
    {
        return [
            "Author",
            "Profile"
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return __('Author Profile');
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __('Update your vendor profile information');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    protected static ?int $navigationSort = 2;

    public function mount(): void
    {
        $this->form->fill(auth()->user()->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Profile Information'))
                    ->description(__('Update your account\'s profile information and email address.'))
                    ->grow()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make("avatar")->collection("avatar")
                            ->imageEditor()->avatar(),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label(__('Name')),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(User::class, 'email', ignorable: auth()->user(), ignoreRecord: true)
                            ->label(__('Email')),
                    ])->columnSpan(1),

                Section::make(__('Update Password'))
                    ->description(__('Ensure your account is using a long, random password to stay secure.'))
                    ->grow()
                    ->schema([
                        TextInput::make('current_password')
                            ->password()
                            ->revealable()
                            ->label(__('Current Password')),

                        TextInput::make('new_password')
                            ->password()
                            ->revealable()
                            ->confirmed()
                            ->dehydrated(fn($state): bool => filled($state))
                            ->dehydrateStateUsing(fn($state): string => Hash::make($state)),

                        TextInput::make('new_password_confirmation')
                            ->password()
                            ->revealable()
                            ->label(__('Confirm Password')),
                    ])
                    ->columns(1)->columnSpan(1),
            ])
            ->columns(2)
            ->statePath("data")
            ->model(auth()->user());
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Save'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = auth()->user();

        // Validate current password if trying to change password
        if ($data['current_password']) {
            if (!Hash::check($data['current_password'], $user->password)) {
                Notification::make()
                    ->title(__('Current password is incorrect'))
                    ->danger()
                    ->send();

                return;
            }
        }

        // Update user data
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        // Only update password if a new one was provided
        if (isset($data['new_password'])) {
            $updateData['password'] = $data['new_password'];
        }

        $user->update($updateData);

        Notification::make()
            ->title(__('Profile updated successfully'))
            ->success()
            ->send();
    }
}
