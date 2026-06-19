<x-filament-panels::page>

    {{-- Acciones de Filament (modales) --}}
    <x-filament-actions::modals />

    {{-- Header: info de la clase --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
        <a href="/docente/mis-clases"
           class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition">
            <x-heroicon-o-arrow-left class="w-4 h-4" />
            Regresar a Mis Clases
        </a>

        <div class="flex-1 sm:text-right">
            @php $titular = $this->clase->docentes->firstWhere(fn($d) => $d->pivot->es_titular) ?? $this->clase->docentes->first(); @endphp
            @if($titular)
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Titular: <strong>{{ $titular->nombre }} {{ $titular->apellidos }}</strong>
                </p>
            @endif
            <p class="text-sm text-gray-400">
                {{ $this->clase->alumnos->count() }} alumnos &bull; {{ $this->clase->nivel }}
            </p>
        </div>
    </div>

    {{-- Tabs --}}
    @php
        $tabs = [
            'pase-lista'    => ['label' => 'Pase de Lista',    'icon' => 'heroicon-o-clipboard-document-check'],
            'asistencia'    => ['label' => 'Asistencia',       'icon' => 'heroicon-o-calendar-days'],
            'tarea'         => ['label' => 'Tareas',           'icon' => 'heroicon-o-document-text'],
            'trabajo_clase' => ['label' => 'Trabajo en Clase', 'icon' => 'heroicon-o-pencil-square'],
            'proyecto'      => ['label' => 'Proyectos',        'icon' => 'heroicon-o-beaker'],
            'examen'        => ['label' => 'Examen',           'icon' => 'heroicon-o-academic-cap'],
            'extra'         => ['label' => 'Extras',           'icon' => 'heroicon-o-star'],
        ];
    @endphp

    <div class="mb-6 border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
        <nav class="flex gap-1 min-w-max">
            @foreach($tabs as $key => $info)
                <button wire:click="changeTab('{{ $key }}')"
                        class="flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium border-b-2 transition whitespace-nowrap
                            {{ $tab === $key
                                ? 'border-emerald-600 text-emerald-700 dark:text-emerald-400 dark:border-emerald-400'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400' }}">
                    <x-dynamic-component :component="$info['icon']" class="w-4 h-4" />
                    {{ $info['label'] }}
                </button>
            @endforeach
        </nav>
    </div>

    {{-- ========================= TAB: PASE DE LISTA ========================= --}}
    @if($tab === 'pase-lista')

        {{-- ── SELECTOR DE MATERIA / PERIODO (solo Secundaria y Bachillerato) ── --}}
        @if($this->esSecundaria)
            <div class="mb-5 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Selecciona el periodo / materia:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($this->materias as $m)
                        <button wire:click="seleccionarMateriaCorte({{ $m['id'] }})"
                                class="px-3 py-1.5 text-xs rounded-full border font-medium transition
                                    {{ $corteMateria === $m['id']
                                        ? 'bg-emerald-700 text-white border-emerald-700'
                                        : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border-gray-300 hover:border-gray-400' }}">
                            {{ $m['nombre'] }}
                        </button>
                    @endforeach
                </div>
                @if(! $corteMateria)
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-2 flex items-center gap-1">
                        <x-heroicon-o-exclamation-triangle class="w-3.5 h-3.5" />
                        Selecciona una materia para ver o registrar el pase de lista de ese periodo.
                    </p>
                @endif
            </div>
        @endif

        {{-- ── TABLA PRINCIPAL: PASE DE LISTA EN VIVO ── --}}
        @if(! $this->esSecundaria || $corteMateria)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Pase de Lista</h3>
                    <p class="text-sm text-gray-500">
                        {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                        @if($this->esSecundaria && $corteMateria)
                            @php $mActual = collect($this->materias)->firstWhere('id', $corteMateria); @endphp
                            @if($mActual)
                                &mdash; <span class="font-semibold text-emerald-600">{{ $mActual['nombre'] }}</span>
                            @endif
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-sm text-gray-400 mr-1">
                        {{ collect($this->alumnosHoy)->where('estado', 'presente')->count() }}
                        / {{ count($this->alumnosHoy) }} presentes
                    </span>

                    {{-- Botón / Badge Marcar Entrada --}}
                    @if($this->corteEntradaHoy)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 border border-green-200 text-green-700 text-xs font-semibold rounded-lg">
                            <x-heroicon-s-check-circle class="w-3.5 h-3.5" />
                            Entrada {{ substr($this->corteEntradaHoy->hora_corte, 0, 5) }}
                        </span>
                    @else
                        <button wire:click="mountAction('marcarEntrada')"
                                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white rounded-lg shadow-sm transition bg-emerald-700 hover:bg-emerald-800">
                            <x-heroicon-o-arrow-right-circle class="w-4 h-4" />
                            Marcar Entrada
                        </button>
                    @endif

                    {{-- Botón / Badge Marcar Salida --}}
                    @if($this->corteSalidaHoy)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold rounded-lg">
                            <x-heroicon-s-check-circle class="w-3.5 h-3.5" />
                            Salida {{ substr($this->corteSalidaHoy->hora_corte, 0, 5) }}
                        </span>
                    @elseif($this->corteEntradaHoy)
                        <button wire:click="mountAction('marcarSalida')"
                                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white rounded-lg shadow-sm transition bg-amber-500 hover:bg-amber-600">
                            <x-heroicon-o-arrow-left-circle class="w-4 h-4" />
                            Marcar Salida
                        </button>
                    @else
                        <button disabled
                                title="Primero registra la entrada"
                                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-gray-400 rounded-lg border border-gray-200 bg-gray-50 cursor-not-allowed">
                            <x-heroicon-o-arrow-left-circle class="w-4 h-4" />
                            Marcar Salida
                        </button>
                    @endif
                </div>
            </div>

            @if(empty($this->alumnosHoy))
                <div class="py-12 text-center text-gray-400">
                    <x-heroicon-o-user-group class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                    No hay alumnos en esta clase
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50 text-left text-xs text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-3">Nombre</th>
                                <th class="px-6 py-3">Fecha</th>
                                <th class="px-6 py-3">Asistencia</th>
                                <th class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($this->alumnosHoy as $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-3">
                                        <div class="flex items-center gap-3">
                                            @if($row['foto'])
                                                <img src="{{ asset('storage/' . $row['foto']) }}"
                                                     class="w-8 h-8 rounded-full object-cover" />
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-500 text-xs font-bold">
                                                    {{ strtoupper(substr($row['nombre'], 0, 1)) }}
                                                </div>
                                            @endif
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $row['nombre'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-gray-500">{{ now()->format('d/m/Y') }}</td>
                                    <td class="px-6 py-3">
                                        @if($row['estado'] === 'presente')
                                            <div class="flex items-center gap-1.5">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                    <x-heroicon-s-check-circle class="w-3.5 h-3.5" />
                                                    Presente
                                                </span>
                                                @if($row['hora_entrada'])
                                                    <span class="text-xs text-gray-400">{{ substr($row['hora_entrada'], 0, 5) }}</span>
                                                @endif
                                            </div>
                                        @elseif($row['estado'] === 'tardanza')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                                <x-heroicon-s-clock class="w-3.5 h-3.5" />
                                                Tardanza
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                <x-heroicon-s-x-circle class="w-3.5 h-3.5" />
                                                Ausente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        @if($row['estado'] === 'ausente' && !$this->corteEntradaHoy)
                                            <button wire:click="marcarPresente({{ $row['id'] }})"
                                                    wire:loading.attr="disabled"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 hover:bg-green-100 text-green-700 text-xs font-medium rounded-lg border border-green-200 transition">
                                                <x-heroicon-o-check class="w-3.5 h-3.5" />
                                                Marcar presente
                                            </button>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        @endif

        {{-- ── TABLA HISTORIAL DE CORTES ── --}}
        <div class="mt-6">
            <div class="flex items-center gap-3 mb-3">
                <h3 class="text-base font-semibold text-gray-700 dark:text-gray-300">Historial de Cortes</h3>
                <span class="text-xs text-gray-400">({{ $this->historialCortes->count() }} registros)</span>
            </div>

            @if($this->historialCortes->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 py-10 text-center">
                    <x-heroicon-o-clipboard-document-list class="w-10 h-10 mx-auto mb-2 text-gray-300" />
                    <p class="text-gray-400 text-sm">Aún no hay cortes registrados.<br>Usa el botón <strong>Marcar Entrada</strong> para guardar el primer registro.</p>
                </div>
            @else
                <div class="flex flex-col gap-3">
                    @foreach($this->historialCortes as $corte)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                            <button wire:click="toggleCorte({{ $corte->id }})"
                                    class="w-full px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition text-left">

                                <div class="flex-1 flex items-center gap-3">
                                    @if($corte->tipo === 'entrada')
                                        <x-heroicon-o-arrow-right-circle class="w-5 h-5 text-green-500 flex-shrink-0" />
                                    @else
                                        <x-heroicon-o-arrow-left-circle class="w-5 h-5 text-blue-500 flex-shrink-0" />
                                    @endif
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $corte->fecha->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}
                                            </p>
                                            <span class="px-2 py-0.5 text-xs font-bold rounded-full
                                                {{ $corte->tipo === 'entrada' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                                {{ $corte->tipo === 'entrada' ? 'Entrada' : 'Salida' }}
                                            </span>
                                            @if($corte->materia)
                                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-indigo-100 text-indigo-700">
                                                    {{ $corte->materia->nombre }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-400">Registrado a las {{ substr($corte->hora_corte, 0, 5) }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                        ✓ {{ $corte->total_presentes }} presentes
                                    </span>
                                    @if($corte->total_tardanza > 0)
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">
                                            ⏱ {{ $corte->total_tardanza }} tardanza
                                        </span>
                                    @endif
                                    @if($corte->total_justificados > 0)
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                            📋 {{ $corte->total_justificados }} justificados
                                        </span>
                                    @endif
                                    @if($corte->total_ausentes > 0)
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                            ✗ {{ $corte->total_ausentes }} ausentes
                                        </span>
                                    @endif
                                    <x-heroicon-o-chevron-down class="w-4 h-4 text-gray-400 transition-transform {{ $corteExpandidoId === $corte->id ? 'rotate-180' : '' }}" />
                                </div>
                            </button>

                            @if($corteExpandidoId === $corte->id)
                                <div class="border-t border-gray-100 dark:border-gray-700 overflow-x-auto">
                                    @if(empty($this->detallesCorteExpandido))
                                        <div class="py-6 text-center text-gray-400 text-sm">Sin datos</div>
                                    @else
                                        <table class="w-full text-sm">
                                            <thead>
                                                <tr class="bg-gray-50 dark:bg-gray-900/50 text-left text-xs text-gray-500 uppercase tracking-wider">
                                                    <th class="px-6 py-3">Nombre</th>
                                                    <th class="px-6 py-3 text-center">Estado</th>
                                                    <th class="px-6 py-3">Nota</th>
                                                    <th class="px-6 py-3 text-right">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                                @foreach($this->detallesCorteExpandido as $detalle)
                                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                                        <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">
                                                            {{ $detalle['nombre'] }}
                                                        </td>
                                                        <td class="px-6 py-3 text-center">
                                                            @php
                                                                $estadoConfig = match($detalle['estado']) {
                                                                    'presente'    => ['bg-green-100 text-green-700', '✓ Presente'],
                                                                    'tardanza'    => ['bg-amber-100 text-amber-700', '⏱ Tardanza'],
                                                                    'justificado' => ['bg-blue-100 text-blue-700', '📋 Justificado'],
                                                                    default       => ['bg-red-100 text-red-700', '✗ Ausente'],
                                                                };
                                                            @endphp
                                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $estadoConfig[0] }}">
                                                                {{ $estadoConfig[1] }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-3 text-gray-500 text-xs">
                                                            {{ $detalle['nota'] ?? '—' }}
                                                        </td>
                                                        <td class="px-6 py-3 text-right">
                                                            <button wire:click="mountAction('editarEstadoCorte', @js(['detalle_id' => $detalle['id']]))"
                                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-medium rounded-lg border border-amber-200 transition">
                                                                <x-heroicon-o-pencil class="w-3.5 h-3.5" />
                                                                Editar
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    {{-- ========================= TAB: ASISTENCIA ========================= --}}
    @elseif($tab === 'asistencia')
        @php $matriz = $this->matrizAsistencia; @endphp

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Registro de Asistencia</h3>
                    <p class="text-sm text-gray-400">Basado en los cortes de entrada (sin filtro de materia)</p>
                </div>
                <span class="text-sm text-gray-400">{{ count($matriz['fechas']) }} día(s) registrado(s)</span>
            </div>

            @if(empty($matriz['fechas']))
                <div class="py-16 text-center">
                    <x-heroicon-o-calendar-days class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                    <p class="text-gray-500 font-medium">Sin registros de asistencia aún</p>
                    <p class="text-gray-400 text-sm mt-1">El registro aparece aquí cada vez que usas <strong>Marcar Entrada</strong> en el Pase de Lista.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="text-sm w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50">
                                <th class="px-6 py-3 text-left text-xs text-gray-500 uppercase tracking-wider font-semibold sticky left-0 bg-gray-50 dark:bg-gray-900/50 z-10 min-w-52">
                                    Nombre del Estudiante
                                </th>
                                @foreach($matriz['fechas'] as $fecha)
                                    <th class="px-3 py-3 text-center text-xs text-gray-500 uppercase tracking-wider font-semibold min-w-24">
                                        <span class="block text-gray-400 font-normal normal-case">{{ $fecha['dia'] }}</span>
                                        {{ $fecha['fecha'] }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($matriz['alumnos'] as $fila)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-3 sticky left-0 bg-white dark:bg-gray-800 z-10">
                                        <div class="flex items-center gap-2">
                                            @if($fila['foto'])
                                                <img src="{{ asset('storage/' . $fila['foto']) }}"
                                                     class="w-7 h-7 rounded-full object-cover flex-shrink-0" />
                                            @else
                                                <div class="w-7 h-7 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-500 text-xs font-bold flex-shrink-0">
                                                    {{ strtoupper(substr($fila['nombre'], 0, 1)) }}
                                                </div>
                                            @endif
                                            <span class="font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $fila['nombre'] }}</span>
                                        </div>
                                    </td>
                                    @foreach($fila['dias'] as $estado)
                                        <td class="px-3 py-3 text-center">
                                            @if($estado === 'presente')
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100" title="Presente">
                                                    <x-heroicon-s-check class="w-4 h-4 text-green-600" />
                                                </span>
                                            @elseif($estado === 'tardanza')
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-100" title="Tardanza">
                                                    <x-heroicon-s-clock class="w-4 h-4 text-amber-600" />
                                                </span>
                                            @elseif($estado === 'justificado')
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100" title="Falta justificada">
                                                    <x-heroicon-s-document-check class="w-4 h-4 text-blue-600" />
                                                </span>
                                            @elseif($estado === 'ausente')
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100" title="Ausente">
                                                    <x-heroicon-s-x-mark class="w-4 h-4 text-red-600" />
                                                </span>
                                            @else
                                                <span class="text-gray-300">—</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-700 flex items-center gap-4 flex-wrap">
                    <span class="text-xs text-gray-400 font-medium">Leyenda:</span>
                    <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-green-100"><x-heroicon-s-check class="w-3 h-3 text-green-600" /></span> Presente
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-red-100"><x-heroicon-s-x-mark class="w-3 h-3 text-red-600" /></span> Ausente
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-100"><x-heroicon-s-clock class="w-3 h-3 text-amber-600" /></span> Tardanza
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-100"><x-heroicon-s-document-check class="w-3 h-3 text-blue-600" /></span> Justificado
                    </span>
                    <span class="ml-auto text-xs text-gray-400 italic">
                        Para modificar un estado, ve a <strong>Pase de Lista → Historial → Editar</strong>
                    </span>
                </div>
            @endif
        </div>

    {{-- ========================= TABS ACADÉMICOS ========================= --}}
    @else
        @php
            $tipoLabels = [
                'tarea'         => 'Tarea',
                'trabajo_clase' => 'Trabajo en Clase',
                'proyecto'      => 'Proyecto',
                'examen'        => 'Examen',
                'extra'         => 'Extra',
            ];
            $labelActual = $tipoLabels[$tab] ?? 'Actividad';
        @endphp

        {{-- Barra de herramientas --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">

            {{-- Filtro de materia (solo las del docente) --}}
            @if(count($this->materias) > 0)
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-sm text-gray-500 font-medium">Materia:</span>
                    <button wire:click="filtrarMateria(null)"
                            class="px-3 py-1 text-xs rounded-full border transition
                                {{ is_null($materiaId)
                                    ? 'bg-emerald-700 text-white border-emerald-700'
                                    : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border-gray-300 hover:border-gray-400' }}">
                        Todas
                    </button>
                    @foreach($this->materias as $materia)
                        <button wire:click="filtrarMateria({{ $materia['id'] }})"
                                class="px-3 py-1 text-xs rounded-full border transition
                                    {{ $materiaId === $materia['id']
                                        ? 'bg-emerald-700 text-white border-emerald-700'
                                        : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border-gray-300 hover:border-gray-400' }}">
                            {{ $materia['nombre'] }}
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="sm:ml-auto">
                <button wire:click="mountAction('nuevaActividad')"
                        type="button"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg shadow-sm transition bg-emerald-700 hover:bg-emerald-800">
                    <x-heroicon-o-plus class="w-4 h-4" />
                    Nueva {{ $labelActual }}
                </button>
            </div>
        </div>

        {{-- Lista de actividades --}}
        @if($this->actividades->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 py-16 text-center">
                <x-heroicon-o-document-plus class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                <p class="text-gray-500 font-medium">Sin {{ strtolower($labelActual) }}s registradas</p>
                <p class="text-gray-400 text-sm mt-1">Usa el botón "Nueva {{ $labelActual }}" para agregar una.</p>
            </div>
        @else
            <div class="flex flex-col gap-3">
                @foreach($this->actividades as $actividad)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $actividad->titulo }}
                                    </h4>
                                    @if($actividad->materia)
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700 font-medium">
                                            {{ $actividad->materia->nombre }}
                                        </span>
                                    @endif
                                </div>
                                @if($actividad->descripcion)
                                    <p class="text-sm text-gray-500 mt-0.5">{{ $actividad->descripcion }}</p>
                                @endif
                                @if($actividad->fecha_entrega)
                                    <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                        <x-heroicon-o-calendar class="w-3.5 h-3.5" />
                                        Entrega: {{ $actividad->fecha_entrega->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button wire:click="toggleActividad({{ $actividad->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg border transition
                                            {{ $actividadAbiertaId === $actividad->id
                                                ? 'bg-emerald-700 text-white border-emerald-700'
                                                : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border-gray-300 hover:border-gray-400' }}">
                                    <x-heroicon-o-chart-bar class="w-4 h-4" />
                                    {{ $actividadAbiertaId === $actividad->id ? 'Ocultar' : 'Calificaciones' }}
                                </button>

                                <button wire:click="mountAction('eliminarActividad', @js(['actividad_id' => $actividad->id]))"
                                        class="inline-flex items-center p-1.5 text-gray-400 hover:text-red-500 rounded-lg transition">
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        @if($actividadAbiertaId === $actividad->id)
                            <div class="border-t border-gray-100 dark:border-gray-700 overflow-x-auto">
                                @if(empty($this->calificacionesAbiertas))
                                    <div class="py-8 text-center text-gray-400 text-sm">
                                        No hay alumnos en esta clase
                                    </div>
                                @else
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="bg-gray-50 dark:bg-gray-900/50 text-left text-xs text-gray-500 uppercase tracking-wider">
                                                <th class="px-6 py-3">Nombre</th>
                                                <th class="px-6 py-3 text-center">Calificación</th>
                                                <th class="px-6 py-3 text-center">Estado</th>
                                                <th class="px-6 py-3 text-right">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                            @foreach($this->calificacionesAbiertas as $cal)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                                    <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">
                                                        {{ $cal['nombre'] }}
                                                    </td>
                                                    <td class="px-6 py-3 text-center">
                                                        @if(is_null($cal['calificacion']))
                                                            <span class="text-gray-400">—</span>
                                                        @else
                                                            <span class="text-lg font-bold
                                                                {{ $cal['calificacion'] >= 7 ? 'text-green-600' :
                                                                   ($cal['calificacion'] >= 5 ? 'text-amber-600' : 'text-red-600') }}">
                                                                {{ $cal['calificacion'] }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-3 text-center">
                                                        @if(is_null($cal['calificacion']))
                                                            <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-500">Pendiente</span>
                                                        @elseif($cal['calificacion'] == 0)
                                                            <span class="px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-600">No entregó</span>
                                                        @else
                                                            <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700">Entregado</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-3 text-right">
                                                        <button wire:click="mountAction('editarCalificacion', @js(['actividad_id' => $actividad->id, 'alumno_id' => $cal['alumno_id']]))"
                                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-medium rounded-lg border border-amber-200 transition">
                                                            <x-heroicon-o-pencil class="w-3.5 h-3.5" />
                                                            Editar
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>
        @endif

    @endif

</x-filament-panels::page>
