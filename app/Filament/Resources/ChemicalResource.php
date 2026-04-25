<?php

namespace App\Filament\Resources;

use App\Chemical;
use App\Filament\Resources\ChemicalResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class ChemicalResource extends Resource
{
    protected static ?string $model = Chemical::class;
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Spray Management';
    protected static ?string $navigationLabel = 'Chemicals';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('chem_type')
                ->label('Chemical Type')
                ->options(fn () => DB::table('chemtypes')->pluck('name', 'id'))
                ->required(),
            Forms\Components\TextInput::make('trade_name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('components')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('rates')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('withhold_period')
                ->label('Withhold Period (days)')
                ->numeric()
                ->required(),
            Forms\Components\TextInput::make('pest_disease')
                ->label('Pest / Disease')
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('chemType.name')
                    ->label('Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('trade_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('components')
                    ->limit(40),
                Tables\Columns\TextColumn::make('rates'),
                Tables\Columns\TextColumn::make('withhold_period')
                    ->label('Withhold (days)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pest_disease')
                    ->label('Pest / Disease')
                    ->limit(40),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index'  => Pages\ListChemicals::route('/'),
            'create' => Pages\CreateChemical::route('/create'),
            'edit'   => Pages\EditChemical::route('/{record}/edit'),
        ];
    }
}
