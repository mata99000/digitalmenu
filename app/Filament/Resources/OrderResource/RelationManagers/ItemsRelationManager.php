<?php

// app/Filament/Resources/OrderResource/RelationManagers/ItemsRelationManager.php
namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms;
use Filament\Tables;
use App\Models\Item;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderedItems';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('item_id')
                    ->relationship('item', 'name')
                    ->searchable()
                    ->required()
                    ->label('Item Name'),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->label('Quantity'),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->label('Price'),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item.name')
                    ->label('Item Name'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price'),
            ]);
    }
}
