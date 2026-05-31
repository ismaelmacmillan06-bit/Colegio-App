<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificación de Asistencia</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f6f9;font-family:Arial,Helvetica,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f9;padding:30px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 3px 10px rgba(0,0,0,.08);">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="background:#1a1a2e;padding:30px;">
                            <h1 style="color:#ffffff;margin:0;font-size:22px;">
                                {{ $tipo === 'entrada' ? '✅ Registro de Entrada' : '🏠 Registro de Salida' }}
                            </h1>
                            <p style="color:#6eb8f5;margin:8px 0 0;font-size:14px;">{{ $nombreColegio }}</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:40px;">

                            <p style="font-size:16px;color:#333333;">
                                Hola <strong>{{ $nombrePadre }}</strong>,
                            </p>

                            <p style="font-size:15px;color:#555555;line-height:1.6;">
                                @if($tipo === 'entrada')
                                    Te informamos que <strong>{{ $nombreAlumno }}</strong> ha llegado al colegio y fue registrado exitosamente.
                                @else
                                    Te informamos que <strong>{{ $nombreAlumno }}</strong> ha salido del colegio.
                                @endif
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0"
                                   style="background:#f0f7ff;border:1px solid #c3e3fd;border-radius:10px;margin:25px 0;">
                                <tr>
                                    <td style="padding:20px;">
                                        <p style="margin:8px 0;font-size:15px;">
                                            🎒 <strong>Alumno:</strong> {{ $nombreAlumno }}
                                        </p>
                                        <p style="margin:8px 0;font-size:15px;">
                                            🏫 <strong>Clase:</strong> {{ $grado }}
                                        </p>
                                        <p style="margin:8px 0;font-size:15px;">
                                            📅 <strong>Fecha:</strong> {{ $fecha }}
                                        </p>
                                        <p style="margin:8px 0;font-size:15px;">
                                            ⏰ <strong>Hora de {{ $tipo === 'entrada' ? 'ingreso' : 'salida' }}:</strong>
                                            <strong style="color:#2196f3;">{{ $hora }}</strong>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin-top:30px;color:#666666;font-size:13px;line-height:1.6;">
                                Este correo fue generado automáticamente por el sistema de control de asistencia escolar.
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f8fafc;padding:25px;text-align:center;border-top:1px solid #e5e7eb;">
                            <p style="margin:0;color:#6b7280;font-size:13px;">
                                © {{ date('Y') }} {{ $nombreColegio }}
                            </p>
                            <p style="margin-top:6px;color:#9ca3af;font-size:12px;">
                                Sistema de Control de Asistencia Escolar
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>