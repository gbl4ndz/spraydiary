<?php

namespace App\Filament\Resources\ShedResource\Pages;

use App\Filament\Resources\ShedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShed extends EditRecord
{
    protected static string $resource = ShedResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
