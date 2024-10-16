<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceExpirationNotification extends Notification
{
    use Queueable;

    protected $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Alerta de Vencimiento de Factura')
            ->greeting('Hola ' . $this->invoice->name)
            ->line('Tu factura con referencia ' . $this->invoice->reference . ' está próxima a vencer el ' . $this->invoice->expiration_date. '.')
            ->action('Ver Factura', url('/invoices/' . $this->invoice->id))
            ->line('Gracias por confiar en nuestro servicio.');
    }

    public function toArray($notifiable): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'expiration_date' => $this->invoice->expiration_date,
        ];
    }
}
