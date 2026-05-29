<?php

namespace App\Filament\Resources\Circulars;

use App\Filament\Resources\Circulars\Pages;
use App\Models\Circular;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class CircularResource extends Resource
{
    protected static ?string $model = Circular::class;
    protected static ?string $navigationLabel = 'Circulares';
    protected static ?string $modelLabel = 'Circular';
    protected static ?int $navigationSort = 1;
    public static function getNavigationGroup(): ?string
{
    return 'Página Web';
}

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
                ->rows(3)
                ->columnSpanFull(),

            DatePicker::make('fecha')
                ->label('Fecha')
                ->required()
                ->default(now()),

            Toggle::make('activo')
                ->label('Visible en el sitio')
                ->default(true),

            FileUpload::make('archivo_pdf')
                ->label('Archivo PDF')
                ->acceptedFileTypes(['application/pdf'])
                ->disk('public')
                ->directory('circulares')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('titulo')
            ->label('Título')
            ->searchable()
            ->sortable(),

        Tables\Columns\TextColumn::make('fecha')
            ->label('Fecha')
            ->date('d/m/Y')
            ->sortable(),

        Tables\Columns\IconColumn::make('activo')
            ->label('Visible')
            ->boolean(),
    ])
    ->defaultSort('fecha', 'desc')
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
            'index' => Pages\ListCirculars::route('/'),
            'create' => Pages\CreateCircular::route('/create'),
            'edit' => Pages\EditCircular::route('/{record}/edit'),
        ];
    }
}