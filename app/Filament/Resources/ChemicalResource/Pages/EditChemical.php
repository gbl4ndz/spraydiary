<?php

namespace App\Filament\Resources\ChemicalResource\Pages;

use App\Filament\Resources\ChemicalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChemical extends EditRecord
{
    protected static string $resource = ChemicalResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
