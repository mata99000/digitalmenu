<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Button;
class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('inventory')
                    ->schema([
                        TextInput::make('name')
                            ->label('Product Name')
                            ->disabled(),
                        TextInput::make('current_quantity')
                            ->label('Current Quantity')
                            ->disabled(),
                        TextInput::make('new_quantity')
                            ->label('New Quantity')
                            ->numeric()
                            ->extraAttributes(['class' => 'focus-input'])
                    ])
                    ->disableItemDeletion()
                    ->disableItemCreation()
                    ->disableItemMovement()
                    ->columns(1)
            ]);
    }
    public function saveQuantities(array $data): void
    {
        foreach ($data['inventory'] as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                $product->current_quantity += $item['new_quantity'];
                $product->save();
            }
        }
    }
    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('id')->label('ID'),
            TextColumn::make('name')->label('Name')->searchable()->sortable(),
            TextColumn::make('description')->label('Description')->limit(50),
            TextColumn::make('unit')->label('Unit'),
            TextColumn::make('quantity')->label('Quantity')->sortable(),
            TextColumn::make('created_at')->label('Created At')->dateTime()->sortable(),
        ])
        ->filters([
            // Definišite filtere ako je potrebno
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
