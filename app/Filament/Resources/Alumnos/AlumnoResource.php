<?php

namespace App\Filament\Resources\Alumnos;

use App\Filament\Resources\Alumnos\Pages;
use App\Models\Alumno;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class AlumnoResource extends Resource
{
    protected static ?string $model = Alumno::class;
    protected static ?string $navigationLabel = 'Alumnos';
    protected static ?string $modelLabel = 'Alumno';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Datos del Alumno')
                ->schema([
                    TextInput::make('nombre')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('apellidos')
                        ->label('Apellidos')
                        ->required()
                        ->maxLength(255),

                    Select::make('grupo_id')
                        ->label('Grupo')
                        ->required()
                        ->relationship('grupo', 'grupo')
                        ->preload(),

                    TextInput::make('nfc_uid')
                        ->label('UID Credencial NFC')
                        ->maxLength(255)
                        ->placeholder('Se asigna al registrar la credencial'),

                    FileUpload::make('foto')
                        ->label('Foto del Alumno')
                        ->image()
                        ->directory('alumnos')
                        ->maxSize(1024),

                    Toggle::make('activo')
                        ->label('Activo')
                        ->default(true),
                ])->columns(2),

            Section::make('Datos de los Padres')
                ->schema([
                    TextInput::make('nombre_padre')
                        ->label('Nombre del Padre')
                        ->maxLength(255),

                    TextInput::make('telefono_padre')
                        ->label('Teléfono del Padre')
                        ->tel()
                        ->maxLength(20),

                    TextInput::make('nombre_madre')
                        ->label('Nombre de la Madre')
                        ->maxLength(255),

                    TextInput::make('telefono_madre')
                        ->label('Teléfono de la Madre')
                        ->tel()
                        ->maxLength(20),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('foto')
                ->label('Foto')
                ->circular(),

            Tables\Columns\TextColumn::make('nombre')
                ->label('Nombre')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('apellidos')
                ->label('Apellidos')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('grupo.grupo')
                ->label('Grupo')
                ->sortable(),

            Tables\Columns\TextColumn::make('grupo.grado.nombre')
                ->label('Grado')
                ->sortable(),

            Tables\Columns\TextColumn::make('nfc_uid')
                ->label('NFC UID')
                ->searchable()
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
            'index' => Pages\ListAlumnos::route('/'),
            'create' => Pages\CreateAlumno::route('/create'),
            'edit' => Pages\EditAlumno::route('/{record}/edit'),
        ];
    }
}