<div style="width: 100%; padding: 0.5rem 0;">

    {{-- HEADER --}}
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text-primary); margin: 0;">Dashboard Escolar</h1>
        <p style="color: var(--color-text-secondary); font-size: 0.85rem; margin: 4px 0 0;">{{ $fecha }}</p>
    </div>

    {{-- STATS CARDS --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem;">

        <div style="background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: 12px; padding: 1.25rem;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 0.75rem;">
                <div style="width: 36px; height: 36px; background: #e0e7ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">🏫</div>
                <span style="font-size: 0.78rem; color: var(--color-text-secondary); line-height: 1.2;">Directivos activos</span>
            </div>
            <div style="font-size: 1.8rem; font-weight: 700; color: var(--color-text-primary);">{{ $total_directivos }}</div>
        </div>

        <div style="background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: 12px; padding: 1.25rem;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 0.75rem;">
                <div style="width: 36px; height: 36px; background: #d1fae5; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">👨‍🏫</div>
                <span style="font-size: 0.78rem; color: var(--color-text-secondary); line-height: 1.2;">Maestros extracurriculares</span>
            </div>
            <div style="font-size: 1.8rem; font-weight: 700; color: var(--color-text-primary);">{{ $total_extracurriculares }}</div>
        </div>

        <div style="background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: 12px; padding: 1.25rem;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 0.75rem;">
                <div style="width: 36px; height: 36px; background: #fef3c7; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">🎒</div>
                <span style="font-size: 0.78rem; color: var(--color-text-secondary); line-height: 1.2;">Alumnos registrados</span>
            </div>
            <div style="font-size: 1.8rem; font-weight: 700; color: var(--color-text-primary);">{{ $total_alumnos }}</div>
            <div style="margin-top: 6px; display: flex; flex-wrap: wrap; gap: 4px;">
                @foreach($alumnos_por_nivel as $nivel => $total)
                <span style="font-size: 0.68rem; background: #e0e7ff; color: #3730a3; padding: 2px 7px; border-radius: 99px;">{{ $nivel }}: {{ $total }}</span>
                @endforeach
            </div>
        </div>

        <div style="background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: 12px; padding: 1.25rem;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 0.75rem;">
                <div style="width: 36px; height: 36px; background: #fce7f3; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">📊</div>
                <span style="font-size: 0.78rem; color: var(--color-text-secondary); line-height: 1.2;">Asistencia general hoy</span>
            </div>
            <div style="font-size: 1.8rem; font-weight: 700; color: var(--color-text-primary);">{{ $asistencia_general }}%</div>
            <div style="margin-top: 6px;">
                <span style="font-size: 0.68rem; background: #d1fae5; color: #065f46; padding: 2px 7px; border-radius: 99px;">{{ $total_presentes }} presentes hoy</span>
            </div>
        </div>

    </div>

    {{-- LAYOUT PRINCIPAL --}}
    <div style="display: flex; gap: 1.5rem; align-items: flex-start;">

        {{-- SIDEBAR IZQUIERDO --}}
        <div style="width: 210px; flex-shrink: 0;">

            @if($directivos->count() > 0)
            <div style="background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: 12px; padding: 1rem; margin-bottom: 1rem;">
                <p style="font-size: 0.72rem; font-weight: 600; color: var(--color-text-secondary); text-transform: uppercase; letter-spacing: 1px; margin: 0 0 0.75rem;">Directivos</p>
                @foreach($directivos as $d)
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 0.6rem;">
                    @if($d['foto'])
                        <img src="{{ asset('storage/' . $d['foto']) }}" style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                    @else
                        <div style="width: 34px; height: 34px; border-radius: 50%; background: #e0e7ff; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 600; color: #3730a3; flex-shrink: 0;">{{ substr($d['nombre'], 0, 1) }}</div>
                    @endif
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 0.78rem; font-weight: 500; color: var(--color-text-primary); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $d['nombre'] }}</div>
                        <div style="font-size: 0.7rem; color: var(--color-text-secondary);">{{ $d['cargo'] }}</div>
                        <span style="font-size: 0.65rem; padding: 1px 6px; border-radius: 99px; background: {{ $d['llego'] ? '#d1fae5' : '#fef3c7' }}; color: {{ $d['llego'] ? '#065f46' : '#92400e' }};">{{ $d['llego'] ? '● Llegó' : '● Pendiente' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if($extracurriculares->count() > 0)
            <div style="background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: 12px; padding: 1rem;">
                <p style="font-size: 0.72rem; font-weight: 600; color: var(--color-text-secondary); text-transform: uppercase; letter-spacing: 1px; margin: 0 0 0.75rem;">Extracurriculares</p>
                @foreach($extracurriculares as $e)
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 0.6rem;">
                    @if($e['foto'])
                        <img src="{{ asset('storage/' . $e['foto']) }}" style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                    @else
                        <div style="width: 34px; height: 34px; border-radius: 50%; background: #d1fae5; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 600; color: #065f46; flex-shrink: 0;">{{ substr($e['nombre'], 0, 1) }}</div>
                    @endif
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 0.78rem; font-weight: 500; color: var(--color-text-primary); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $e['nombre'] }}</div>
                        <div style="font-size: 0.7rem; color: var(--color-text-secondary);">{{ $e['materia'] }}</div>
                        <span style="font-size: 0.65rem; padding: 1px 6px; border-radius: 99px; background: {{ $e['llego'] ? '#d1fae5' : '#fef3c7' }}; color: {{ $e['llego'] ? '#065f46' : '#92400e' }};">{{ $e['llego'] ? '● Llegó' : '● Pendiente' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if($directivos->count() === 0 && $extracurriculares->count() === 0)
            <div style="background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: 12px; padding: 1rem; text-align: center; color: var(--color-text-secondary); font-size: 0.8rem;">
                Sin personal registrado
            </div>
            @endif

        </div>

        {{-- CLASES --}}
        <div style="flex: 1; min-width: 0;">
            <p style="font-size: 0.9rem; font-weight: 600; color: var(--color-text-primary); margin: 0 0 1rem;">Asistencias en tiempo real</p>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem;">

                @forelse($clases as $clase)
                <div style="background: var(--color-background-primary); border: 0.5px solid var(--color-border-tertiary); border-radius: 14px; padding: 1rem;">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <span style="font-size: 0.68rem; padding: 2px 8px; border-radius: 99px; background: {{ $clase['presentes'] > 0 ? '#d1fae5' : '#f3f4f6' }}; color: {{ $clase['presentes'] > 0 ? '#065f46' : '#6b7280' }};">
                            {{ $clase['presentes'] > 0 ? '● Activa' : '● Sin registros' }}
                        </span>
                        <span style="font-size: 1.4rem; font-weight: 800; color: #1e3a8a;">{{ $clase['porcentaje'] }}%</span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 0.75rem;">
                        @if($clase['docente_foto'])
                            <img src="{{ asset('storage/' . $clase['docente_foto']) }}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                        @else
                            <div style="width: 38px; height: 38px; border-radius: 50%; background: #e0e7ff; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 600; color: #3730a3; flex-shrink: 0;">{{ substr($clase['docente_nombre'], 0, 1) }}</div>
                        @endif
                        <div style="min-width: 0;">
                            <div style="font-weight: 600; font-size: 0.9rem; color: var(--color-text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $clase['nombre'] }}</div>
                            <div style="font-size: 0.75rem; color: var(--color-text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $clase['docente_nombre'] }}</div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <div style="background: var(--color-background-secondary); border-radius: 8px; padding: 0.5rem;">
                            <div style="font-size: 0.68rem; color: var(--color-text-secondary);">Ya llegó</div>
                            <div style="font-size: 0.85rem; font-weight: 600; color: {{ $clase['maestro_llego'] ? '#16a34a' : '#dc2626' }};">
                                {{ $clase['maestro_llego'] ? 'Sí ✅' : 'No ❌' }}
                            </div>
                        </div>
                        <div style="background: var(--color-background-secondary); border-radius: 8px; padding: 0.5rem;">
                            <div style="font-size: 0.68rem; color: var(--color-text-secondary);">Alumnos</div>
                            <div style="font-size: 0.85rem; font-weight: 600; color: var(--color-text-primary);">{{ $clase['presentes'] }} / {{ $clase['total'] }}</div>
                        </div>
                    </div>

                    <div style="width: 100%; background: #e5e7eb; border-radius: 99px; height: 5px; margin-bottom: 0.75rem;">
                        <div style="background: #2563eb; height: 5px; border-radius: 99px; width: {{ $clase['porcentaje'] }}%;"></div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.7rem; color: var(--color-text-secondary);">
                            {{ $clase['ultimo_acceso'] ? 'Último: ' . $clase['ultimo_acceso'] : 'Sin registros hoy' }}
                        </span>
                        <a href="{{ url('/admin/alumnos?tableFilters[clase_id][value]=' . $clase['id']) }}"
                           style="font-size: 0.72rem; background: #1e3a8a; color: white; padding: 4px 12px; border-radius: 6px; text-decoration: none; font-weight: 500; white-space: nowrap;">
                            Ver grupo
                        </a>
                    </div>

                </div>
                @empty
                <div style="text-align: center; color: var(--color-text-secondary); padding: 2rem; grid-column: 1/-1;">
                    No hay clases registradas aún.
                </div>
                @endforelse

            </div>
        </div>

    </div>
</div>