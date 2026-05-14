<?php

namespace App\Filament\Resources\Grados;

use App\Filament\Resources\Grados\Pages;
use App\Models\Grado;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class GradoResource extends Resource
{
    protected static ?string $model = Grado::class;
    protected static ?string $navigationLabel = 'Grados';
    protected static ?string $modelLabel = 'Grado';
    

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nombre')
                ->label('Nombre del Grado')
                ->required()
                ->placeholder('Ej: 1°, 2°, 3°')
                ->maxLength(50),

            Select::make('nivel')
                ->label('Nivel')
                ->required()
                ->options([
                    'Preescolar' => 'Preescolar',
                    'Primaria' => 'Primaria',
                    'Secundaria' => 'Secundaria',
                ]),

            Toggle::make('activo')
                ->label('Activo')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nombre')
                ->label('Grado')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('nivel')
                ->label('Nivel')
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
            'index' => Pages\ListGrados::route('/'),
            'create' => Pages\CreateGrado::route('/create'),
            'edit' => Pages\EditGrado::route('/{record}/edit'),
        ];
    }
}