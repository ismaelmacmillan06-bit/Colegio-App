<x-filament-panels::page>

    @if($this->clases->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <x-heroicon-o-academic-cap class="w-16 h-16 text-gray-300 mb-4" />
            <p class="text-gray-500 text-lg font-medium">No hay clases activas registradas</p>
            <p class="text-gray-400 text-sm mt-1">Ve al módulo <strong>Escuela → Clases</strong> para crearlas.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($this->clases as $clase)
                @php
                    $titular = $clase->docentes->first();
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col transition hover:shadow-md">

                    {{-- Header de color --}}
                    <div class="h-2 w-full" style="background-color: #00004E;"></div>

                    <div class="p-6 flex flex-col gap-4 flex-1">

                        {{-- Nombre de clase + nivel --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $clase->nombre }}</h3>
                            <span class="inline-block mt-1 text-xs font-medium px-2 py-0.5 rounded-full
                                {{ $clase->nivel === 'Primaria' ? 'bg-blue-100 text-blue-700' :
                                   ($clase->nivel === 'Preescolar' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700') }}">
                                {{ $clase->nivel }}
                            </span>
                        </div>

                        {{-- Estadísticas --}}
                        <div class="flex gap-4">
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                <x-heroicon-o-user-group class="w-4 h-4 text-gray-400" />
                                <span><strong>{{ $clase->alumnos_count }}</strong> alumnos</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                <x-heroicon-o-user class="w-4 h-4 text-gray-400" />
                                <span><strong>{{ $clase->docentes_count }}</strong> docente(s)</span>
                            </div>
                        </div>

                        {{-- Titular --}}
                        @if($titular)
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <x-heroicon-o-identification class="w-4 h-4 flex-shrink-0" />
                                <span>{{ $titular->nombre }} {{ $titular->apellidos }}</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-sm text-amber-500">
                                <x-heroicon-o-exclamation-triangle class="w-4 h-4 flex-shrink-0" />
                                <span>Sin docente titular asignado</span>
                            </div>
                        @endif

                        {{-- Botón entrar --}}
                        <div class="mt-auto pt-2">
                            <a href="/admin/trabajo-escolar/clase?claseId={{ $clase->id }}"
                               class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl text-white text-sm font-semibold transition"
                               style="background-color: #00004E;"
                               onmouseover="this.style.opacity='0.85'"
                               onmouseout="this.style.opacity='1'">
                                <x-heroicon-o-arrow-right-circle class="w-5 h-5" />
                                Entrar
                            </a>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif

</x-filament-panels::page>
