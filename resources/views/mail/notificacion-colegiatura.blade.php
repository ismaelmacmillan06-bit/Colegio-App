<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #f0f4f9; margin: 0; padding: 32px 16px; color: #1e293b; }
  .wrap { max-width: 560px; margin: 0 auto; }
  .card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #1a1854 0%, #4f46e5 100%); padding: 32px 40px; text-align: center; }
  .header-logo { font-size: 22px; font-weight: 800; color: white; letter-spacing: -0.5px; }
  .header-sub { font-size: 13px; color: rgba(255,255,255,0.6); margin-top: 4px; }
  .badge-status { display: inline-block; margin-top: 16px; padding: 6px 18px; border-radius: 100px; font-size: 13px; font-weight: 700; letter-spacing: 0.04em; }
  .badge-pagada { background: rgba(16,185,129,0.2); color: #6ee7b7; }
  .badge-pendiente { background: rgba(251,191,36,0.2); color: #fcd34d; }
  .body { padding: 32px 40px; }
  .greeting { font-size: 16px; color: #334155; line-height: 1.6; margin-bottom: 20px; }
  .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px 24px; margin: 20px 0; }
  .info-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
  .info-row:last-child { border-bottom: none; }
  .info-label { font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
  .info-value { font-size: 14px; font-weight: 700; color: #0f172a; text-align: right; }
  .monto-big { text-align: center; margin: 28px 0; }
  .monto-big .num { font-size: 42px; font-weight: 800; color: #1a1854; line-height: 1; }
  .monto-big .cur { font-size: 16px; font-weight: 600; color: #94a3b8; margin-top: 4px; }
  .beca-box { background: linear-gradient(135deg, #ede9fe, #ddd6fe); border-radius: 10px; padding: 14px 18px; margin: 16px 0; }
  .beca-box p { margin: 0; font-size: 13px; color: #4c1d95; font-weight: 600; }
  .alert-box { background: #fef9c3; border: 1px solid #fde047; border-radius: 10px; padding: 14px 18px; margin: 16px 0; }
  .alert-box p { margin: 0; font-size: 13px; color: #854d0e; }
  .note { font-size: 13px; color: #94a3b8; margin-top: 24px; line-height: 1.6; }
  .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 40px; text-align: center; }
  .footer p { font-size: 11px; color: #94a3b8; margin: 0; line-height: 1.6; }
</style>
</head>
<body>
<div class="wrap">
  <div class="card">

    {{-- Header --}}
    <div class="header">
      <div class="header-logo">SchoolCore</div>
      <div class="header-sub">Sistema de Gestión Escolar</div>
      <div class="badge-status {{ $tipo === 'pago_realizado' ? 'badge-pagada' : 'badge-pendiente' }}">
        {{ $tipo === 'pago_realizado' ? '✅ Pago Confirmado' : '⏰ Pago Pendiente' }}
      </div>
    </div>

    {{-- Body --}}
    <div class="body">

      <p class="greeting">
        Estimado/a <strong>{{ $contactoNombre }}</strong>,<br>
        @if($tipo === 'pago_realizado')
          Le informamos que el pago de colegiatura del alumno <strong>{{ $colegiatura->alumno?->nombre }} {{ $colegiatura->alumno?->apellidos }}</strong> ha sido registrado exitosamente en nuestro sistema.
        @else
          Le recordamos que la colegiatura del alumno <strong>{{ $colegiatura->alumno?->nombre }} {{ $colegiatura->alumno?->apellidos }}</strong> se encuentra <strong>pendiente de pago</strong>.
        @endif
      </p>

      {{-- Info box --}}
      <div class="info-box">
        <div class="info-row">
          <span class="info-label">Alumno</span>
          <span class="info-value">{{ $colegiatura->alumno?->nombre }} {{ $colegiatura->alumno?->apellidos }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Clase</span>
          <span class="info-value">{{ $colegiatura->alumno?->clase?->nombre ?? '—' }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Período</span>
          <span class="info-value" style="color: #4f46e5;">{{ $colegiatura->periodo }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Tipo de cobro</span>
          <span class="info-value">{{ $colegiatura->tipo_cobro }}</span>
        </div>
        @if($tipo === 'pago_pendiente' && $colegiatura->fecha_vencimiento)
        <div class="info-row">
          <span class="info-label">Fecha límite</span>
          <span class="info-value" style="color: #dc2626;">{{ $colegiatura->fecha_vencimiento->format('d \d\e F \d\e Y') }}</span>
        </div>
        @endif
        @if($tipo === 'pago_realizado' && $colegiatura->fecha_pago)
        <div class="info-row">
          <span class="info-label">Fecha de pago</span>
          <span class="info-value" style="color: #059669;">{{ $colegiatura->fecha_pago->format('d \d\e F \d\e Y') }}</span>
        </div>
        @endif
      </div>

      {{-- Monto --}}
      <div class="monto-big">
        <div class="num">${{ number_format($colegiatura->monto, 0) }}</div>
        <div class="cur">MXN</div>
      </div>

      {{-- Beca --}}
      @if($colegiatura->descuento_pct > 0)
      <div class="beca-box">
        <p>🎓 <strong>Beca aplicada: {{ $colegiatura->descuento_pct }}% de descuento</strong><br>
        Monto original: ${{ number_format($colegiatura->monto_original ?? 0, 0) }} MXN → Monto final: ${{ number_format($colegiatura->monto, 0) }} MXN</p>
      </div>
      @endif

      {{-- Alert para pago pendiente --}}
      @if($tipo === 'pago_pendiente')
      <div class="alert-box">
        <p>⚠️ Por favor, realice su pago antes de la fecha límite para evitar cargos por mora. Si ya realizó el pago, ignore este mensaje.</p>
      </div>
      @endif

      <p class="note">Para cualquier duda o aclaración, comuníquese directamente con la administración del colegio. No responda a este correo electrónico.</p>

    </div>

    {{-- Footer --}}
    <div class="footer">
      <p>Este mensaje fue generado automáticamente por <strong>SchoolCore</strong>.<br>
      © {{ date('Y') }} SchoolCore — Sistema de Gestión Escolar</p>
    </div>

  </div>
</div>
</body>
</html>
