<?php

namespace App\Providers;

use F9Web\Health\Checks\OpCacheCheck;
use Filament\Forms\Components\Select;
use Illuminate\Support\ServiceProvider;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Spatie\CpuLoadHealthCheck\CpuLoadCheck;
use Spatie\Health\Checks\Checks\BackupsCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DatabaseConnectionCountCheck;
use Spatie\Health\Checks\Checks\DatabaseSizeCheck;
use Spatie\Health\Checks\Checks\DatabaseTableSizeCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\MeiliSearchCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;
use Spatie\SecurityAdvisoriesHealthCheck\SecurityAdvisoriesCheck;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Health::checks([
            OptimizedAppCheck::new(),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
            CacheCheck::new(),
            BackupsCheck::new(),
            DatabaseTableSizeCheck::new(),
            DatabaseCheck::new(),
            DatabaseConnectionCountCheck::new(),
            DatabaseSizeCheck::new(),
            PingCheck::new()->url("https://google.com")->name("google ping"),
            PingCheck::new()->url(config("app.url"))->name("app ping"),
            CpuLoadCheck::new(),
            UsedDiskSpaceCheck::new(),
            OpCacheCheck::new(),
            SecurityAdvisoriesCheck::new(),
        ]);

        Select::configureUsing(function ($select){
            $select->native(false);
        });
    }
}
