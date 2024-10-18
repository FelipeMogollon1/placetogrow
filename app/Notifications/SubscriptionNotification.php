<?php

namespace App\Notifications;

use App\Infrastructure\Persistence\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SubscriptionNotification extends Notification
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
        Log::info('Details of the notification.', [
            'subscription' => $this->subscription,
        ]);

        if (!$this->subscription) {
            Log::error('Subscription object is null.');
            return (new MailMessage())
                ->subject('Error: Subscription Not Found')
                ->line('We could not find your subscription information. Please contact support.');
        }

        $name = $this->subscription->name ?? 'Valued Customer';
        $description = $this->subscription->description ?? 'your subscription';
        $nextBillingDate = $this->subscription->next_billing_date
            ? Carbon::parse($this->subscription->next_billing_date)->format('d M Y')
            : 'unknown';

        Log::info('finaly details of the notification.', [
            'name' => $name,
            'description' => $description,
            'next_billing_date' => $nextBillingDate,
        ]);

        return (new MailMessage())
            ->subject('Upcoming Subscription Billing Reminder')
            ->greeting('Hello ' . $name)
            ->line('Your subscription for ' . $description . ' is due on ' . $nextBillingDate . '.')
            ->line('Please ensure your payment information is up to date to avoid any disruptions.')
            ->action('Manage Subscription', url('/subscription/' . $this->subscription->id))
            ->line('Thank you for choosing our service!');
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */

    public function toArray(object $notifiable): array
    {
        return [
            'invoice_id' => $this->subscription->id,
            'expiration_date' => $this->subscription->next_billing_date,
        ];
    }
}
