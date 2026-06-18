@php
    $periodos      = $this->periodos;
    $matrix        = $this->matrizData;
    $stats         = $this->stats;
    $periodoActual = \App\Models\Colegiatura::generarPeriodo('Bimestral');

    $statusCfg = [
        'pagada'         => ['icon' => '✓', 'bg' => '#d1fae5', 'color' => '#065f46', 'ring' => '#6ee7b7', 'label' => 'Pagada'],
        'proximo_vencer' => ['icon' => '⚠', 'bg' => '#ffedd5', 'color' => '#9a3412', 'ring' => '#fdba74', 'label' => 'Próximo a vencer'],
        'pendiente'      => ['icon' => '◷', 'bg' => '#fef9c3', 'color' => '#854d0e', 'ring' => '#fde047', 'label' => 'Pendiente'],
        'vencida'        => ['icon' => '✗', 'bg' => '#fee2e2', 'color' => '#991b1b', 'ring' => '#fca5a5', 'label' => 'Vencida'],
        'declinada'      => ['icon' => '✗', 'bg' => '#f1f5f9', 'color' => '#64748b', 'ring' => '#cbd5e1', 'label' => 'Declinada'],
    ];

    $nivelColors = [
        'Maternal'     => ['bg' => '#e0f2fe', 'color' => '#0369a1'],
        'Preescolar'   => ['bg' => '#d1fae5', 'color' => '#065f46'],
        'Primaria'     => ['bg' => '#fef3c7', 'color' => '#92400e'],
        'Secundaria'   => ['bg' => '#fee2e2', 'color' => '#991b1b'],
        'Bachillerato' => ['bg' => '#ede9fe', 'color' => '#4c1d95'],
        'Licenciatura' => ['bg' => '#fce7f3', 'color' => '#831843'],
    ];
@endphp

<x-filament-panels::page>

{{-- WhatsApp opener: listens for open-url event from Livewire --}}
<div x-data x-on:open-url.window="window.open($event.detail.url, '_blank')" style="display:none;"></div>

{{-- ═══ STATS ════════════════════════════════════════════ --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">

    <div style="background: linear-gradient(135deg,#10b981,#059669); border-radius:1rem; padding:1.25rem 1.5rem; color:white; box-shadow:0 4px 14px rgba(16,185,129,0.3);">
        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;opacity:.8;margin-bottom:.5rem;">Recaudado</div>
        <div style="font-size:1.55rem;font-weight:800;line-height:1;">${{ number_format($stats['recaudado'],0) }}</div>
        <div style="font-size:.72rem;opacity:.75;margin-top:.3rem;">{{ $stats['periodoActual'] }}</div>
    </div>

    <div style="background:white;border-radius:1rem;padding:1.25rem 1.5rem;border:1px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,.05);">
        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#64748b;margin-bottom:.5rem;">Al Corriente</div>
        <div style="font-size:1.55rem;font-weight:800;line-height:1;color:#059669;">{{ $stats['alCorriente'] }}</div>
        <div style="font-size:.72rem;color:#94a3b8;margin-top:.3rem;">alumnos pagados</div>
    </div>

    <div style="background:white;border-radius:1rem;padding:1.25rem 1.5rem;border:1px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,.05);">
        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#64748b;margin-bottom:.5rem;">Pendientes</div>
        <div style="font-size:1.55rem;font-weight:800;line-height:1;color:#d97706;">{{ $stats['pendientes'] }}</div>
        <div style="font-size:.72rem;color:#94a3b8;margin-top:.3rem;">sin pagar</div>
    </div>

    <div style="background:white;border-radius:1rem;padding:1.25rem 1.5rem;border:1px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,.05);">
        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#64748b;margin-bottom:.5rem;">Vencidas</div>
        <div style="font-size:1.55rem;font-weight:800;line-height:1;color:#dc2626;">{{ $stats['vencidas'] }}</div>
        <div style="font-size:.72rem;color:#94a3b8;margin-top:.3rem;">fuera de plazo</div>
    </div>

    <div style="background:white;border-radius:1rem;padding:1.25rem 1.5rem;border:1px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,.05);">
        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#64748b;margin-bottom:.5rem;">% Cobranza</div>
        <div style="font-size:1.55rem;font-weight:800;line-height:1;color:{{ $stats['pct']>=80?'#059669':($stats['pct']>=50?'#d97706':'#dc2626') }};">{{ $stats['pct'] }}%</div>
        <div style="margin-top:.75rem;background:#f1f5f9;border-radius:100px;height:5px;">
            <div style="width:{{ $stats['pct'] }}%;height:5px;border-radius:100px;background:{{ $stats['pct']>=80?'#10b981':($stats['pct']>=50?'#f59e0b':'#ef4444') }};"></div>
        </div>
    </div>

    {{-- Becas card --}}
    <div style="background: linear-gradient(135deg,#ede9fe,#ddd6fe); border-radius:1rem; padding:1.25rem 1.5rem; border:1px solid #c4b5fd; box-shadow:0 1px 4px rgba(0,0,0,.05);">
        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#4c1d95;margin-bottom:.5rem;">🎓 Becas</div>
        <div style="font-size:1.55rem;font-weight:800;line-height:1;color:#4c1d95;">{{ $stats['becasCount'] }}</div>
        <div style="font-size:.72rem;color:#6d28d9;margin-top:.3rem;">
            @if($stats['totalDescuentos'] > 0)
                -${{ number_format($stats['totalDescuentos'],0) }} en descuentos
            @else
                sin descuentos aplicados
            @endif
        </div>
    </div>

</div>

{{-- ═══ TOOLBAR ═══════════════════════════════════════════ --}}
<div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;margin-bottom:1rem;">

    <div style="position:relative;flex:1;min-width:200px;max-width:340px;">
        <svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);width:15px;height:15px;color:#94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
        </svg>
        <input wire:model.live.debounce.300ms="searchAlumno" type="text" placeholder="Buscar alumno..."
            style="width:100%;padding:.52rem .75rem .52rem 2.2rem;border:1px solid #e2e8f0;border-radius:.625rem;font-size:.85rem;color:#0f172a;background:white;outline:none;"
            onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
    </div>

    <div style="display:flex;align-items:center;gap:.5rem;background:white;border:1px solid #e2e8f0;border-radius:.625rem;padding:.38rem .75rem;">
        <button wire:click="prevYear" style="background:none;border:none;cursor:pointer;color:#64748b;padding:.1rem .35rem;border-radius:.375rem;font-size:1rem;">‹</button>
        <span style="font-size:.875rem;font-weight:600;color:#0f172a;white-space:nowrap;">{{ $schoolYear }}–{{ $schoolYear+1 }}</span>
        <button wire:click="nextYear" style="background:none;border:none;cursor:pointer;color:#64748b;padding:.1rem .35rem;border-radius:.375rem;font-size:1rem;">›</button>
    </div>

    <button wire:click="generarPeriodoActual"
        style="display:flex;align-items:center;gap:.4rem;background:#4f46e5;color:white;border:none;padding:.52rem 1rem;border-radius:.625rem;font-size:.82rem;font-weight:600;cursor:pointer;white-space:nowrap;">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
        </svg>
        Generar período
    </button>

    <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;margin-left:auto;">
        @foreach(['pagada'=>'✓ Pagada','pendiente'=>'◷ Pendiente','proximo_vencer'=>'⚠ Por vencer','vencida'=>'✗ Vencida'] as $s=>$lbl)
        <span style="font-size:.72rem;font-weight:600;padding:.22rem .6rem;border-radius:100px;background:{{ $statusCfg[$s]['bg'] }};color:{{ $statusCfg[$s]['color'] }};">{{ $lbl }}</span>
        @endforeach
        <span style="font-size:.72rem;font-weight:600;padding:.22rem .6rem;border-radius:100px;background:#f8fafc;color:#94a3b8;border:1px dashed #cbd5e1;">— Sin registro</span>
    </div>
</div>

{{-- ═══ MATRIX TABLE ══════════════════════════════════════ --}}
<div style="overflow-x:auto;border-radius:1rem;box-shadow:0 1px 4px rgba(0,0,0,.06);background:white;border:1px solid #e2e8f0;">
    <table style="width:100%;border-collapse:collapse;font-size:.84rem;">
        <thead>
            <tr style="border-bottom:2px solid #f1f5f9;">
                <th style="text-align:left;padding:.875rem 1rem;color:#475569;font-weight:700;font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;white-space:nowrap;min-width:180px;">Alumno</th>
                <th style="text-align:left;padding:.875rem .75rem;color:#475569;font-weight:700;font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;white-space:nowrap;">Nivel</th>
                @foreach($periodos as $periodo)
                <th style="text-align:center;padding:.875rem .625rem;color:{{ $periodo===$periodoActual?'#4f46e5':'#475569' }};font-weight:{{ $periodo===$periodoActual?'800':'700' }};font-size:.7rem;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;background:{{ $periodo===$periodoActual?'#eef2ff':'transparent' }};min-width:88px;">
                    {{ $periodo }}
                    @if($periodo===$periodoActual)
                    <span style="display:block;font-size:.58rem;font-weight:600;color:#4f46e5;text-transform:none;letter-spacing:0;">Actual</span>
                    @endif
                </th>
                @endforeach
                <th style="text-align:center;padding:.875rem .75rem;color:#475569;font-weight:700;font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;white-space:nowrap;min-width:80px;">Notificar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matrix as $idx => $row)
            @php
                $alumno = $row['alumno'];
                $nc     = $nivelColors[$row['nivel']] ?? ['bg'=>'#f8fafc','color'=>'#64748b'];
                $isOdd  = $idx % 2 === 1;
                // Find the most recent colegiatura for notify default
                $ultimaColegiatura = $row['periodos']->filter()->last();
            @endphp
            <tr style="border-bottom:1px solid #f8fafc;{{ $isOdd?'background:#fafbfd;':'' }}"
                onmouseover="this.style.background='#f0f4ff'" onmouseout="this.style.background='{{ $isOdd?'#fafbfd':'transparent' }}'">

                <td style="padding:.75rem 1rem;white-space:nowrap;">
                    <div style="font-weight:600;color:#0f172a;font-size:.85rem;">{{ $alumno->apellidos }}, {{ $alumno->nombre }}</div>
                    <div style="font-size:.72rem;color:#94a3b8;margin-top:1px;">{{ $row['clase'] }}</div>
                </td>

                <td style="padding:.75rem .75rem;white-space:nowrap;">
                    <span style="font-size:.72rem;font-weight:700;padding:.25rem .7rem;border-radius:100px;background:{{ $nc['bg'] }};color:{{ $nc['color'] }};">{{ $row['nivel'] }}</span>
                </td>

                @foreach($periodos as $periodo)
                @php $col = $row['periodos'][$periodo] ?? null; @endphp
                <td style="padding:.6rem .5rem;text-align:center;background:{{ $periodo===$periodoActual?'rgba(79,70,229,.03)':'transparent' }};">
                    @if($col)
                    @php $cfg = $statusCfg[$col->status] ?? $statusCfg['pendiente']; @endphp

                    @if(in_array($col->status,['pendiente','proximo_vencer']))
                    <button wire:click="abrirModalPago({{ $col->id }})"
                        title="{{ $cfg['label'] }} — clic para registrar pago"
                        style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;border:2px solid {{ $cfg['ring'] }};background:{{ $cfg['bg'] }};color:{{ $cfg['color'] }};cursor:pointer;font-size:1rem;font-weight:700;transition:transform .1s,box-shadow .1s;"
                        onmouseover="this.style.transform='scale(1.12)';this.style.boxShadow='0 2px 8px rgba(0,0,0,.15)'"
                        onmouseout="this.style.transform='scale(1)';this.style.boxShadow='none'">
                        {{ $cfg['icon'] }}
                    </button>
                    @else
                    <span title="{{ $cfg['label'] }}{{ $col->status==='pagada'&&$col->fecha_pago?' · Pagada '.$col->fecha_pago->format('d/m/Y'):'' }}{{ $col->descuento_pct>0?' · Beca '.$col->descuento_pct.'%':'' }}"
                        style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;border:2px solid {{ $cfg['ring'] }};background:{{ $cfg['bg'] }};color:{{ $cfg['color'] }};font-size:1rem;font-weight:700;position:relative;">
                        {{ $cfg['icon'] }}
                        @if($col->descuento_pct>0)
                        <span style="position:absolute;top:-4px;right:-4px;background:#7c3aed;color:white;font-size:.55rem;font-weight:700;padding:1px 4px;border-radius:100px;line-height:1.4;">{{ $col->descuento_pct }}%</span>
                        @endif
                    </span>
                    @endif

                    @else
                    <button wire:click="abrirModalGenerar({{ $alumno->id }},'{{ $periodo }}')"
                        title="Sin colegiatura — clic para generar"
                        style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;border:2px dashed #cbd5e1;background:transparent;color:#cbd5e1;cursor:pointer;font-size:1.2rem;transition:border-color .1s,color .1s;"
                        onmouseover="this.style.borderColor='#6366f1';this.style.color='#6366f1'"
                        onmouseout="this.style.borderColor='#cbd5e1';this.style.color='#cbd5e1'">
                        +
                    </button>
                    @endif
                </td>
                @endforeach

                {{-- Notificar --}}
                <td style="padding:.6rem .75rem;text-align:center;">
                    <button wire:click="abrirModalNotificar({{ $alumno->id }},{{ $ultimaColegiatura?->id ?? 'null' }})"
                        title="Notificar al padre/madre/tutor de {{ $alumno->nombre }}"
                        style="display:inline-flex;align-items:center;justify-content:center;gap:.3rem;padding:.4rem .75rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:.625rem;color:#059669;font-size:.75rem;font-weight:700;cursor:pointer;transition:background .1s;"
                        onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                        </svg>
                        Notificar
                    </button>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="{{ 3 + count($periodos) }}" style="padding:3rem;text-align:center;color:#94a3b8;">
                    <div style="font-size:2rem;margin-bottom:.5rem;">💳</div>
                    <div style="font-weight:600;color:#475569;">Sin alumnos registrados</div>
                    <div style="font-size:.83rem;margin-top:.25rem;">Da de alta alumnos y genera su colegiatura del período actual.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($matrix->isNotEmpty())
<div style="margin-top:.75rem;font-size:.75rem;color:#94a3b8;text-align:right;">
    {{ $matrix->count() }} alumno(s) · Ciclo {{ $schoolYear }}–{{ $schoolYear+1 }}
    · Toca <strong style="color:#4f46e5">◷</strong> o <strong style="color:#d97706">⚠</strong> para registrar el pago
</div>
@endif


{{-- ═══ MODAL: PAGO + BECA ════════════════════════════════ --}}
@if($modalPago && $this->colegiaturaSeleccionada)
@php $c = $this->colegiaturaSeleccionada; @endphp
<div wire:click.self="cerrarModal"
    style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.45);backdrop-filter:blur(3px);">
    <div style="background:white;border-radius:1.25rem;padding:2rem;max-width:440px;width:calc(100% - 2rem);box-shadow:0 20px 50px rgba(0,0,0,.2);max-height:90vh;overflow-y:auto;">

        <div style="text-align:center;margin-bottom:1.5rem;">
            <div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:50%;background:#d1fae5;margin-bottom:1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:28px;height:28px;color:#059669;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                </svg>
            </div>
            <h3 style="font-size:1.15rem;font-weight:800;color:#0f172a;margin:0 0 .3rem;">¿Confirmar pago?</h3>
            <p style="font-size:.85rem;color:#64748b;margin:0;">Esta acción registra la colegiatura como pagada.</p>
        </div>

        {{-- Info box --}}
        <div style="background:#f8fafc;border-radius:.875rem;padding:1rem 1.25rem;margin-bottom:1.25rem;border:1px solid #e2e8f0;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.5rem;">
                <span style="font-size:.75rem;color:#64748b;font-weight:600;">Alumno</span>
                <span style="font-size:.85rem;font-weight:700;color:#0f172a;">{{ $c->alumno?->apellidos }}, {{ $c->alumno?->nombre }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.5rem;">
                <span style="font-size:.75rem;color:#64748b;font-weight:600;">Período</span>
                <span style="font-size:.85rem;font-weight:600;color:#4f46e5;">{{ $c->periodo }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:.75rem;color:#64748b;font-weight:600;">Monto</span>
                <span style="font-size:1.1rem;font-weight:800;color:#059669;">
                    @if($becaPct > 0)
                        ${{ number_format(($c->monto_original ?? $c->monto) * (1 - $becaPct/100), 0) }} MXN
                        <span style="font-size:.72rem;color:#94a3b8;text-decoration:line-through;margin-left:4px;">${{ number_format($c->monto_original ?? $c->monto,0) }}</span>
                    @else
                        ${{ number_format($c->monto,0) }} MXN
                    @endif
                </span>
            </div>
        </div>

        {{-- Beca toggle --}}
        <button wire:click="toggleBeca"
            style="width:100%;display:flex;align-items:center;justify-content:space-between;background:{{ $mostrarBeca?'#ede9fe':'#f8fafc' }};border:1px solid {{ $mostrarBeca?'#c4b5fd':'#e2e8f0' }};border-radius:.75rem;padding:.75rem 1rem;cursor:pointer;margin-bottom:{{ $mostrarBeca?'.75rem':'1.25rem' }};">
            <span style="font-size:.85rem;font-weight:600;color:{{ $mostrarBeca?'#4c1d95':'#475569' }};">🎓 Aplicar Beca / Descuento</span>
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:{{ $mostrarBeca?'#7c3aed':'#94a3b8' }};transform:{{ $mostrarBeca?'rotate(180deg)':'rotate(0)' }};transition:transform .2s;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
            </svg>
        </button>

        @if($mostrarBeca)
        <div style="background:#faf5ff;border:1px solid #e9d5ff;border-radius:.75rem;padding:1rem 1.25rem;margin-bottom:1.25rem;">
            <label style="font-size:.8rem;font-weight:700;color:#4c1d95;display:block;margin-bottom:.6rem;">Porcentaje de descuento</label>
            <div style="display:flex;align-items:center;gap:.75rem;">
                <input wire:model.live.debounce.300ms="becaPct" type="number" min="0" max="100"
                    style="width:80px;padding:.5rem .75rem;border:2px solid #c4b5fd;border-radius:.625rem;font-size:1rem;font-weight:700;color:#4c1d95;text-align:center;outline:none;"
                    onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#c4b5fd'">
                <span style="font-size:1rem;font-weight:700;color:#6d28d9;">%</span>
                @if($becaPct > 0)
                <div style="flex:1;text-align:right;">
                    <div style="font-size:.72rem;color:#6d28d9;font-weight:600;">Monto final</div>
                    <div style="font-size:1.15rem;font-weight:800;color:#4c1d95;">
                        ${{ number_format(($c->monto_original ?? $c->monto) * (1 - $becaPct/100), 0) }} MXN
                    </div>
                </div>
                @endif
            </div>
            @if($becaPct > 0)
            <div style="margin-top:.625rem;font-size:.75rem;color:#7c3aed;">
                Ahorro: ${{ number_format(($c->monto_original ?? $c->monto) * ($becaPct/100), 0) }} MXN ({{ $becaPct }}% de descuento)
            </div>
            @endif
        </div>
        @endif

        <div style="display:flex;gap:.75rem;">
            <button wire:click="cerrarModal"
                style="flex:1;padding:.75rem;border:1px solid #e2e8f0;background:white;border-radius:.75rem;font-size:.875rem;font-weight:600;color:#475569;cursor:pointer;">
                Cancelar
            </button>
            <button wire:click="confirmarPago"
                style="flex:1;padding:.75rem;background:linear-gradient(135deg,#10b981,#059669);border:none;border-radius:.75rem;font-size:.875rem;font-weight:700;color:white;cursor:pointer;box-shadow:0 4px 12px rgba(16,185,129,.4);">
                ✓ {{ $becaPct > 0 ? 'Registrar con Beca' : 'Registrar Pago' }}
            </button>
        </div>

    </div>
</div>
@endif


{{-- ═══ MODAL: GENERAR ════════════════════════════════════ --}}
@if($modalGenerar && $alumnoIdGen)
<div wire:click.self="cerrarModal"
    style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.45);backdrop-filter:blur(3px);">
    <div style="background:white;border-radius:1.25rem;padding:2rem;max-width:400px;width:calc(100% - 2rem);box-shadow:0 20px 50px rgba(0,0,0,.2);">
        <div style="text-align:center;margin-bottom:1.5rem;">
            <div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:50%;background:#ede9fe;margin-bottom:1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:28px;height:28px;color:#7c3aed;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </div>
            <h3 style="font-size:1.1rem;font-weight:800;color:#0f172a;margin:0 0 .3rem;">Generar colegiatura</h3>
            <p style="font-size:.83rem;color:#64748b;margin:0;">Se creará con estado <strong>Pendiente</strong>:</p>
            <p style="font-size:1rem;font-weight:800;color:#4f46e5;margin:.5rem 0 0;">{{ $periodoGen }}</p>
        </div>
        <div style="display:flex;gap:.75rem;">
            <button wire:click="cerrarModal" style="flex:1;padding:.75rem;border:1px solid #e2e8f0;background:white;border-radius:.75rem;font-size:.875rem;font-weight:600;color:#475569;cursor:pointer;">Cancelar</button>
            <button wire:click="confirmarGenerar" style="flex:1;padding:.75rem;background:linear-gradient(135deg,#6366f1,#4f46e5);border:none;border-radius:.75rem;font-size:.875rem;font-weight:700;color:white;cursor:pointer;box-shadow:0 4px 12px rgba(99,102,241,.4);">+ Generar</button>
        </div>
    </div>
</div>
@endif


{{-- ═══ MODAL: NOTIFICAR ══════════════════════════════════ --}}
@if($modalNotificar && $this->alumnoNotificar)
@php $a = $this->alumnoNotificar; @endphp
<div wire:click.self="cerrarModal"
    style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.45);backdrop-filter:blur(3px);">
    <div style="background:white;border-radius:1.25rem;padding:2rem;max-width:480px;width:calc(100% - 2rem);box-shadow:0 20px 50px rgba(0,0,0,.2);max-height:90vh;overflow-y:auto;">

        {{-- Header --}}
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1.5rem;">
            <div style="background:#ecfdf5;border-radius:.75rem;padding:.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#059669;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                </svg>
            </div>
            <div>
                <h3 style="font-size:1.05rem;font-weight:800;color:#0f172a;margin:0;">Enviar Notificación</h3>
                <p style="font-size:.8rem;color:#64748b;margin:0;">{{ $a->apellidos }}, {{ $a->nombre }}</p>
            </div>
            <button wire:click="cerrarModal" style="margin-left:auto;background:none;border:none;cursor:pointer;color:#94a3b8;font-size:1.25rem;line-height:1;padding:.25rem;">✕</button>
        </div>

        {{-- Tipo de notificación --}}
        <div style="margin-bottom:1.25rem;">
            <div style="font-size:.78rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.625rem;">¿Qué deseas notificar?</div>
            <div style="display:flex;gap:.625rem;">
                <label style="flex:1;display:flex;align-items:center;gap:.625rem;padding:.75rem 1rem;border:2px solid {{ $tipoNotificacion==='pago_pendiente'?'#6366f1':'#e2e8f0' }};border-radius:.75rem;cursor:pointer;background:{{ $tipoNotificacion==='pago_pendiente'?'#eef2ff':'white' }};">
                    <input type="radio" wire:model.live="tipoNotificacion" value="pago_pendiente" style="accent-color:#4f46e5;">
                    <span>
                        <span style="display:block;font-size:.82rem;font-weight:700;color:#0f172a;">⏰ Pago pendiente</span>
                        <span style="font-size:.72rem;color:#64748b;">Recordatorio de pago</span>
                    </span>
                </label>
                <label style="flex:1;display:flex;align-items:center;gap:.625rem;padding:.75rem 1rem;border:2px solid {{ $tipoNotificacion==='pago_realizado'?'#059669':'#e2e8f0' }};border-radius:.75rem;cursor:pointer;background:{{ $tipoNotificacion==='pago_realizado'?'#f0fdf4':'white' }};">
                    <input type="radio" wire:model.live="tipoNotificacion" value="pago_realizado" style="accent-color:#10b981;">
                    <span>
                        <span style="display:block;font-size:.82rem;font-weight:700;color:#0f172a;">✅ Pago realizado</span>
                        <span style="font-size:.72rem;color:#64748b;">Confirmación de pago</span>
                    </span>
                </label>
            </div>
        </div>

        {{-- Contactos --}}
        <div style="margin-bottom:1.5rem;">
            <div style="font-size:.78rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.625rem;">¿A quién notificar?</div>
            <div style="display:flex;flex-direction:column;gap:.5rem;">

                @foreach([
                    ['tipo'=>'padre','nombre'=>$a->nombre_padre,'email'=>$a->correo_padre,'phone'=>$a->telefono_padre,'label'=>'Padre'],
                    ['tipo'=>'madre','nombre'=>$a->nombre_madre,'email'=>$a->correo_madre,'phone'=>$a->telefono_madre,'label'=>'Madre'],
                    ['tipo'=>'tutor','nombre'=>$a->nombre_tutor,'email'=>$a->correo_tutor,'phone'=>$a->telefono_tutor,'label'=>'Tutor'],
                ] as $contacto)
                @if($contacto['nombre'] || $contacto['email'] || $contacto['phone'])
                <div style="display:flex;align-items:center;gap:.75rem;padding:.875rem 1rem;background:#f8fafc;border-radius:.75rem;border:1px solid #e2e8f0;">

                    {{-- Checkbox (only if has email) --}}
                    @if($contacto['email'])
                    <input type="checkbox" wire:model.live="contactosSeleccionados" value="{{ $contacto['tipo'] }}"
                        style="width:16px;height:16px;accent-color:#4f46e5;cursor:pointer;flex-shrink:0;">
                    @else
                    <div style="width:16px;height:16px;flex-shrink:0;"></div>
                    @endif

                    {{-- Info --}}
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">{{ $contacto['label'] }}</div>
                        <div style="font-size:.85rem;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $contacto['nombre'] ?? '—' }}</div>
                        @if($contacto['email'])
                        <div style="font-size:.72rem;color:#64748b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">✉ {{ $contacto['email'] }}</div>
                        @else
                        <div style="font-size:.72rem;color:#cbd5e1;">Sin correo registrado</div>
                        @endif
                    </div>

                    {{-- WhatsApp button --}}
                    @if($contacto['phone'])
                    <button wire:click="abrirWhatsApp('{{ $contacto['tipo'] }}')"
                        title="Abrir WhatsApp con {{ $contacto['nombre'] ?? $contacto['label'] }} ({{ $contacto['phone'] }})"
                        style="display:inline-flex;align-items:center;gap:.35rem;background:#25d366;color:white;border:none;padding:.5rem .875rem;border-radius:.625rem;font-size:.75rem;font-weight:700;cursor:pointer;white-space:nowrap;flex-shrink:0;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                        </svg>
                        WhatsApp
                    </button>
                    @endif
                </div>
                @endif
                @endforeach

                @if(!$a->nombre_padre && !$a->nombre_madre && !$a->nombre_tutor)
                <div style="padding:1.25rem;text-align:center;color:#94a3b8;font-size:.85rem;">
                    Sin contactos registrados para este alumno
                </div>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div style="display:flex;gap:.75rem;">
            <button wire:click="cerrarModal"
                style="flex:1;padding:.75rem;border:1px solid #e2e8f0;background:white;border-radius:.75rem;font-size:.875rem;font-weight:600;color:#475569;cursor:pointer;">
                Cancelar
            </button>
            <button wire:click="enviarNotificacion"
                @if(empty($contactosSeleccionados)) disabled style="opacity:.5;cursor:not-allowed;" @endif
                style="flex:1;padding:.75rem;background:linear-gradient(135deg,#4f46e5,#3730a3);border:none;border-radius:.75rem;font-size:.875rem;font-weight:700;color:white;cursor:pointer;box-shadow:0 4px 12px rgba(79,70,229,.35);">
                ✉ Enviar Email
                @if(!empty($contactosSeleccionados))
                <span style="background:rgba(255,255,255,.25);border-radius:100px;padding:1px 7px;font-size:.75rem;">{{ count($contactosSeleccionados) }}</span>
                @endif
            </button>
        </div>

        <p style="text-align:center;font-size:.72rem;color:#94a3b8;margin:.875rem 0 0;">
            Los botones de WhatsApp abren WhatsApp Web con el mensaje pre-llenado.
        </p>

    </div>
</div>
@endif

</x-filament-panels::page>
