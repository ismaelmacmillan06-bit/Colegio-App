<x-filament-panels::page>

{{-- Banner "próximamente" --}}
<div style="background: linear-gradient(135deg, #1e1b6e 0%, #4F46E5 100%); border-radius: 1rem; padding: 1.5rem 2rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1.25rem;">
    <div style="background: rgba(255,255,255,0.15); border-radius: 0.75rem; padding: 0.875rem; flex-shrink: 0;">
        <svg xmlns="http://www.w3.org/2000/svg" style="width: 2rem; height: 2rem; color: white;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
        </svg>
    </div>
    <div>
        <h2 style="color: white; font-weight: 700; font-size: 1.15rem; margin: 0 0 0.25rem;">Dashboard de Progreso Académico</h2>
        <p style="color: rgba(255,255,255,0.72); font-size: 0.875rem; margin: 0;">Esta sección mostrará estadísticas del ciclo escolar. Contenido en desarrollo.</p>
    </div>
    <div style="margin-left: auto; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); color: white; font-size: 0.75rem; font-weight: 600; padding: 0.375rem 0.875rem; border-radius: 100px;">
        Próximamente
    </div>
</div>

{{-- Cards de vista previa --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">

    {{-- Card 1 --}}
    <div style="background: white; border-radius: 1rem; padding: 1.25rem 1.5rem; border: 1px solid #edf1f7; box-shadow: 0 1px 4px rgba(0,0,0,0.05);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
            <span style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Promedio General</span>
            <span style="background: #ede9fe; color: #6d28d9; font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 100px;">Por Clase</span>
        </div>
        <div style="font-size: 2.25rem; font-weight: 800; color: #0f172a; line-height: 1;">—</div>
        <div style="font-size: 0.78rem; color: #94a3b8; margin-top: 0.4rem;">Promedio de todas las clases</div>
        {{-- Placeholder bars --}}
        <div style="margin-top: 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
            @foreach(['1°A', '2°B', '3°A', '3°B'] as $clase)
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.72rem; color: #64748b;">
                <span style="width: 2rem; flex-shrink: 0;">{{ $clase }}</span>
                <div style="flex: 1; background: #f1f5f9; border-radius: 100px; height: 6px;">
                    <div style="width: {{ rand(40,90) }}%; height: 6px; border-radius: 100px; background: #c7d2fe;"></div>
                </div>
            </div>
            @endforeach
        </div>
        <p style="font-size: 0.7rem; color: #cbd5e1; margin-top: 0.75rem; font-style: italic;">datos de ejemplo</p>
    </div>

    {{-- Card 2 --}}
    <div style="background: white; border-radius: 1rem; padding: 1.25rem 1.5rem; border: 1px solid #edf1f7; box-shadow: 0 1px 4px rgba(0,0,0,0.05);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
            <span style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Campos Formativos</span>
            <span style="background: #d1fae5; color: #065f46; font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 100px;">SEP 2022</span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.65rem; margin-top: 0.5rem;">
            @php
                $campos = [
                    ['nombre' => 'Lenguajes', 'pct' => 68, 'color' => '#818cf8'],
                    ['nombre' => 'Sab. y Pens. Científico', 'pct' => 54, 'color' => '#34d399'],
                    ['nombre' => 'Naturaleza y Comunidad', 'pct' => 72, 'color' => '#fb923c'],
                    ['nombre' => 'De lo Humano y Comunit.', 'pct' => 61, 'color' => '#f472b6'],
                ];
            @endphp
            @foreach($campos as $campo)
            <div>
                <div style="display: flex; justify-content: space-between; font-size: 0.72rem; color: #475569; margin-bottom: 0.25rem;">
                    <span>{{ $campo['nombre'] }}</span>
                    <span style="font-weight: 600; color: #0f172a;">{{ $campo['pct'] }}%</span>
                </div>
                <div style="background: #f1f5f9; border-radius: 100px; height: 7px;">
                    <div style="width: {{ $campo['pct'] }}%; height: 7px; border-radius: 100px; background: {{ $campo['color'] }};"></div>
                </div>
            </div>
            @endforeach
        </div>
        <p style="font-size: 0.7rem; color: #cbd5e1; margin-top: 0.75rem; font-style: italic;">datos de ejemplo</p>
    </div>

    {{-- Card 3 --}}
    <div style="background: white; border-radius: 1rem; padding: 1.25rem 1.5rem; border: 1px solid #edf1f7; box-shadow: 0 1px 4px rgba(0,0,0,0.05);">
        <div style="margin-bottom: 0.75rem;">
            <span style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Asistencia General</span>
        </div>
        {{-- Donut placeholder --}}
        <div style="display: flex; justify-content: center; align-items: center; padding: 1.25rem 0;">
            <div style="position: relative; width: 90px; height: 90px;">
                <svg viewBox="0 0 36 36" style="transform: rotate(-90deg); width: 90px; height: 90px;">
                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="#f1f5f9" stroke-width="3.8"/>
                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="#818cf8" stroke-width="3.8"
                        stroke-dasharray="75 25" stroke-linecap="round"/>
                </svg>
                <div style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <span style="font-size: 1.15rem; font-weight: 800; color: #0f172a;">—</span>
                </div>
            </div>
        </div>
        <div style="display: flex; justify-content: center; gap: 1rem; font-size: 0.72rem; color: #64748b;">
            <span><span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #818cf8; margin-right: 4px;"></span>Presentes</span>
            <span><span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #f1f5f9; margin-right: 4px;"></span>Ausentes</span>
        </div>
        <p style="font-size: 0.7rem; color: #cbd5e1; margin-top: 0.75rem; text-align: center; font-style: italic;">datos de ejemplo</p>
    </div>

</div>

{{-- Sección inferior: tabla placeholder --}}
<div style="background: white; border-radius: 1rem; padding: 1.5rem; border: 1px solid #edf1f7; box-shadow: 0 1px 4px rgba(0,0,0,0.05);">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
        <div>
            <h3 style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin: 0 0 0.2rem;">Rendimiento por Materia</h3>
            <p style="font-size: 0.8rem; color: #94a3b8; margin: 0;">Análisis de calificaciones por campo formativo y clase</p>
        </div>
        <span style="background: #fef9c3; color: #854d0e; font-size: 0.72rem; font-weight: 600; padding: 0.35rem 0.875rem; border-radius: 100px; border: 1px solid #fde68a;">
            En desarrollo
        </span>
    </div>

    {{-- Placeholder table --}}
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.82rem;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f5f9;">
                    <th style="text-align: left; padding: 0.625rem 0.75rem; color: #64748b; font-weight: 600; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;">Materia</th>
                    <th style="text-align: left; padding: 0.625rem 0.75rem; color: #64748b; font-weight: 600; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;">Campo Formativo</th>
                    <th style="text-align: center; padding: 0.625rem 0.75rem; color: #64748b; font-weight: 600; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;">Prom. 1°</th>
                    <th style="text-align: center; padding: 0.625rem 0.75rem; color: #64748b; font-weight: 600; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;">Prom. 2°</th>
                    <th style="text-align: center; padding: 0.625rem 0.75rem; color: #64748b; font-weight: 600; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;">Prom. 3°</th>
                    <th style="text-align: center; padding: 0.625rem 0.75rem; color: #64748b; font-weight: 600; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;">General</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $materias = [
                        ['Español', 'Lenguajes', 8.4, 7.9, 8.8, 8.4],
                        ['Inglés', 'Lenguajes', 7.5, 7.2, 8.1, 7.6],
                        ['Matemáticas', 'Sab. y Pens. Científico', 6.8, 7.3, 7.1, 7.1],
                        ['Química', 'Sab. y Pens. Científico', 7.1, 6.9, 7.6, 7.2],
                        ['Historia', 'Naturaleza y Comunidad', 8.2, 8.5, 8.0, 8.2],
                    ];
                @endphp
                @foreach($materias as $i => $m)
                <tr style="border-bottom: 1px solid #f8fafc; {{ $i % 2 === 0 ? '' : 'background: #fafbfd;' }}">
                    <td style="padding: 0.7rem 0.75rem; font-weight: 500; color: #1e293b;">{{ $m[0] }}</td>
                    <td style="padding: 0.7rem 0.75rem; color: #64748b;">{{ $m[1] }}</td>
                    <td style="padding: 0.7rem 0.75rem; text-align: center; color: #475569;">{{ $m[2] }}</td>
                    <td style="padding: 0.7rem 0.75rem; text-align: center; color: #475569;">{{ $m[3] }}</td>
                    <td style="padding: 0.7rem 0.75rem; text-align: center; color: #475569;">{{ $m[4] }}</td>
                    <td style="padding: 0.7rem 0.75rem; text-align: center;">
                        <span style="background: {{ $m[5] >= 8 ? '#d1fae5' : ($m[5] >= 7 ? '#fef9c3' : '#fee2e2') }}; color: {{ $m[5] >= 8 ? '#065f46' : ($m[5] >= 7 ? '#854d0e' : '#991b1b') }}; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 100px; font-size: 0.75rem;">
                            {{ $m[5] }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <p style="font-size: 0.72rem; color: #cbd5e1; margin-top: 1rem; font-style: italic;">
        ⚠️ Los datos mostrados son de ejemplo. La funcionalidad real estará disponible próximamente.
    </p>
</div>

</x-filament-panels::page>
