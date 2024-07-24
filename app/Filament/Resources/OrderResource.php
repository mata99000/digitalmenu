<?php
// app/Filament/Resources/OrderResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Models\Order;
use App\Models\Waiter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Carbon\Carbon;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('waiter_id')
                    ->relationship('waiter', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                        'pending' => 'Pending',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('created_at')
                    ->label('Created At'),
                Forms\Components\DatePicker::make('updated_at')
                    ->label('Delivered At'),
                Forms\Components\Repeater::make('orderedItems')
                    ->relationship('orderedItems')
                    ->schema([
                        Forms\Components\Grid::make(2)
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
                            ]),
                    ])
                    ->columns(2)
                    ->label('Ordered Items')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Order Number'),
                TextColumn::make('waiter.name')
                    ->label('Waiter')
                    ->searchable(),
                TextColumn::make('total')
                    ->label('Total'),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('j.n.y \a\t H:i')),
                TextColumn::make('updated_at')
                    ->label('Delivered At')
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('j.n.y \a\t H:i')),
                TextColumn::make('duration')
                    ->label('Duration')
                    ->formatStateUsing(function ($record) {
                        $created_at = Carbon::parse($record->created_at);
                        $updated_at = Carbon::parse($record->updated_at);
                        return $created_at && $updated_at ? $created_at->diffForHumans($updated_at, true) : 'N/A';
                    })
                    ->getStateUsing(function ($record) {
                        $created_at = Carbon::parse($record->created_at);
                        $updated_at = Carbon::parse($record->updated_at);
                        return $created_at && $updated_at ? $created_at->diff($updated_at)->format('%h:%i:%s') : 'N/A';
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        $color = match ($state) {
                            'completed' => 'green',
                            'canceled' => 'red',
                            'in_progress' => 'orange',
                            default => 'gray',
                        };
                        return "<span style='color: {$color}; font-weight: bold;'>{$state}</span>";
                    })
                    ->html(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                        'in_progress' => 'In Progress',
                    ]),
                SelectFilter::make('waiter_id')
                    ->relationship('waiter', 'name')
                    ->label('Waiter'),
                Filter::make('created_at')
                    ->label('Date')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn ($query, $date) => $query->whereDate('created_at', '<=', $date));
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make('view')
                    ->label('View Order'),
            ])
            ->bulkActions([
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
        ];
    }
}
