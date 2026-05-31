<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AsistenciaNotificacion extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nombrePadre,
        public string $nombreAlumno,
        public string $grado,
        public string $fecha,
        public string $hora,
        public string $tipo,
        public string $nombreColegio = 'Centro Cultural y Pedagógico IMA',
    ) {}

    public function envelope(): Envelope
    {
        $asunto = $this->tipo === 'entrada'
            ? "✅ {$this->nombreAlumno} llegó al colegio"
            : "🏠 {$this->nombreAlumno} salió del colegio";

        return new Envelope(subject: $asunto);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.asistencia',
        );
    }
}