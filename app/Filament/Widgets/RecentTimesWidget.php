<?php

namespace App\Filament\Widgets;

use App\Time;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentTimesWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Recently Completed Sessions';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Time::query()
                    ->with(['task', 'block', 'chemical', 'user'])
                    ->whereNotNull('end_time')
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Operator')
                    ->sortable(),
                Tables\Columns\TextColumn::make('task.description')
                    ->label('Task')
                    ->searchable(),
                Tables\Columns\TextColumn::make('block.block_name')
                    ->label('Block'),
                Tables\Columns\TextColumn::make('chemical.trade_name')
                    ->label('Chemical'),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Start'),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->fontFamily('mono')
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('total_liquid')
                    ->label('Used (L)'),
            ])
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25])
            ->defaultSort('id', 'desc');
    }
}
