<?php

namespace App\Notifications;

use App\Infrastructure\Persistence\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceExpirationNotification extends Notification
{
    use Queueable;

    protected Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }
    public function toMail(object $notifiable): MailMessage
    {
        $micrositeName = $this->invoice->microsite->name ?? __('notifications.microsite_default');
        $expirationDate = $this->invoice->expiration_date
            ? Carbon::parse($this->invoice->expiration_date)->format('d M Y')
            : 'N/A';

        return (new MailMessage())
            ->subject(__('notifications.subject'))
            ->greeting(__('notifications.greeting', ['name' => $this->invoice->name . ' ' . $this->invoice->surname]))
            ->line(__('notifications.line1', ['microsite' => $micrositeName, 'reference' => $this->invoice->reference]))
            ->line(__('notifications.amount', [
                'currency' => $this->invoice->currency_type,
                'amount' => number_format($this->invoice->amount, 2)
            ]))
            ->line(__('notifications.due_date', ['date' => $expirationDate]))
            ->line(__('notifications.platform_link'))
            ->action(__('notifications.pay_button'), url('/invoices/' . $this->invoice->id))
            ->line(__('notifications.thanks'));
    }



    public function toArray($notifiable): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'expiration_date' => $this->invoice->expiration_date,
        ];
    }
}
