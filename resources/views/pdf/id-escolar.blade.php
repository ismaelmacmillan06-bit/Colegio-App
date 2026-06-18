<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>ID Escolar — {{ $clase->nombre }}</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; background: #fff; }

    .header {
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        color: white;
        padding: 18px 24px;
        border-radius: 0 0 12px 12px;
        margin-bottom: 20px;
    }
    .header h1 { font-size: 18px; font-weight: bold; letter-spacing: -0.3px; }
    .header p { font-size: 11px; opacity: 0.82; margin-top: 3px; }
    .meta { display: flex; gap: 24px; margin-top: 10px; }
    .meta-item { font-size: 10px; opacity: 0.9; }
    .meta-item strong { display: block; font-size: 13px; opacity: 1; }

    table { width: 100%; border-collapse: collapse; margin-top: 4px; }
    thead tr { background: #1e3a8a; color: white; }
    thead th { padding: 8px 10px; text-align: left; font-size: 10px; font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase; }
    tbody tr { border-bottom: 1px solid #e2e8f0; }
    tbody tr:nth-child(even) { background: #f8fafc; }
    tbody td { padding: 9px 10px; vertical-align: middle; }

    .num { color: #94a3b8; font-size: 10px; width: 28px; }
    .nombre { font-weight: 600; font-size: 11px; }
    .codigo {
        font-family: 'DejaVu Sans Mono', monospace;
        font-size: 11px;
        font-weight: 700;
        color: #2563eb;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 5px;
        padding: 3px 8px;
        display: inline-block;
        letter-spacing: 0.05em;
    }

    .footer {
        margin-top: 24px;
        padding-top: 10px;
        border-top: 1px solid #e2e8f0;
        font-size: 9px;
        color: #94a3b8;
        text-align: center;
    }

    .empty { text-align: center; padding: 32px; color: #94a3b8; font-style: italic; }
</style>
</head>
<body>

<div class="header">
    <h1>ID Escolar — {{ $clase->nombre }}</h1>
    <p>Identificadores únicos de alumnos · Documento confidencial</p>
    <div class="meta">
        <div class="meta-item">
            Total alumnos
            <strong>{{ $alumnos->count() }}</strong>
        </div>
        <div class="meta-item">
            Fecha de emisión
            <strong>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</strong>
        </div>
    </div>
</div>

@if ($alumnos->isEmpty())
    <p class="empty">No hay alumnos con código asignado en esta clase.</p>
@else
    <table>
        <thead>
            <tr>
                <th class="num">#</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>ID Escolar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($alumnos as $i => $alumno)
                <tr>
                    <td class="num">{{ $i + 1 }}</td>
                    <td class="nombre">{{ $alumno->nombre }}</td>
                    <td>{{ $alumno->apellidos }}</td>
                    <td><span class="codigo">{{ $alumno->codigo_alumno }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<div class="footer">
    SchoolCoreApp · Documento generado automáticamente · Solo para uso interno del administrador del colegio
</div>

</body>
</html>
