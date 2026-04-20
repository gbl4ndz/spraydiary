<?php

namespace App\Filament\Resources;

use App\Time;
use App\Filament\Resources\TimeResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TimeResource extends Resource
{
    protected static ?string $model = Time::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Spray Management';
    protected static ?string $navigationLabel = 'Time Records';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Sprayed By')
                    ->sortable(),
                Tables\Columns\TextColumn::make('task.description')
                    ->label('Task')
                    ->searchable(),
                Tables\Columns\TextColumn::make('block.block_name')
                    ->label('Block'),
                Tables\Columns\TextColumn::make('chemical.trade_name')
                    ->label('Chemical'),
                Tables\Columns\TextColumn::make('start_time')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time'),
                Tables\Columns\TextColumn::make('duration'),
                Tables\Columns\TextColumn::make('tank_capacity')
                    ->label('Tank (L)'),
                Tables\Columns\TextColumn::make('total_liquid')
                    ->label('Used (L)'),
                Tables\Columns\IconColumn::make('is_fruiting')
                    ->label('Fruiting')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->is_fruiting === '1'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimes::route('/'),
        ];
    }
}
