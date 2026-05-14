<?php

namespace App\Filament\Resources\Grupos;

use App\Filament\Resources\Grupos\Pages;
use App\Models\Grupo;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class GrupoResource extends Resource
{
    protected static ?string $model = Grupo::class;
    protected static ?string $navigationLabel = 'Grupos';
    protected static ?string $modelLabel = 'Grupo';
    

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('grado_id')
                ->label('Grado')
                ->required()
                ->relationship('grado', 'nombre')
                ->preload(),

            TextInput::make('grupo')
                ->label('Grupo')
                ->required()
                ->placeholder('Ej: A, B, C')
                ->maxLength(10),

            TextInput::make('maestro')
                ->label('Nombre del Maestro/a')
                ->maxLength(255),

            TextInput::make('total_alumnos')
                ->label('Total de Alumnos')
                ->numeric()
                ->default(0),

            Toggle::make('activo')
                ->label('Activo')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('grado.nombre')
                ->label('Grado')
                ->sortable(),

            Tables\Columns\TextColumn::make('grupo')
                ->label('Grupo')
                ->sortable(),

            Tables\Columns\TextColumn::make('maestro')
                ->label('Maestro/a')
                ->searchable(),

            Tables\Columns\TextColumn::make('total_alumnos')
                ->label('Total Alumnos')
                ->sortable(),

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
            'index' => Pages\ListGrupos::route('/'),
            'create' => Pages\CreateGrupo::route('/create'),
            'edit' => Pages\EditGrupo::route('/{record}/edit'),
        ];
    }
}