<?php

namespace App\Providers;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\ServiceProvider;
use App\Filament\Resources\UserResource;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(User $user): void
    {
        if ($user->hasRole('ADMIN', 'SUPER ADMIN')) {
            Filament::serving(function () {
                Filament::registerUserMenuItems([
                    MenuItem::make()
                        ->label('Settings')
                        ->url(UserResource::getUrl())
                        ->icon('heroicon-o-cog-6-tooth'),
                ]);
            });
        }
    }
}
