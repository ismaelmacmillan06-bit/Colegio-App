<?php

namespace App\Filament\Resources\Sliders;

use App\Filament\Resources\Sliders\Pages;
use App\Models\Slider;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput as NumberInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;
    protected static ?string $navigationLabel = 'Sliders';
    protected static ?string $modelLabel = 'Slider';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('titulo')
                ->label('Título')
                ->maxLength(255)
                ->columnSpanFull(),

            TextInput::make('subtitulo')
                ->label('Subtítulo')
                ->maxLength(255)
                ->columnSpanFull(),

            FileUpload::make('imagen')
                ->label('Imagen')
                ->image()
                ->directory('sliders')
                ->required()
                ->maxSize(1024)        // máximo 1MB
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}