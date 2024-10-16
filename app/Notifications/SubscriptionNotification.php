<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionNotification extends Notification
{
    use Queueable;

    protected $subscription;

    public function __construct($subscription)
    {
        $this->subscription = $subscription;

    }


    public function via(object $notifiable): array
    {
        return ['mail'];
    }


    public function toMail(object $notifiable): MailMessage
    {
        $renewalDate = $this->subscription->renewal_date->format('d/m/Y');

        return (new MailMessage)
            ->subject('Upcoming Subscription Billing Reminder')
            ->greeting('Hello ' . $this->subscription->name)
            ->line('Your subscription for ' . $this->subscription->description . ' is due on ' . $this->subscription->next_billing_date->format('Y-m-d') . '.')
            ->line('Please make sure your payment information is up to date.')
            ->action('Manage Subscription', url('/subscription/' . $this->subscription->id))
            ->line('Thank you for using our service!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable): array
    {
        return [
            'subscription_id' => $this->subscription->id,
            'plan_name' => $this->subscription->plan->name,
            'renewal_date' => $this->subscription->renewal_date,
            'days_before' => $this->daysBefore,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
