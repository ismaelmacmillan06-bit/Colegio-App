<?php

namespace App\Mail;

use App\Models\Colegiatura;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificacionColegiatura extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Colegiatura $colegiatura,
        public string $tipo,             // 'pago_realizado' | 'pago_pendiente'
        public string $contactoNombre,
    ) {}

    public function envelope(): Envelope
    {
        $alumno  = $this->colegiatura->alumno;
        $nombre  = trim(($alumno?->nombre ?? '') . ' ' . ($alumno?->apellidos ?? ''));
        $periodo = $this->colegiatura->periodo;

        $subject = match ($this->tipo) {
            'pago_realizado' => "✅ Pago confirmado — {$nombre} · {$periodo}",
            default          => "⏰ Recordatorio de pago — {$nombre} · {$periodo}",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.notificacion-colegiatura');
    }
}
