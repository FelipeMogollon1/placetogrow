<?php

namespace App\Notifications;

use App\Infrastructure\Persistence\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionEndingNotification extends Notification
{
    use Queueable;

    protected Subscription $subscription;


    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }


    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Tu suscripción está por finalizar')
            ->line('Tu suscripción a ' . $this->subscription->name . ' finalizará pronto.')
            ->line('Recuerda que puedes volver a suscribirte en cualquier momento.')
            ->line('Gracias por usar nuestro servicio!');
    }

}
