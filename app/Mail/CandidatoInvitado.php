<?php

namespace App\Mail;

use App\Models\Ingreso\Convocatoria;
use App\Models\Secundaria\DataBachillerato;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidatoInvitado extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public DataBachillerato $bachiller, public Convocatoria $convocatoria)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitación a participar en carreras universitarias: ',
            from: new Address('ingreso@sistec.edu.sv', 'Ingreso universitario')
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.candidato-invitado',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachment = [];
        if ($this->convocatoria->afiche != null) {
            $filePath = storage_path('app/private/' . $this->convocatoria->afiche); // Adjust filename as needed

            $attachment[] = Attachment::fromPath($filePath)->as('afiche.pdf')->withMime('application/pdf');
        }
        return $attachment;
    }
}
