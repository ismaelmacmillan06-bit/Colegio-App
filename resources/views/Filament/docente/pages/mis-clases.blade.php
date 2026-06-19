<x-filament-panels::page>

    @if(!$this->docente)
        <div class="py-16 text-center text-gray-400">
            <x-heroicon-o-exclamation-triangle class="w-12 h-12 mx-auto mb-3 text-amber-400" />
            <p class="font-medium text-gray-600">No se encontró tu perfil docente.</p>
            <p class="text-sm mt-1">Contacta al administrador del colegio.</p>
        </div>
    @elseif($this->clases->isEmpty())
        <div class="py-16 text-center text-gray-400">
            <x-heroicon-o-academic-cap class="w-12 h-12 mx-auto mb-3 text-gray-300" />
            <p class="font-medium text-gray-600">Aún no tienes clases asignadas.</p>
            <p class="text-sm mt-1">El administrador del colegio debe asignarte a una clase.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($this->clases as $clase)
                <a href="{{ url('/docente/mis-clases/clase?claseId=' . $clase->id) }}"
                   class="group block bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-green-300 transition-all duration-200 overflow-hidden">

                    {{-- Header verde --}}
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700"
                         style="background:linear-gradient(135deg,#047857,#059669);">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-white/20 border border-white/30 grid place-items-center flex-shrink-0">
                                <x-heroicon-o-user-group class="w-5 h-5 text-white" />
                            </div>
                            <span class="text-xs font-bold text-white/80 uppercase tracking-wider">{{ $clase->nivel }}</span>
                        </div>
                        <h3 class="mt-3 text-lg font-bold text-white leading-tight">{{ $clase->nombre }}</h3>
                    </div>

                    {{-- Body --}}
                    <div class="px-5 py-4">
                        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <span class="flex items-center gap-1.5">
                                <x-heroicon-o-users class="w-4 h-4" />
                                {{ $clase->alumnos_count }} alumnos
                            </span>
                            @if($clase->fecha_fin)
                                <span class="flex items-center gap-1.5">
                                    <x-heroicon-o-calendar class="w-4 h-4" />
                                    Fin: {{ $clase->fecha_fin->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>

                        {{-- Materias del docente para esta clase --}}
                        @php
                            $materias = $this->docente->materias()->where('activo', true)->get();
                        @endphp
                        @if($materias->isNotEmpty())
                            <div class="flex flex-wrap gap-1 mb-4">
                                @foreach($materias->take(4) as $mat)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-emerald-100 text-emerald-700 font-medium">
                                        {{ $mat->nombre }}
                                    </span>
                                @endforeach
                                @if($materias->count() > 4)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-500">
                                        +{{ $materias->count() - 4 }} más
                                    </span>
                                @endif
                            </div>
                        @endif

                        <div class="flex items-center text-sm font-semibold text-emerald-600 group-hover:text-emerald-700 transition">
                            Ver clase
                            <x-heroicon-o-arrow-right class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" />
                        </div>
                    </div>

                </a>
            @endforeach
        </div>
    @endif

</x-filament-panels::page>
