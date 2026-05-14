<?php

namespace App\Filament\Resources\Docentes;

use App\Filament\Resources\Docentes\Pages;
use App\Models\Docente;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class DocenteResource extends Resource
{
    protected static ?string $model = Docente::class;
    protected static ?string $navigationLabel = 'Docentes';
    protected static ?string $modelLabel = 'Docente';
    protected static ?int $navigationSort = 9;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nombre')
                ->label('Nombre')
                ->required()
                ->maxLength(255),

            TextInput::make('apellidos')
                ->label('Apellidos')
                ->required()
                ->maxLength(255),

            TextInput::make('materia')
                ->label('Materia')
                ->maxLength(255),

            TextInput::make('telefono')
                ->label('Teléfono')
                ->tel()
                ->maxLength(20),

            TextInput::make('nfc_uid')
                ->label('UID Credencial NFC')
                ->maxLength(255)
                ->placeholder('Se asigna al registrar la credencial'),

            FileUpload::make('foto')
                ->label('Foto')
                ->image()
                ->disk('public')
                ->directory('docentes')
                ->maxSize(1024)
                ->columnSpanFull(),

            Toggle::make('activo')
                ->label('Activo')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('foto')
                ->label('Foto')
                ->circular()
                ->disk('public'),

            Tables\Columns\TextColumn::make('nombre')
                ->label('Nombre')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('apellidos')
                ->label('Apellidos')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('materia')
                ->label('Materia')
                ->searchable(),

            Tables\Columns\TextColumn::make('nfc_uid')
                ->label('NFC UID')
                ->toggleable(),

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
            'index' => Pages\ListDocentes::route('/'),
            'create' => Pages\CreateDocente::route('/create'),
            'edit' => Pages\EditDocente::route('/{record}/edit'),
        ];
    }
}