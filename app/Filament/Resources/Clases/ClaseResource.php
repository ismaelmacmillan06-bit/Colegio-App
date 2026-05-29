<?php

namespace App\Filament\Resources\Clases;

use App\Filament\Resources\Clases\Pages;
use App\Models\Clase;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ClaseResource extends Resource
{
    protected static ?string $model = Clase::class;
    protected static ?string $navigationLabel = 'Clases';
    protected static ?string $modelLabel = 'Clase';
    protected static ?int $navigationSort = 1;
    public static function getNavigationGroup(): ?string
{
    return 'Escuela';
}

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nombre')
                ->label('Nombre de la Clase')
                ->required()
                ->placeholder('Ej: 1°A Primaria, Kinder 3, 1° Secundaria A')
                ->maxLength(255)
                ->columnSpanFull(),

            Select::make('nivel')
                ->label('Nivel')
                ->options([
                    'Preescolar' => 'Preescolar',
                    'Primaria' => 'Primaria',
                    'Secundaria' => 'Secundaria',
                ]),

            TextInput::make('capacidad')
                ->label('Capacidad')
                ->numeric()
                ->default(30),

            Toggle::make('activo')
                ->label('Activo')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nombre')
                ->label('Clase')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('nivel')
                ->label('Nivel')
                ->sortable(),

            Tables\Columns\TextColumn::make('capacidad')
                ->label('Capacidad'),

            Tables\Columns\TextColumn::make('alumnos_count')
                ->label('Alumnos')
                ->counts('alumnos'),

            Tables\Columns\IconColumn::make('activo')
                ->label('Activo')
                ->boolean(),
        ])
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
            'index' => Pages\ListClases::route('/'),
            'create' => Pages\CreateClase::route('/create'),
            'edit' => Pages\EditClase::route('/{record}/edit'),
        ];
    }
}