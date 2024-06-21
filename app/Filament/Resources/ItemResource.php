<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Models\Item;
use App\Models\Category;
use App\Models\Subcategory;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;
    protected static ?string $navigationGroup = 'Upravljanje jelovnikom';
    protected static ?string $navigationLabel = 'Stavke jelovnika';
    protected static ?string $navigationIcon = 'heroicon-s-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(fn (callable $set) => $set('subcategory_id', null)),

                        Forms\Components\Select::make('subcategory_id')
                            ->label('Subcategory')
                            ->options(function (callable $get) {
                                $categoryId = $get('category_id');
                                if ($categoryId) {
                                    return Subcategory::where('category_id', $categoryId)->pluck('name', 'id');
                                }
                                return Subcategory::all()->pluck('name', 'id');
                            })
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description'),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric(),
                        Select::make('type')
                            ->label('Type')
                            ->options([
                                'food' => 'Food',
                                'drink' => 'Drink',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('comment'),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('item-images'),
                    ])
                    ->columns(2), // This makes the form fields appear in two columns
                Forms\Components\Section::make('Options')
                    ->schema([
                        Forms\Components\HasManyRepeater::make('options')
                            ->relationship('options')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Select::make('type')
                                    ->label('Type')
                                    ->options([
                                        'add' => 'Add',
                                        'remove' => 'Remove',
                                    ])
                                    ->required(),
                                Forms\Components\HasManyRepeater::make('prices')
                                    ->relationship('price')
                                    ->schema([
                                        Forms\Components\TextInput::make('amount')
                                            ->numeric()
                                            ->required(),
                                    ])
                            ])
                    ])
                    ->collapsible()
                    ->collapsed(), // This makes the section collapsed by default // This makes the section collapsible
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('subcategory.category.name')->label('Category'),
                Tables\Columns\TextColumn::make('subcategory.name')->label('Subcategory'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('type'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
