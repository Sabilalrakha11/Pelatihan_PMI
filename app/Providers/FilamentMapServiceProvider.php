use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;

public function boot(): void
{
    FilamentView::registerRenderHook(
        'panels::body.end',
        fn (): string => Blade::render('<script src="https://maps.googleapis.com/maps/api/js?key=' . env('FILAMENT_GOOGLE_MAPS_KEY') . '" async defer></script>')
    );
}<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;

class FilamentMapServiceProvider extends ServiceProvider
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
    public function boot(): void
    {
        // Menyuntikkan script Google Maps API ke seluruh halaman panel Filament
        FilamentView::registerRenderHook(
            'panels::body.end',
            fn (): string => Blade::render('<script src="https://maps.googleapis.com/maps/api/js?key=' . env('FILAMENT_GOOGLE_MAPS_KEY') . '" async defer></script>')
        );
    }
}