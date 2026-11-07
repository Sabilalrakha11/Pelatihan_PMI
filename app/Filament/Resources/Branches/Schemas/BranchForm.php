<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                    
                Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                    
                TextInput::make('telepon')
                    ->tel(),
                    
                // Komponen Google Maps
Map::make('titik_peta')
    ->label('Pilih Lokasi pada Google Maps')
    ->columnSpanFull()
    ->defaultLocation([-6.2441, 106.7992]) // Titik awal (Blok M)
    ->defaultZoom(12) // Tambahkan zoom level wajib
    ->geolocate() // Membantu memuat peta awal
    ->geolocateOnLoad(true) // Memaksa peta mencari lokasi saat dimuat
    ->dehydrated(false)
    ->reactive()
    ->afterStateUpdated(function ($state, callable $set) {
        if (is_array($state)) {
            $set('latitude', $state['lat'] ?? null);
            $set('longitude', $state['lng'] ?? null);
        }
    }),
                    
                TextInput::make('latitude')
                    ->required()
                    ->numeric()
                    ->readOnly(),
                    
                TextInput::make('longitude')
                    ->required()
                    ->numeric()
                    ->readOnly(),
            ]);
    }
}