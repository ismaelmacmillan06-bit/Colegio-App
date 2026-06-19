<div style="width: 100%; padding: 0.5rem 0;">

    {{-- HEADER --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.4rem; font-weight: 700; color: #0f172a; margin: 0;">Dashboard Escolar</h1>
            <p style="color: #6b7280; font-size: 0.82rem; margin: 3px 0 0;">{{ $fecha }} — Actualización automática cada 30 seg</p>
        </div>
        <div style="background: #ecfdf5; border: 1px solid #6ee7b7; border-radius: 8px; padding: 6px 14px; font-size: 0.78rem; color: #059669; font-weight: 600;">
            ● Sistema activo
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; margin-bottom: 1.5rem;">

        <div style="background: linear-gradient(135deg, #059669, #34d399); border-radius: 14px; padding: 1.25rem; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; right: -10px; top: -10px; width: 70px; height: 70px; background: rgba(255,255,255,0.12); border-radius: 50%;"></div>
            <div style="font-size: 0.75rem; opacity: 0.85; margin-bottom: 0.5rem; font-weight: 500;">DIRECTIVOS ACTIVOS</div>
            <div style="font-size: 2.2rem; font-weight: 800; line-height: 1;">{{ $total_directivos }}</div>
            <div style="font-size: 0.72rem; opacity: 0.75; margin-top: 6px;">Personal directivo</div>
        </div>

        <div style="background: linear-gradient(135deg, #047857, #059669); border-radius: 14px; padding: 1.25rem; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; right: -10px; top: -10px; width: 70px; height: 70px; background: rgba(255,255,255,0.12); border-radius: 50%;"></div>
            <div style="font-size: 0.75rem; opacity: 0.85; margin-bottom: 0.5rem; font-weight: 500;">EXTRACURRICULARES</div>
            <div style="font-size: 2.2rem; font-weight: 800; line-height: 1;">{{ $total_extracurriculares }}</div>
            <div style="font-size: 0.72rem; opacity: 0.75; margin-top: 6px;">Maestros activos</div>
        </div>

        <div style="background: linear-gradient(135deg, #a7f3d0, #6ee7b7); border-radius: 14px; padding: 1.25rem; color: #065f46; position: relative; overflow: hidden;">
            <div style="position: absolute; right: -10px; top: -10px; width: 70px; height: 70px; background: rgba(255,255,255,0.35); border-radius: 50%;"></div>
            <div style="font-size: 0.75rem; opacity: 0.85; margin-bottom: 0.5rem; font-weight: 600;">ALUMNOS REGISTRADOS</div>
            <div style="font-size: 2.2rem; font-weight: 800; line-height: 1;">{{ $total_alumnos }}</div>
            <div style="margin-top: 6px; display: flex; flex-wrap: wrap; gap: 4px;">
                @foreach($alumnos_por_nivel as $nivel => $total)
                <span style="font-size: 0.65rem; background: rgba(255,255,255,0.6); color: #065f46; padding: 1px 7px; border-radius: 99px; font-weight: 600;">{{ $nivel }}: {{ $total }}</span>
                @endforeach
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #064e3b, #047857); border-radius: 14px; padding: 1.25rem; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; right: -10px; top: -10px; width: 70px; height: 70px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="font-size: 0.75rem; opacity: 0.85; margin-bottom: 0.5rem; font-weight: 500;">ASISTENCIA GENERAL</div>
            <div style="font-size: 2.2rem; font-weight: 800; line-height: 1;">{{ $asistencia_general }}%</div>
            <div style="margin-top: 8px; background: rgba(255,255,255,0.2); border-radius: 99px; height: 4px;">
                <div style="background: #6ee7b7; height: 4px; border-radius: 99px; width: {{ $asistencia_general }}%;"></div>
            </div>
            <div style="font-size: 0.68rem; opacity: 0.75; margin-top: 4px;">{{ $total_presentes }} presentes hoy</div>
        </div>

        {{-- FALTAS HOY --}}
        <div style="background: linear-gradient(135deg, #dc2626, #f97316); border-radius: 14px; padding: 1.25rem; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; right: -10px; top: -10px; width: 70px; height: 70px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="font-size: 0.75rem; opacity: 0.85; margin-bottom: 0.5rem; font-weight: 500;">FALTAS HOY</div>
            <div style="font-size: 2.2rem; font-weight: 800; line-height: 1;">{{ $faltas_hoy }}</div>
            <div style="margin-top: 8px; background: rgba(255,255,255,0.2); border-radius: 99px; height: 4px;">
                @php $pctFaltas = $total_alumnos > 0 ? min(100, round(($faltas_hoy / $total_alumnos) * 100)) : 0; @endphp
                <div style="background: rgba(255,255,255,0.8); height: 4px; border-radius: 99px; width: {{ $pctFaltas }}%;"></div>
            </div>
            <div style="font-size: 0.68rem; opacity: 0.75; margin-top: 4px;">
                {{ $clases_cortadas }} clase(s) con corte registrado
            </div>
        </div>

    </div>

    {{-- LAYOUT PRINCIPAL --}}
    <div style="display: flex; gap: 1.25rem; align-items: flex-start;">

        {{-- SIDEBAR IZQUIERDO --}}
        <div style="width: 200px; flex-shrink: 0;">

            @if($directivos->count() > 0)
            <div style="background: white; border: 1px solid #a7f3d0; border-radius: 14px; padding: 1rem; margin-bottom: 1rem; box-shadow: 0 2px 12px rgba(16,185,129,0.08);">
                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 0.75rem;">
                    <div style="width: 4px; height: 14px; background: #059669; border-radius: 99px;"></div>
                    <p style="font-size: 0.7rem; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: 1px; margin: 0;">Directivos</p>
                </div>
                @foreach($directivos as $d)
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 0.65rem; padding-bottom: 0.65rem; border-bottom: 0.5px solid #ecfdf5;">
                    @if($d['foto'])
                        <img src="{{ asset('storage/' . $d['foto']) }}" style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 2px solid #6ee7b7;">
                    @else
                        <div style="width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, #059669, #34d399); display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 700; color: white; flex-shrink: 0;">{{ substr($d['nombre'], 0, 1) }}</div>
                    @endif
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 0.75rem; font-weight: 600; color: #0f172a; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $d['nombre'] }}</div>
                        <div style="font-size: 0.68rem; color: #6b7280;">{{ $d['cargo'] }}</div>
                        <span style="font-size: 0.62rem; padding: 1px 6px; border-radius: 99px; font-weight: 600;
                            background: {{ $d['llego'] ? '#d1fae5' : '#fff7ed' }};
                            color: {{ $d['llego'] ? '#065f46' : '#c2410c' }};">
                            {{ $d['llego'] ? '● Llegó' : '● Pendiente' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if($extracurriculares->count() > 0)
            <div style="background: white; border: 1px solid #a7f3d0; border-radius: 14px; padding: 1rem; box-shadow: 0 2px 12px rgba(16,185,129,0.08);">
                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 0.75rem;">
                    <div style="width: 4px; height: 14px; background: #34d399; border-radius: 99px;"></div>
                    <p style="font-size: 0.7rem; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: 1px; margin: 0;">Extracurriculares</p>
                </div>
                @foreach($extracurriculares as $e)
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 0.65rem; padding-bottom: 0.65rem; border-bottom: 0.5px solid #ecfdf5;">
                    @if($e['foto'])
                        <img src="{{ asset('storage/' . $e['foto']) }}" style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 2px solid #a7f3d0;">
                    @else
                        <div style="width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, #34d399, #a7f3d0); display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 700; color: #065f46; flex-shrink: 0;">{{ substr($e['nombre'], 0, 1) }}</div>
                    @endif
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 0.75rem; font-weight: 600; color: #0f172a; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $e['nombre'] }}</div>
                        <div style="font-size: 0.68rem; color: #6b7280;">{{ $e['materia'] }}</div>
                        <span style="font-size: 0.62rem; padding: 1px 6px; border-radius: 99px; font-weight: 600;
                            background: {{ $e['llego'] ? '#d1fae5' : '#fff7ed' }};
                            color: {{ $e['llego'] ? '#065f46' : '#c2410c' }};">
                            {{ $e['llego'] ? '● Llegó' : '● Pendiente' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if($directivos->count() === 0 && $extracurriculares->count() === 0)
            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 14px; padding: 1rem; text-align: center; color: #6ee7b7; font-size: 0.8rem;">
                Sin personal registrado
            </div>
            @endif

        </div>

        {{-- CLASES --}}
        <div style="flex: 1; min-width: 0;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                <div style="width: 4px; height: 18px; background: linear-gradient(to bottom, #059669, #34d399); border-radius: 99px;"></div>
                <p style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin: 0;">Asistencias en tiempo real</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 1rem;">

                @forelse($clases as $clase)
                <div style="background: white; border: 1px solid #a7f3d0; border-radius: 16px; padding: 1rem; box-shadow: 0 2px 12px rgba(16,185,129,0.07); transition: box-shadow 0.2s;">

                    {{-- Badge + porcentaje --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <span style="font-size: 0.65rem; padding: 3px 9px; border-radius: 99px; font-weight: 600;
                            background: {{ $clase['presentes'] > 0 ? '#d1fae5' : '#ecfdf5' }};
                            color: {{ $clase['presentes'] > 0 ? '#065f46' : '#6ee7b7' }};">
                            {{ $clase['presentes'] > 0 ? '● Activa' : '● Sin registros' }}
                        </span>
                        <span style="font-size: 1.5rem; font-weight: 800; color: #059669;">{{ $clase['porcentaje'] }}%</span>
                    </div>

                    {{-- Docente + nombre clase --}}
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 0.75rem;">
                        @if($clase['docente_foto'])
                            <img src="{{ asset('storage/' . $clase['docente_foto']) }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 2px solid #6ee7b7;">
                        @else
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #059669, #34d399); display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 700; color: white; flex-shrink: 0;">{{ substr($clase['docente_nombre'], 0, 1) }}</div>
                        @endif
                        <div style="min-width: 0;">
                            <div style="font-weight: 700; font-size: 0.88rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $clase['nombre'] }}</div>
                            <div style="font-size: 0.72rem; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $clase['docente_nombre'] }}</div>
                        </div>
                    </div>

                    {{-- Métricas --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <div style="background: #ecfdf5; border-radius: 8px; padding: 0.5rem;">
                            <div style="font-size: 0.65rem; color: #6ee7b7; font-weight: 600; margin-bottom: 2px;">YA LLEGÓ</div>
                            <div style="font-size: 0.88rem; font-weight: 700; color: {{ $clase['maestro_llego'] ? '#16a34a' : '#dc2626' }};">
                                {{ $clase['maestro_llego'] ? 'Sí ✅' : 'No ❌' }}
                            </div>
                        </div>
                        <div style="background: #ecfdf5; border-radius: 8px; padding: 0.5rem;">
                            <div style="font-size: 0.65rem; color: #6ee7b7; font-weight: 600; margin-bottom: 2px;">ALUMNOS</div>
                            <div style="font-size: 0.88rem; font-weight: 700; color: #065f46;">{{ $clase['presentes'] }} / {{ $clase['total'] }}</div>
                        </div>
                    </div>

                    {{-- Barra de progreso --}}
                    <div style="width: 100%; background: #a7f3d0; border-radius: 99px; height: 6px; margin-bottom: 0.75rem;">
                        <div style="background: linear-gradient(to right, #059669, #34d399); height: 6px; border-radius: 99px; width: {{ $clase['porcentaje'] }}%;"></div>
                    </div>

                    {{-- Footer --}}
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.68rem; color: #6ee7b7;">
                            {{ $clase['ultimo_acceso'] ? '🕐 ' . $clase['ultimo_acceso'] : 'Sin registros hoy' }}
                        </span>
                        <a href="{{ url('/admin/alumnos?tableFilters[clase_id][value]=' . $clase['id']) }}"
                           style="font-size: 0.7rem; background: linear-gradient(135deg, #064e3b, #059669); color: white; padding: 5px 12px; border-radius: 8px; text-decoration: none; font-weight: 600; white-space: nowrap;">
                            Ver grupo
                        </a>
                    </div>

                </div>
                @empty
                <div style="text-align: center; color: #6ee7b7; padding: 3rem; grid-column: 1/-1; background: #ecfdf5; border-radius: 16px; border: 1px dashed #a7f3d0;">
                    No hay clases registradas aún.
                </div>
                @endforelse

            </div>
        </div>

    </div>
</div>
