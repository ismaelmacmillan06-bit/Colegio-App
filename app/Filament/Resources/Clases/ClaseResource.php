<?php

namespace App\Filament\Resources\Clases;

use App\Filament\Resources\Clases\Pages;
use App\Models\Clase;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
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
                ->label('Nombre de la clase')
                ->required()
                ->placeholder('Ej: 1°A Primaria, Maternal B, Kinder 3')
                ->maxLength(255)
                ->columnSpanFull(),

            Select::make('nivel')
                ->label('Nivel')
                ->options([
                    'Maternal'    => 'Maternal',
                    'Preescolar'  => 'Preescolar',
                    'Primaria'    => 'Primaria',
                    'Secundaria'  => 'Secundaria',
                    'Bachillerato'=> 'Bachillerato',
                ])
                ->native(false)
                ->required(),

            DatePicker::make('fecha_fin')
                ->label('Fecha de fin')
                ->native(false)
                ->placeholder('dd/mm/aaaa'),
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
                ->badge()
                ->color(fn ($state) => match($state) {
                    'Maternal'     => 'info',
                    'Preescolar'   => 'success',
                    'Primaria'     => 'warning',
                    'Secundaria'   => 'danger',
                    'Bachillerato' => 'gray',
                    default        => 'gray',
                })
                ->sortable(),

            Tables\Columns\TextColumn::make('fecha_fin')
                ->label('Fecha de fin')
                ->date('d/m/Y')
                ->sortable(),

            Tables\Columns\TextColumn::make('alumnos_count')
                ->label('Alumnos')
                ->counts('alumnos')
                ->alignCenter(),

            Tables\Columns\IconColumn::make('activo')
                ->label('Activa')
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
            'index'  => Pages\ListClases::route('/'),
            'create' => Pages\CreateClase::route('/create'),
            'edit'   => Pages\EditClase::route('/{record}/edit'),
        ];
    }
}
