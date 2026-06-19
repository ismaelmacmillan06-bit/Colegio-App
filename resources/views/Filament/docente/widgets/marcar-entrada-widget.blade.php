<div class="fi-wi fi-wi-stats-overview-stat rounded-2xl overflow-hidden"
     style="background:white;border:1px solid #edf1f7;box-shadow:0 1px 4px rgba(0,0,0,0.05);">

    <div style="display:flex;align-items:center;gap:1.5rem;padding:1.5rem 2rem;flex-wrap:wrap;">

        {{-- Ícono --}}
        <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#22c55e,#15803d);display:grid;place-items:center;flex-shrink:0;">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        {{-- Info --}}
        <div style="flex:1;min-width:180px;">
            <p style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;margin:0 0 4px;">Asistencia de hoy — {{ now()->isoFormat('dddd D [de] MMMM') }}</p>

            @if($registroHoy)
                <p style="font-size:18px;font-weight:700;color:#0f172a;margin:0 0 2px;">
                    Entrada: <span style="color:#16a34a;">{{ \Carbon\Carbon::parse($registroHoy->hora_entrada)->format('H:i') }}</span>
                    @if($registroHoy->hora_salida)
                        &nbsp;·&nbsp; Salida: <span style="color:#64748b;">{{ \Carbon\Carbon::parse($registroHoy->hora_salida)->format('H:i') }}</span>
                    @endif
                </p>
                <p style="font-size:13px;color:#64748b;margin:0;">{{ $registroHoy->hora_salida ? 'Jornada completada ✓' : 'Jornada en curso' }}</p>
            @else
                <p style="font-size:18px;font-weight:700;color:#94a3b8;margin:0 0 2px;">Sin registro</p>
                <p style="font-size:13px;color:#94a3b8;margin:0;">No has marcado tu entrada hoy.</p>
            @endif
        </div>

        {{-- Botones --}}
        <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
            @if(!$registroHoy)
                <button wire:click="marcarEntrada"
                        style="display:inline-flex;align-items:center;gap:.5rem;padding:.625rem 1.25rem;background:linear-gradient(135deg,#22c55e,#15803d);color:white;font-size:14px;font-weight:600;border:none;border-radius:10px;cursor:pointer;box-shadow:0 2px 8px rgba(21,128,61,.35);">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                    </svg>
                    Marcar Entrada
                </button>
            @elseif(!$registroHoy->hora_salida)
                <button wire:click="marcarSalida"
                        style="display:inline-flex;align-items:center;gap:.5rem;padding:.625rem 1.25rem;background:linear-gradient(135deg,#f59e0b,#d97706);color:white;font-size:14px;font-weight:600;border:none;border-radius:10px;cursor:pointer;box-shadow:0 2px 8px rgba(217,119,6,.35);">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                    </svg>
                    Marcar Salida
                </button>
            @else
                <div style="display:inline-flex;align-items:center;gap:.5rem;padding:.625rem 1.25rem;background:#f0fdf4;color:#16a34a;font-size:14px;font-weight:600;border:1px solid #bbf7d0;border-radius:10px;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Jornada completa
                </div>
            @endif
        </div>
    </div>
</div>
