<?php

namespace App\Filament\Resources\Alumnos;

use App\Filament\Resources\Alumnos\Pages;
use App\Models\Alumno;
use App\Models\Clase;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
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

            Tables\Columns\IconColumn::make('expediente_medico')
                ->label('C.Médico')
                ->getStateUsing(fn (Alumno $record): bool => $record->expedienteMedico()->exists())
                ->boolean()
                ->trueIcon('heroicon-o-clipboard-document-check')
                ->falseIcon('heroicon-o-clipboard-document')
                ->trueColor('success')
                ->falseColor('gray')
                ->tooltip(fn (Alumno $record): string => $record->expedienteMedico()->exists()
                    ? 'Expediente registrado'
                    : 'Sin expediente'),
        ])
        ->actions([
            Action::make('controlMedico')
                ->label('C.Médico')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('info')
                ->modalWidth('4xl')
                ->modalHeading(fn (Alumno $record): string => 'Expediente Médico — ' . $record->nombre . ' ' . $record->apellidos)
                ->modalSubmitActionLabel('Guardar expediente')
                ->fillForm(function (Alumno $record): array {
                    $exp = $record->expedienteMedico;
                    if (! $exp) return [];

                    return collect($exp->toArray())->only([
                        'tipo_sangre', 'alergias', 'condiciones_medicas', 'medicamentos',
                        'restricciones_fisicas', 'medico_nombre', 'medico_telefono',
                        'medico_cedula', 'seguro_medico', 'numero_poliza',
                        'fecha_expedicion', 'notas',
                    ])->toArray();
                    // archivo_certificado se omite: FileUpload no puede pre-llenarse con rutas existentes
                })
                ->form([
                    Section::make('Información Médica')
                        ->columns(2)
                        ->schema([
                            Select::make('tipo_sangre')
                                ->label('Tipo de Sangre')
                                ->options(['A+'=>'A+','A-'=>'A-','B+'=>'B+','B-'=>'B-',
                                           'AB+'=>'AB+','AB-'=>'AB-','O+'=>'O+','O-'=>'O-'])
                                ->native(false)
                                ->placeholder('Desconocido'),

                            DatePicker::make('fecha_expedicion')
                                ->label('Fecha del certificado')
                                ->native(false),

                            Textarea::make('alergias')
                                ->label('Alergias')
                                ->rows(2)
                                ->columnSpanFull(),

                            Textarea::make('condiciones_medicas')
                                ->label('Condiciones médicas preexistentes')
                                ->rows(2)
                                ->columnSpanFull(),

                            Textarea::make('medicamentos')
                                ->label('Medicamentos actuales')
                                ->rows(2)
                                ->columnSpanFull(),

                            Textarea::make('restricciones_fisicas')
                                ->label('Restricciones físicas')
                                ->placeholder('Ej: No puede hacer educación física, no levantar peso...')
                                ->rows(2)
                                ->columnSpanFull(),
                        ]),

                    Section::make('Médico Tratante')
                        ->columns(3)
                        ->schema([
                            TextInput::make('medico_nombre')
                                ->label('Nombre del médico'),

                            TextInput::make('medico_telefono')
                                ->label('Teléfono')
                                ->tel(),

                            TextInput::make('medico_cedula')
                                ->label('Cédula profesional'),
                        ]),

                    Section::make('Seguro Médico')
                        ->columns(2)
                        ->schema([
                            TextInput::make('seguro_medico')
                                ->label('Nombre del seguro')
                                ->placeholder('IMSS, ISSSTE, Seguro Popular, GNP...'),

                            TextInput::make('numero_poliza')
                                ->label('Número de póliza'),
                        ]),

                    Section::make('Certificado Original')
                        ->schema([
                            FileUpload::make('archivo_certificado')
                                ->label('Subir certificado (foto o PDF)')
                                ->helperText('Si ya tiene uno guardado, solo sube uno nuevo si quieres reemplazarlo.')
                                ->acceptedFileTypes(['image/*', 'application/pdf'])
                                ->disk('public')
                                ->directory('expedientes-medicos')
                                ->maxSize(10240)
                                ->downloadable()
                                ->openable()
                                ->nullable(),

                            Textarea::make('notas')
                                ->label('Notas adicionales')
                                ->rows(2),
                        ]),
                ])
                ->action(function (array $data, Alumno $record): void {
                    $existente = $record->expedienteMedico;

                    // Conservar el certificado anterior si no se subió uno nuevo
                    if (empty($data['archivo_certificado']) && $existente?->archivo_certificado) {
                        $data['archivo_certificado'] = $existente->archivo_certificado;
                    }

                    $record->expedienteMedico()->updateOrCreate(
                        ['alumno_id' => $record->id],
                        $data
                    );

                    Notification::make()
                        ->title('Expediente médico guardado')
                        ->success()
                        ->send();
                }),

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