<?php

namespace App\Providers\Filament;

use App\Filament\Pages\MyProfilePage;
use BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Saasykit\FilamentOops\FilamentOopsPlugin;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;
use TomatoPHP\FilamentApi\FilamentAPIPlugin;
use TomatoPHP\FilamentArtisan\FilamentArtisanPlugin;
use TomatoPHP\FilamentDeveloperGate\FilamentDeveloperGatePlugin;
use TomatoPHP\FilamentLanguageSwitcher\FilamentLanguageSwitcherPlugin;
use TomatoPHP\FilamentTranslations\FilamentTranslationsPlugin;
use TomatoPHP\FilamentUsers\FilamentUsersPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandLogo(asset('logo.png'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugin(FilamentExceptionsPlugin::make())
            ->plugin(FilamentSpatieLaravelHealthPlugin::make())
            ->plugin(FilamentUsersPlugin::make())
            ->plugin(FilamentOopsPlugin::make())
            ->plugin(FilamentLanguageSwitcherPlugin::make())
            ->plugin(FilamentArtisanPlugin::make())
            ->plugin(FilamentTranslationsPlugin::make()->allowCreate())
            ->plugin(FilamentDeveloperGatePlugin::make())
            ->plugin(FilamentShieldPlugin::make())
            ->plugin(FilamentAPIPlugin::make())
            ->plugin(
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterNavigation: true,
                    )
                    ->customMyProfilePage(MyProfilePage::class)
                    ->enableTwoFactorAuthentication(false)
            )
            ->authMiddleware([
                Authenticate::class,
            ])
            ->bootUsing(function () {
            })
            ->navigationGroups([
                NavigationGroup::make(__("Home")),
                NavigationGroup::make(__("Content")),
                NavigationGroup::make(__("Settings")),
            ]);
    }
}
