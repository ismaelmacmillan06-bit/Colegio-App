<?php

namespace App\Filament\Resources\Alumnos;

use App\Filament\Resources\Alumnos\Pages;
use App\Models\Alumno;
use App\Models\Clase;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;

class AlumnoResource extends Resource
{
    protected static ?string $model = Alumno::class;
    protected static ?string $navigationLabel = 'Alumnos';
    protected static ?string $modelLabel = 'Alumno';
    protected static ?int $navigationSort = 2;
    public static function getNavigationGroup(): ?string
{
    return 'Escuela';
}
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

                    Select::make('clase_id')
                        ->label('Clase')
                        ->required()
                        ->options(Clase::where('activo', true)->pluck('nombre', 'id'))
                        ->searchable(),

                    TextInput::make('nfc_uid')
                        ->label('UID Credencial NFC')
                        ->maxLength(255)
                        ->placeholder('Se asigna al registrar la credencial'),

                    FileUpload::make('foto')
                        ->label('Foto del Alumno')
                        ->image()
                        ->disk('public')
                        ->directory('alumnos')
                        ->maxSize(1024),

                    Toggle::make('activo')
                        ->label('Activo')
                        ->default(true),
                ])->columns(2),

            Section::make('Contactos')
    ->schema([
        // PADRE
        TextInput::make('nombre_padre')
            ->label('Nombre del Padre')
            ->maxLength(255),

        TextInput::make('telefono_padre')
            ->label('Teléfono del Padre')
            ->tel()
            ->maxLength(20),

        TextInput::make('correo_padre')
            ->label('Correo del Padre')
            ->email()
            ->maxLength(255),

        // MADRE
        TextInput::make('nombre_madre')
            ->label('Nombre de la Madre')
            ->maxLength(255),

        TextInput::make('telefono_madre')
            ->label('Teléfono de la Madre')
            ->tel()
            ->maxLength(20),

        TextInput::make('correo_madre')
            ->label('Correo de la Madre')
            ->email()
            ->maxLength(255),

        // TUTOR
        TextInput::make('nombre_tutor')
            ->label('Nombre del Tutor')
            ->maxLength(255),

        TextInput::make('telefono_tutor')
            ->label('Teléfono del Tutor')
            ->tel()
            ->maxLength(20),

        TextInput::make('correo_tutor')
            ->label('Correo del Tutor')
            ->email()
            ->maxLength(255),
    ])->columns(3),
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

            Tables\Columns\TextColumn::make('clase.nombre')
                ->label('Clase')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('nfc_uid')
                ->label('NFC UID')
                ->toggleable(),

            Tables\Columns\IconColumn::make('activo')
                ->label('Activo')
                ->boolean(),
        ])
        ->actions([
            Action::make('generarCredencial')
                ->label('Credencial')
                ->icon('heroicon-o-identification')
                ->color('success')
                ->requiresConfirmation(false)
                ->form([
                    Select::make('responsable')
                        ->label('Datos del reverso')
                        ->options([
                            'padre'  => 'Padre',
                            'madre'  => 'Madre',
                            'tutor'  => 'Tutor Legal',
                        ])
                        ->default('padre')
                        ->required(),
                ])
                ->modalHeading('Generar Credencial')
                ->modalDescription('Selecciona qué contacto aparecerá en el reverso de la credencial.')
                ->modalSubmitActionLabel('Descargar PDF')
                ->action(function (Alumno $record, array $data) {
                    $url = route('credencial.alumno', [
                        'alumno'      => $record->id,
                        'responsable' => $data['responsable'],
                    ]);
                    return redirect()->to($url);
                }),

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