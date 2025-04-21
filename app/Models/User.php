<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use TomatoPHP\FilamentLanguageSwitcher\Traits\InteractsWithLanguages;

class User extends Authenticatable implements HasAvatar , FilamentUser , HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;
    use InteractsWithMedia;
    use TwoFactorAuthenticatable;
    use InteractsWithLanguages;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() == "admin";
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getFirstMediaUrl("avatar" , "avatar");
    }

    public function registerMediaCollections(): void
    {
        $fallbackText = 'U';
        if (!empty($this->title)) {
            $fallbackText = preg_replace('/\b(\w)/', '$1', ucwords($this->title));
            $fallbackText = preg_replace('/[^A-Z]/', '', $fallbackText);
        }

        $this->addMediaCollection('avatar')->useFallbackUrl("https://placehold.co/150x150?text=" . $fallbackText);
    }
}
