<?php

namespace App\Filament\Resources;

use App\Shed;
use App\Filament\Resources\ShedResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShedResource extends Resource
{
    protected static ?string $model = Shed::class;
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Greenhouse';
    protected static ?string $navigationLabel = 'Sheds';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('shed_name')
                ->label('Shed Name')
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('shed_name')
                    ->label('Shed Name')
                    ->searchable()
                    ->sortable(),
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
            'index'  => Pages\ListSheds::route('/'),
            'create' => Pages\CreateShed::route('/create'),
            'edit'   => Pages\EditShed::route('/{record}/edit'),
        ];
    }
}
