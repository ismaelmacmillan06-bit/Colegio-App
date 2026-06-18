<?php

namespace App\Filament\Resources\Colegiaturas;

use App\Filament\Resources\Colegiaturas\Pages;
use App\Models\Alumno;
use App\Models\Colegiatura;
use App\Models\NivelColegiatura;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ColegiaturaResource extends Resource
{
    protected static ?string $model = Colegiatura::class;
    protected static ?string $modelLabel = 'Colegiatura';
    protected static ?string $navigationLabel = 'Colegiaturas (lista)';
    protected static ?int    $navigationSort  = 5;
    protected static bool    $shouldRegisterNavigation = false;

    public static function getNavigationIcon(): \BackedEnum|string|null { return 'heroicon-o-banknotes'; }
    public static function getNavigationGroup(): \UnitEnum|string|null  { return 'Escuela'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('alumno_id')
                ->label('Alumno')
                ->options(fn () => Alumno::orderBy('apellidos')->get()
                    ->mapWithKeys(fn ($a) => [$a->id => $a->apellidos . ', ' . $a->nombre]))
                ->searchable()
                ->required(),

            Select::make('nivel_colegiatura_id')
                ->label('Nivel / Config')
                ->options(fn () => NivelColegiatura::all()
                    ->mapWithKeys(fn ($nc) => [$nc->id => $nc->nivel . ' — $' . number_format($nc->monto, 0) . ' ' . $nc->tipo_cobro]))
                ->required(),

            TextInput::make('periodo')
                ->label('Período')
                ->required()
                ->maxLength(30)
                ->placeholder('Ej: Enero 2026 / Bim. 1/2026'),

            TextInput::make('monto')
                ->label('Monto ($)')
                ->numeric()
                ->prefix('$')
                ->required(),

            Select::make('tipo_cobro')
                ->label('Tipo de cobro')
                ->options(['Mensual' => 'Mensual', 'Bimestral' => 'Bimestral', 'Semestral' => 'Semestral'])
                ->required()
                ->native(false),

            Select::make('status')
                ->label('Estado')
                ->options([
                    'pendiente'      => 'Pendiente',
                    'proximo_vencer' => 'Próximo a vencer',
                    'pagada'         => 'Pagada',
                    'declinada'      => 'Declinada',
                    'vencida'        => 'Vencida',
                ])
                ->required()
                ->native(false)
                ->default('pendiente'),

            DatePicker::make('fecha_vencimiento')
                ->label('Fecha de vencimiento')
                ->native(false),

            DatePicker::make('fecha_pago')
                ->label('Fecha de pago')
                ->native(false),

            Textarea::make('notas')
                ->label('Notas')
                ->rows(2)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('alumno.apellidos')
                    ->label('Alumno')
                    ->formatStateUsing(fn ($state, $record) =>
                        ($record->alumno?->apellidos ?? '') . ', ' . ($record->alumno?->nombre ?? ''))
                    ->searchable(['alumnos.nombre', 'alumnos.apellidos'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('alumno.clase.nombre')
                    ->label('Clase')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nivelConfig.nivel')
                    ->label('Nivel')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Maternal'     => 'info',
                        'Preescolar'   => 'success',
                        'Primaria'     => 'warning',
                        'Secundaria'   => 'danger',
                        'Bachillerato' => 'gray',
                        'Licenciatura' => 'primary',
                        default        => 'gray',
                    }),

                Tables\Columns\TextColumn::make('periodo')
                    ->label('Período')
                    ->sortable(),

                Tables\Columns\TextColumn::make('monto')
                    ->label('Monto')
                    ->money('MXN')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tipo_cobro')
                    ->label('Cobro')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pagada'         => 'success',
                        'pendiente'      => 'warning',
                        'proximo_vencer' => 'danger',
                        'declinada'      => 'gray',
                        'vencida'        => 'gray',
                        default          => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pagada'         => 'Pagada',
                        'pendiente'      => 'Pendiente',
                        'proximo_vencer' => 'Por vencer',
                        'declinada'      => 'Declinada',
                        'vencida'        => 'Vencida',
                        default          => ucfirst($state),
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_vencimiento')
                    ->label('Vence')
                    ->date('d/m/Y')
                    ->color(fn ($record) =>
                        $record->status === 'pendiente' && $record->fecha_vencimiento?->isPast()
                            ? 'danger' : null)
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_pago')
                    ->label('Pagada')
                    ->date('d/m/Y')
                    ->placeholder('—'),
            ])
            ->defaultSort('fecha_vencimiento', 'asc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'pagada'    => 'Pagada',
                        'declinada' => 'Declinada',
                        'vencida'   => 'Vencida',
                    ]),

                SelectFilter::make('tipo_cobro')
                    ->label('Tipo de cobro')
                    ->options([
                        'Mensual'   => 'Mensual',
                        'Bimestral' => 'Bimestral',
                        'Semestral' => 'Semestral',
                    ]),
            ])
            ->actions([
                Action::make('marcar_pagada')
                    ->label('Pagada')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Colegiatura $r) => $r->status !== 'pagada')
                    ->requiresConfirmation()
                    ->modalHeading('Marcar como Pagada')
                    ->modalDescription('¿Confirmas que la colegiatura fue recibida?')
                    ->action(function (Colegiatura $record): void {
                        $record->update(['status' => 'pagada', 'fecha_pago' => now()->toDateString()]);
                        Notification::make()->title('Marcada como pagada')->success()->send();
                    }),

                Action::make('marcar_declinada')
                    ->label('Declinada')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Colegiatura $r) => $r->status !== 'declinada')
                    ->requiresConfirmation()
                    ->modalHeading('Marcar como Declinada')
                    ->modalDescription('Se registrará que el pago fue rechazado o no efectuado.')
                    ->action(function (Colegiatura $record): void {
                        $record->update(['status' => 'declinada', 'fecha_pago' => null]);
                        Notification::make()->title('Marcada como declinada')->danger()->send();
                    }),

                Action::make('marcar_vencida')
                    ->label('Vencida')
                    ->icon('heroicon-o-clock')
                    ->color('gray')
                    ->visible(fn (Colegiatura $r) => $r->status === 'pendiente')
                    ->action(function (Colegiatura $record): void {
                        $record->update(['status' => 'vencida']);
                        Notification::make()->title('Marcada como vencida')->warning()->send();
                    }),

                \Filament\Actions\EditAction::make()->label('Editar'),
            ])
            ->emptyStateIcon('heroicon-o-banknotes')
            ->emptyStateHeading('Sin colegiaturas registradas')
            ->emptyStateDescription('Aún no hay alumnos o colegiaturas cargadas. Agrega alumnos y genera sus colegiaturas para verlas aquí.')
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_pagada')
                        ->label('Marcar Pagadas')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            $records->each(fn ($r) => $r->update([
                                'status'     => 'pagada',
                                'fecha_pago' => now()->toDateString(),
                            ]));
                            Notification::make()->title('Colegiaturas marcadas como pagadas')->success()->send();
                        }),

                    BulkAction::make('bulk_vencida')
                        ->label('Marcar Vencidas')
                        ->icon('heroicon-o-clock')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            $records->each(fn ($r) => $r->update(['status' => 'vencida']));
                            Notification::make()->title('Colegiaturas marcadas como vencidas')->warning()->send();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListColegiaturas::route('/'),
            'create' => Pages\CreateColegiatura::route('/create'),
            'edit'  => Pages\EditColegiatura::route('/{record}/edit'),
        ];
    }
}
