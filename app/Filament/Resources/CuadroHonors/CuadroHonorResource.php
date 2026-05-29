<?php

namespace App\Filament\Resources\CuadroHonors;

use App\Filament\Resources\CuadroHonors\Pages;
use App\Models\CuadroHonor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class CuadroHonorResource extends Resource
{
    protected static ?string $model = CuadroHonor::class;
    protected static ?string $navigationLabel = 'Cuadro de Honor';
    protected static ?string $modelLabel = 'Alumno Destacado';
    protected static ?int $navigationSort = 4;
    public static function getNavigationGroup(): ?string
{
    return 'Página Web';
}

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nombre_alumno')
                ->label('Nombre del Alumno')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            TextInput::make('grado')
                ->label('Grado')
                ->required()
                ->maxLength(100),

            TextInput::make('grupo')
                ->label('Grupo')
                ->required()
                ->maxLength(50),

            TextInput::make('periodo')
                ->label('Período')
                ->required()
                ->placeholder('Ej: 2024-2025')
                ->maxLength(100),

            TextInput::make('orden')
                ->label('Orden')
                ->numeric()
                ->default(0),

            Textarea::make('motivo')
                ->label('Motivo / Logro')
                ->rows(3)
                ->columnSpanFull(),

            FileUpload::make('imagen')
                ->label('Imagen')
                ->image()
                ->disk('public')
                ->directory('galeria')
                ->required()
                ->maxSize(2048)
                ->columnSpanFull(),

            Toggle::make('activo')
                ->label('Visible en el sitio')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('foto')
                ->label('Foto')
                ->circular(),

            Tables\Columns\TextColumn::make('nombre_alumno')
                ->label('Alumno')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('grado')
                ->label('Grado'),

            Tables\Columns\TextColumn::make('grupo')
                ->label('Grupo'),

            Tables\Columns\TextColumn::make('periodo')
                ->label('Período')
                ->sortable(),

            Tables\Columns\IconColumn::make('activo')
                ->label('Visible')
                ->boolean(),
        ])
        ->defaultSort('orden', 'asc')
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
            'index' => Pages\ListCuadroHonors::route('/'),
            'create' => Pages\CreateCuadroHonor::route('/create'),
            'edit' => Pages\EditCuadroHonor::route('/{record}/edit'),
        ];
    }
}