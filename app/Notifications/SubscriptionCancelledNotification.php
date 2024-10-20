<?php

namespace App\Notifications;

use App\Infrastructure\Persistence\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionCancelledNotification extends Notification implements ShouldQueue
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
        $micrositeName = $this->subscription->microsite ? $this->subscription->microsite->name : __('notifications.unknown');
        $reference = $this->subscription->reference;

        return (new MailMessage())
            ->subject(__('notifications.Subscription Cancelled Subject'))
            ->greeting(__('notifications.Subscription Cancelled Greeting', [
                'name' => $this->subscription->name,
                'surname' => $this->subscription->surname,
            ]))
            ->line(__('notifications.Subscription Cancelled Line1', [
                'reference' => $reference,
            ]))
            ->line(__('notifications.Microsite Name', [
                'microsite' => $micrositeName,
            ]))
            ->action(__('notifications.Login Button'), url('/login'))
            ->line(__('notifications.Subscription Cancelled Thanks'));
    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
