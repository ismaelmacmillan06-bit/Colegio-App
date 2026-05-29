<?php

namespace App\Filament\Resources\Docentes;

use App\Filament\Resources\Docentes\Pages;
use App\Models\Docente;
use App\Models\Clase;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class DocenteResource extends Resource
{
    protected static ?string $model = Docente::class;
    protected static ?string $navigationLabel = 'Docentes';
    protected static ?string $modelLabel = 'Docente';
    protected static ?int $navigationSort = 3;
    public static function getNavigationGroup(): ?string
{
    return 'Escuela';
}

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

            Select::make('tipo')
                ->label('Tipo de Docente')
                ->required()
                ->options([
                    'titular' => 'Titular de Clase',
                    'extracurricular' => 'Extracurricular',
                    'directivo' => 'Directivo',
                ])
                ->default('titular')
                ->live(),

            Select::make('clase_id')
                ->label('Clase Asignada')
                ->options(Clase::where('activo', true)->pluck('nombre', 'id'))
                ->searchable()
                ->nullable()
                ->visible(fn($get) => $get('tipo') === 'titular'),

            TextInput::make('materia')
                ->label('Materia / Cargo')
                ->maxLength(255)
                ->placeholder('Ej: Inglés, Director General, Danza'),

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

            Tables\Columns\TextColumn::make('tipo')
                ->label('Tipo')
                ->badge()
                ->color(fn(string $state): string => match($state) {
                    'titular' => 'success',
                    'extracurricular' => 'warning',
                    'directivo' => 'info',
                }),

            Tables\Columns\TextColumn::make('clase.nombre')
                ->label('Clase')
                ->sortable()
                ->placeholder('Sin clase'),

            Tables\Columns\TextColumn::make('materia')
                ->label('Materia / Cargo')
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