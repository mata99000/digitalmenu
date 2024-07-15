<?php
namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Carbon\Carbon;
use Filament\Tables\Actions\ViewAction;
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
                        'in_progress' => 'In Progress',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('deliveried_at')
                    ->label('Delivered At'),
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
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('j.n.y \a\t H:i');
                    }),
                TextColumn::make('deliveried_at')
                    ->label('Delivered At')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('j.n.y \a\t H:i');
                    }),
                TextColumn::make('duration')
                    ->label('Duration')
                    ->formatStateUsing(function ($record) {
                        $created_at = Carbon::parse($record->created_at);
                        $updated_at = Carbon::parse($record->updated_at);

                        if ($created_at && $updated_at) {
                            $duration = $created_at->diffForHumans($updated_at, true);
                            return $duration;
                        }

                        return 'N/A';
                    })
                    ->getStateUsing(function ($record) {
                        $created_at = Carbon::parse($record->created_at);
                        $updated_at = Carbon::parse($record->updated_at);

                        if ($created_at && $updated_at) {
                            return $created_at->diff($updated_at)->format('%h:%i:%s');
                        }

                        return 'N/A';
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
            ])
            ->actions([
                ViewAction::make()
                    ->label('View Order')
                    ->modalHeading('Order Details')
                    ->modalButton('Close')
                    ->modalWidth('xl')
                    ->modalContent(function ($record) {
                        $items = $record->items; // Assuming 'items' is the relationship name
                        return view('components.order-details-modal', ['order' => $record, 'items' => $items]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
