<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\Colegios\Pages;
use App\Models\Colegio;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class ColegioResource extends Resource
{
    protected static ?string $model = Colegio::class;
    protected static ?string $navigationLabel = 'Colegios';
    protected static ?string $modelLabel = 'Colegio';
    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-building-office-2';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Información del Colegio')
                ->columns(2)
                ->schema([
                    TextInput::make('nombre')
                        ->label('Nombre del colegio')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    TextInput::make('director')
                        ->label('Director / Directora')
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Correo institucional')
                        ->email()
                        ->maxLength(255),

                    TextInput::make('telefono')
                        ->label('Teléfono')
                        ->tel()
                        ->maxLength(20),

                    TextInput::make('rfc')
                        ->label('RFC')
                        ->maxLength(13),

                    Textarea::make('domicilio')
                        ->label('Domicilio')
                        ->rows(2)
                        ->columnSpanFull(),

                    FileUpload::make('logo_path')
                        ->label('Logo del colegio')
                        ->image()
                        ->disk('public')
                        ->directory('colegios/logos')
                        ->maxSize(2048)
                        ->columnSpanFull(),
                ]),

            Section::make('Plan y Suscripción')
                ->columns(2)
                ->schema([
                    Select::make('plan')
                        ->label('Plan')
                        ->options([
                            'basico'   => 'Básico — hasta 100 alumnos',
                            'estandar' => 'Estándar — hasta 500 alumnos',
                            'premium'  => 'Premium — ilimitado + IA',
                        ])
                        ->required()
                        ->native(false)
                        ->default('basico'),

                    TextInput::make('precio_mensual')
                        ->label('Precio mensual (MXN)')
                        ->numeric()
                        ->prefix('$')
                        ->default(0),

                    DatePicker::make('fecha_inicio')
                        ->label('Fecha de inicio')
                        ->native(false),

                    DatePicker::make('fecha_vencimiento')
                        ->label('Fecha de vencimiento')
                        ->native(false),

                    Toggle::make('activo')
                        ->label('Activo')
                        ->default(true)
                        ->columnSpanFull(),
                ]),

            Section::make('Notas Internas')
                ->schema([
                    Textarea::make('notas')
                        ->label('Notas (visibles solo para super admin)')
                        ->rows(3),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(asset('images/logoimacf.png')),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Colegio')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('plan')
                    ->label('Plan')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'basico'   => 'info',
                        'estandar' => 'success',
                        'premium'  => 'warning',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'basico'   => 'Básico',
                        'estandar' => 'Estándar',
                        'premium'  => 'Premium',
                        default    => $state,
                    }),

                Tables\Columns\TextColumn::make('precio_mensual')
                    ->label('Mensualidad')
                    ->money('MXN')
                    ->sortable(),

                Tables\Columns\TextColumn::make('alumnos_count')
                    ->label('Alumnos')
                    ->counts('alumnos')
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_vencimiento')
                    ->label('Vence')
                    ->date('d/m/Y')
                    ->color(fn ($record) => $record?->porVencer() ? 'danger' : null)
                    ->sortable(),

                Tables\Columns\IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->defaultSort('nombre')
            ->actions([
                Action::make('crearAdmin')
                    ->label('Crear Admin')
                    ->icon('heroicon-o-user-plus')
                    ->color('info')
                    ->modalHeading(fn (Colegio $record) => 'Crear Admin para ' . $record->nombre)
                    ->modalDescription('Se creará un usuario administrador para este colegio.')
                    ->form([
                        TextInput::make('name')
                            ->label('Nombre del administrador')
                            ->required(),
                        TextInput::make('email')
                            ->label('Correo de acceso')
                            ->email()
                            ->required()
                            ->rules(['required', 'email', 'unique:users,email']),
                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->revealable()
                            ->required()
                            ->minLength(8),
                    ])
                    ->action(function (array $data, Colegio $record): void {
                        User::create([
                            'name'           => $data['name'],
                            'email'          => $data['email'],
                            'password'       => Hash::make($data['password']),
                            'colegio_id'     => $record->id,
                            'is_super_admin' => false,
                        ]);

                        Notification::make()
                            ->title('Admin creado correctamente')
                            ->body('El colegio ya puede acceder en /admin')
                            ->success()
                            ->send();
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
            'index'  => Pages\ListColegios::route('/'),
            'create' => Pages\CreateColegio::route('/create'),
            'edit'   => Pages\EditColegio::route('/{record}/edit'),
        ];
    }
}
