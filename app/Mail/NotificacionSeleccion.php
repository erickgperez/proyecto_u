<?php

namespace App\Mail;

use App\Models\Ingreso\ConvocatoriaAspirante;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificacionSeleccion extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public ConvocatoriaAspirante $conAsp)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notificación de selección ',
            from: new Address('ingreso@sistec.edu.sv', 'Ingreso universitario')
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.aspirante-seleccionado',
        );
    }
}
