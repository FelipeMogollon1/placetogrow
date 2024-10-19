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
        Log::info('Starting to send subscription notification.', [
            'subscription_id' => $this->subscription->id ?? 'N/A',
        ]);

        $micrositeName = $this->subscription->microsite->name ?? __('your microsite');
        $name = $this->subscription->name ?? __('Valued Customer');
        $description = $this->subscription->description ?? __('your subscription');
        $nextBillingDate = $this->subscription->next_billing_date
            ? Carbon::parse($this->subscription->next_billing_date)->translatedFormat('d M Y')
            : __('unknown');

        Log::info('Final notification details:', [
            'name' => $name,
            'description' => $description,
            'microsite_name' => $micrositeName,
            'next_billing_date' => $nextBillingDate,
        ]);

        return (new MailMessage())
            ->subject(__('notifications.Upcoming Subscription Billing Reminder') . ' ' . __('notifications.from') . ' ' . $micrositeName)
            ->greeting(__('notifications.Hello') . ' ' . $name)
            ->line(__('notifications.Your subscription for') . ' ' . $description . ' ' . __('notifications.on') . ' ' . $micrositeName . ' ' . __('notifications.is due on') . ' ' . $nextBillingDate . '.')
            ->line(__('notifications.Please ensure your payment information is up to date to avoid any disruptions.'))
            ->action(__('notifications.Manage Subscription'), url('/subscriptions/' . $this->subscription->id))
            ->line(__('notifications.Thank you for choosing our service!'));
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
