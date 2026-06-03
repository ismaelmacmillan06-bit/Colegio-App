<x-filament-panels::page>

    {{-- Acciones de Filament (modales) --}}
    <x-filament-actions::modals />

    {{-- Header: info de la clase --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
        <a href="/admin/trabajo-escolar"
           class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition">
            <x-heroicon-o-arrow-left class="w-4 h-4" />
            Regresar a Clases
        </a>

        <div class="flex-1 sm:text-right">
            @php $titular = $this->clase->docentes->first(); @endphp
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
            'pase-lista'    => ['label' => 'Pase de Lista',     'icon' => 'heroicon-o-clipboard-document-check'],
            'tarea'         => ['label' => 'Tareas',            'icon' => 'heroicon-o-document-text'],
            'trabajo_clase' => ['label' => 'Trabajo en Clase',  'icon' => 'heroicon-o-pencil-square'],
            'proyecto'      => ['label' => 'Proyectos',         'icon' => 'heroicon-o-beaker'],
            'examen'        => ['label' => 'Examen',            'icon' => 'heroicon-o-academic-cap'],
            'extra'         => ['label' => 'Extras',            'icon' => 'heroicon-o-star'],
        ];
    @endphp

    <div class="mb-6 border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
        <nav class="flex gap-1 min-w-max">
            @foreach($tabs as $key => $info)
                <button wire:click="changeTab('{{ $key }}')"
                        class="flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium border-b-2 transition whitespace-nowrap
                            {{ $tab === $key
                                ? 'border-[#00004E] text-[#00004E] dark:text-white dark:border-white'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400' }}">
                    <x-dynamic-component :component="$info['icon']" class="w-4 h-4" />
                    {{ $info['label'] }}
                </button>
            @endforeach
        </nav>
    </div>

    {{-- ========================= TAB: PASE DE LISTA ========================= --}}
    @if($tab === 'pase-lista')
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Pase de Lista</h3>
                    <p class="text-sm text-gray-500">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
                </div>
                <span class="text-sm text-gray-400">
                    {{ collect($this->alumnosHoy)->where('estado', 'presente')->count() }}
                    / {{ count($this->alumnosHoy) }} presentes
                </span>
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
                                        @if($row['estado'] === 'ausente')
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
            $esPrimaria = $this->clase->nivel === 'Primaria';
        @endphp

        {{-- Barra de herramientas --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">

            {{-- Filtro de materia (solo Primaria) --}}
            @if($esPrimaria && count($this->materias) > 0)
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-sm text-gray-500 font-medium">Materia:</span>
                    <button wire:click="filtrarMateria(null)"
                            class="px-3 py-1 text-xs rounded-full border transition
                                {{ is_null($materiaId)
                                    ? 'bg-[#00004E] text-white border-[#00004E]'
                                    : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border-gray-300 hover:border-gray-400' }}">
                        Todas
                    </button>
                    @foreach($this->materias as $materia)
                        <button wire:click="filtrarMateria({{ $materia['id'] }})"
                                class="px-3 py-1 text-xs rounded-full border transition
                                    {{ $materiaId === $materia['id']
                                        ? 'bg-[#00004E] text-white border-[#00004E]'
                                        : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border-gray-300 hover:border-gray-400' }}">
                            {{ $materia['nombre'] }}
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="sm:ml-auto">
                <button wire:click="mountAction('nuevaActividad')"
                        type="button"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg shadow-sm transition"
                        style="background-color: #00004E;"
                        onmouseover="this.style.opacity='0.85'"
                        onmouseout="this.style.opacity='1'">
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

                        {{-- Cabecera de la actividad --}}
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
                                {{-- Botón ver calificaciones --}}
                                <button wire:click="toggleActividad({{ $actividad->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg border transition
                                            {{ $actividadAbiertaId === $actividad->id
                                                ? 'bg-[#00004E] text-white border-[#00004E]'
                                                : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border-gray-300 hover:border-gray-400' }}">
                                    <x-heroicon-o-chart-bar class="w-4 h-4" />
                                    {{ $actividadAbiertaId === $actividad->id ? 'Ocultar' : 'Calificaciones' }}
                                </button>

                                {{-- Botón eliminar --}}
                                <button wire:click="mountAction('eliminarActividad', @js(['actividad_id' => $actividad->id]))"
                                        class="inline-flex items-center p-1.5 text-gray-400 hover:text-red-500 rounded-lg transition">
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        {{-- Tabla de calificaciones (expandible) --}}
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
