<?php

namespace App\Filament\Resources\Galerias;

use App\Filament\Resources\Galerias\Pages;
use App\Models\Galeria;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class GaleriaResource extends Resource
{
    protected static ?string $model = Galeria::class;
    protected static ?string $navigationLabel = 'Galería';
    protected static ?string $modelLabel = 'Foto';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('titulo')
                ->label('Título')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            Textarea::make('descripcion')
                ->label('Descripción')
                ->rows(2)
                ->columnSpanFull(),

            FileUpload::make('imagen')
            ->label('Imagen')
            ->image()
            ->disk('public')
            ->directory('galeria')
            ->required()
             ->maxSize(2048)
             ->columnSpanFull(),

            TextInput::make('orden')
                ->label('Orden')
                ->numeric()
                ->default(0),

            Toggle::make('activo')
                ->label('Visible en el sitio')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('imagen')
                ->label('Imagen'),

            Tables\Columns\TextColumn::make('titulo')
                ->label('Título')
                ->searchable(),

            Tables\Columns\TextColumn::make('orden')
                ->label('Orden')
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
            'index' => Pages\ListGalerias::route('/'),
            'create' => Pages\CreateGaleria::route('/create'),
            'edit' => Pages\EditGaleria::route('/{record}/edit'),
        ];
    }
}