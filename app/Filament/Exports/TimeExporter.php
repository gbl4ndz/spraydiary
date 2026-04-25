<?php

namespace App\Filament\Exports;

use App\Time;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TimeExporter extends Exporter
{
    protected static ?string $model = Time::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('user.name')->label('Sprayed By'),
            ExportColumn::make('task.description')->label('Task'),
            ExportColumn::make('block.block_name')->label('Block'),
            ExportColumn::make('chemical.trade_name')->label('Chemical'),
            ExportColumn::make('start_time')->label('Start Time'),
            ExportColumn::make('end_time')->label('End Time'),
            ExportColumn::make('duration')->label('Duration'),
            ExportColumn::make('tank_capacity')->label('Tank (L)'),
            ExportColumn::make('total_liquid')->label('Used (L)'),
            ExportColumn::make('is_fruiting')->label('Fruiting'),
            ExportColumn::make('created_at')->label('Created At'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your time records export has completed and '
            . number_format($export->successful_rows) . ' '
            . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' '
                . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
