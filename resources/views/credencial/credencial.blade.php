<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; }

    @page {
        margin: 0;
        size: 86mm 148mm;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    .page {
        width: 86mm;
        height: 148mm;
        position: relative;
        overflow: hidden;
        page-break-after: always;
    }

    .page:last-child {
        page-break-after: avoid;
    }

    /* Imagen de fondo a pantalla completa */
    .bg-img {
        position: absolute;
        left: 0; top: 0;
        width: 86mm; height: 148mm;
        z-index: 0;
    }

    /* Fondo gris si no hay template */
    .bg-placeholder {
        position: absolute;
        left: 0; top: 0;
        width: 86mm; height: 148mm;
        background: #e5e7eb;
        z-index: 0;
    }

    /* Todos los overlays van arriba del fondo */
    .overlay {
        position: absolute;
        z-index: 10;
        text-align: center;
    }

    /* ── FRENTE: zonas de overlay ── */

    /* Foto: centrada, tope a 40mm, 26mm × 26mm */
    .ov-foto {
        left: 30mm;   /* (86-26)/2 */
        top: 40mm;
        width: 26mm;
        height: 26mm;
    }

    .ov-foto img {
        width: 26mm;
        height: 26mm;
        object-fit: cover;
        border-radius: 3px;
        display: block;
    }

    .ov-foto-placeholder {
        width: 26mm; height: 26mm;
        background: rgba(0,0,0,0.08);
        border-radius: 3px;
        display: block;
        text-align: center;
        line-height: 26mm;
        font-size: 14pt;
        color: rgba(0,0,0,0.3);
    }

    /* Nombre: centrado horizontalmente, a 104mm del tope */
    .ov-nombre {
        left: 5mm;
        top: 104mm;
        width: 76mm;
        font-size: 9pt;
        font-weight: 800;
        text-transform: uppercase;
        color: #1a1a1a;
        letter-spacing: 0.3px;
        line-height: 1.2;
        text-shadow: 0 0 3px rgba(255,255,255,0.8);
    }

    /* Clase: centrada, a 115mm */
    .ov-clase {
        left: 5mm;
        top: 115mm;
        width: 76mm;
        font-size: 7pt;
        color: #333;
        text-shadow: 0 0 3px rgba(255,255,255,0.8);
    }

    /* Fechas: abajo, a 138mm */
    .ov-fechas {
        left: 5mm;
        top: 138mm;
        width: 76mm;
        font-size: 5.5pt;
        color: #555;
        text-shadow: 0 0 2px rgba(255,255,255,0.8);
    }

    /* ── REVERSO: zona de overlay ── */

    /* Contacto de emergencia: centrado, desde 73mm */
    .ov-contacto {
        left: 10mm;
        top: 73mm;
        width: 66mm;
        color: #1a1a1a;
        text-shadow: 0 0 3px rgba(255,255,255,0.8);
    }

    .ov-contacto-tipo {
        font-size: 5.5pt;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #555;
        margin-bottom: 1mm;
    }

    .ov-contacto-nombre {
        font-size: 8.5pt;
        font-weight: 800;
        color: #111;
        line-height: 1.2;
    }

    .ov-contacto-tel {
        font-size: 7pt;
        color: #333;
        margin-top: 1mm;
    }
</style>
</head>
<body>

@php
    $frenteB64  = $config->frenteBase64();
    $reversoB64 = $config->reversoBase64();

    // Foto del alumno en base64
    $fotoB64 = null;
    if ($alumno->foto && file_exists(storage_path('app/public/' . $alumno->foto))) {
        $fp      = storage_path('app/public/' . $alumno->foto);
        $fotoB64 = 'data:' . mime_content_type($fp) . ';base64,' . base64_encode(file_get_contents($fp));
    }

    // Datos del responsable
    $datosResp = match($responsable) {
        'padre'  => ['tipo' => 'Padre / Tutor',   'nombre' => $alumno->nombre_padre,  'tel' => $alumno->telefono_padre],
        'madre'  => ['tipo' => 'Madre / Tutora',  'nombre' => $alumno->nombre_madre,  'tel' => $alumno->telefono_madre],
        default  => ['tipo' => 'Tutor Legal',     'nombre' => $alumno->nombre_tutor,  'tel' => $alumno->telefono_tutor],
    };
@endphp

{{-- ══════════════════════════════════════ --}}
{{-- FRENTE                                --}}
{{-- ══════════════════════════════════════ --}}
<div class="page">

    {{-- Fondo --}}
    @if($frenteB64)
        <img src="{{ $frenteB64 }}" class="bg-img" />
    @else
        <div class="bg-placeholder"></div>
        {{-- Aviso visual cuando no hay template --}}
        <div style="position:absolute;left:5mm;top:5mm;width:76mm;text-align:center;z-index:10;">
            <span style="font-size:6pt;color:#666;">⚠ Sin template — sube uno en Generador de Credenciales</span>
        </div>
    @endif

    {{-- Overlay: foto --}}
    <div class="overlay ov-foto">
        @if($fotoB64)
            <img src="{{ $fotoB64 }}" alt="Foto">
        @else
            <div class="ov-foto-placeholder">{{ strtoupper(substr($alumno->nombre, 0, 1)) }}</div>
        @endif
    </div>

    {{-- Overlay: nombre --}}
    <div class="overlay ov-nombre">
        {{ strtoupper($alumno->nombre . ' ' . $alumno->apellidos) }}
    </div>

    {{-- Overlay: clase --}}
    <div class="overlay ov-clase">
        {{ $alumno->clase?->nombre ?? 'Sin clase' }}
    </div>

    {{-- Overlay: fechas --}}
    <div class="overlay ov-fechas">
        Expedición: {{ now()->format('d/m/Y') }} &nbsp;•&nbsp; Vigencia: {{ now()->addYear()->format('d/m/Y') }}
    </div>

</div>

{{-- ══════════════════════════════════════ --}}
{{-- REVERSO                               --}}
{{-- ══════════════════════════════════════ --}}
<div class="page">

    {{-- Fondo --}}
    @if($reversoB64)
        <img src="{{ $reversoB64 }}" class="bg-img" />
    @else
        <div class="bg-placeholder"></div>
        <div style="position:absolute;left:5mm;top:5mm;width:76mm;text-align:center;z-index:10;">
            <span style="font-size:6pt;color:#666;">⚠ Sin template — sube uno en Generador de Credenciales</span>
        </div>
    @endif

    {{-- Overlay: contacto emergencia --}}
    @if($datosResp['nombre'])
        <div class="overlay ov-contacto">
            <div class="ov-contacto-tipo">{{ $datosResp['tipo'] }}</div>
            <div class="ov-contacto-nombre">{{ $datosResp['nombre'] }}</div>
            @if($datosResp['tel'])
                <div class="ov-contacto-tel">Tel: {{ $datosResp['tel'] }}</div>
            @endif
        </div>
    @endif

</div>

</body>
</html>
