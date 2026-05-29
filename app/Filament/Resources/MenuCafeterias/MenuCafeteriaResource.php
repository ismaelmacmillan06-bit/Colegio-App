<?php

namespace App\Filament\Resources\MenuCafeterias;

use App\Filament\Resources\MenuCafeterias\Pages;
use App\Models\MenuCafeteria;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class MenuCafeteriaResource extends Resource
{
    protected static ?string $model = MenuCafeteria::class;
    protected static ?string $navigationLabel = 'Menú Cafetería';
    protected static ?string $modelLabel = 'Menú del Día';
    protected static ?int $navigationSort = 5;
    public static function getNavigationGroup(): ?string
{
    return 'Página Web';
}
    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('dia')
                ->label('Día')
                ->required()
                ->options([
                    'Lunes' => 'Lunes',
                    'Martes' => 'Martes',
                    'Miércoles' => 'Miércoles',
                    'Jueves' => 'Jueves',
                    'Viernes' => 'Viernes',
                ]),

            Toggle::make('activo')
                ->label('Visible en el sitio')
                ->default(true),

            TextInput::make('platillo_principal')
                ->label('Platillo Principal')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            TextInput::make('sopa')
                ->label('Sopa / Entrada')
                ->maxLength(255),

            TextInput::make('bebida')
                ->label('Bebida')
                ->maxLength(255),

            TextInput::make('fruta')
                ->label('Fruta / Postre')
                ->maxLength(255),

            TextInput::make('precio')
                ->label('Precio')
                ->numeric()
                ->prefix('$')
                ->maxLength(10),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('dia')
                ->label('Día')
                ->sortable(),

            Tables\Columns\TextColumn::make('platillo_principal')
                ->label('Platillo Principal')
                ->searchable(),

            Tables\Columns\TextColumn::make('sopa')
                ->label('Sopa'),

            Tables\Columns\TextColumn::make('bebida')
                ->label('Bebida'),

            Tables\Columns\TextColumn::make('precio')
                ->label('Precio')
                ->money('MXN')
                ->sortable(),

            Tables\Columns\IconColumn::make('activo')
                ->label('Visible')
                ->boolean(),
        ])
        ->defaultSort('dia', 'asc')
        ->actions([
            \Filament\Actions\EditAction::make(),
            \Filament\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            \Filament\Actions\BulkActionGroup::make([
                \Filament\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuCafeterias::route('/'),
            'create' => Pages\CreateMenuCafeteria::route('/create'),
            'edit' => Pages\EditMenuCafeteria::route('/{record}/edit'),
        ];
    }
}