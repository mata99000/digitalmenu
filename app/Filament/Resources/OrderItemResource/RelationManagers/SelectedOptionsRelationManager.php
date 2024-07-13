<?php
namespace App\Filament\Resources\OrderItemResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class SelectedOptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'selectedOptions';

    protected static ?string $recordTitleAttribute = 'option_name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('option_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('option_value')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('option_name'),
                TextColumn::make('option_value'),
            ])
            ->filters([
                // Dodajte filtere po potrebi
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
