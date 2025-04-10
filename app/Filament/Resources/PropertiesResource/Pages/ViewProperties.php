<?php

namespace App\Filament\Resources\PropertiesResource\Pages;

use App\Filament\Resources\PropertiesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProperties extends ViewRecord
{
    protected static string $resource = PropertiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
