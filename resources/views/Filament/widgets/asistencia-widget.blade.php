<div style="padding: 1rem;">
    <div style="margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: #1e3a5f; margin: 0;">
            Asistencia del día {{ $fecha }}
        </h2>
        <p style="color: #6b7280; font-size: 0.875rem; margin: 4px 0 0;">Escanea tu tarjeta</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
        @foreach($grupos as $grupo)
        <div style="background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 1.25rem; border: 1px solid #e5e7eb;">
            <div style="font-size: 2rem; font-weight: 800; color: #1e3a5f; margin-bottom: 4px;">
                %{{ $grupo['porcentaje'] }}
            </div>
            <div style="font-weight: 600; color: #111827; font-size: 0.95rem;">
                {{ $grupo['grupo'] }} {{ $grupo['nivel'] }}
            </div>
            <div style="color: #9ca3af; font-size: 0.8rem; margin-bottom: 0.75rem;">
                {{ $grupo['maestro'] }}
            </div>
            <div style="display: inline-block; background: #dbeafe; color: #1e40af; font-size: 0.75rem; padding: 3px 12px; border-radius: 99px; margin-bottom: 0.75rem;">
                Asistencia: {{ $grupo['presentes'] }}
            </div>
            <div style="width: 100%; background: #e5e7eb; border-radius: 99px; height: 6px;">
                <div style="background: #2563eb; height: 6px; border-radius: 99px; width: {{ $grupo['porcentaje'] }}%;"></div>
            </div>
        </div>
        @endforeach

        @if($grupos->isEmpty())
        <div style="grid-column: 1/-1; text-align: center; color: #9ca3af; padding: 2rem;">
            No hay grupos registrados aún.
        </div>
        @endif
    </div>
</div>