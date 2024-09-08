<?php

namespace App\ViewModels\Subscription;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Subscription;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class SubscriptionIndexViewModel extends ViewModel
{
    public function __construct(protected User $user)
    {

    }

    public static function fromAuthenticatedUser(): ?SubscriptionIndexViewModel
    {
        return new self(auth()->user());
    }

    public function getSubscriptions(array $filters): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $permissionsUser = $this->user->getAllPermissions()->pluck('name')->toArray();
        $rolesUser = $this->user->roles->pluck('name')->toArray();

        $subscriptionsQuery = Subscription::select(
            'subscriptions.id',
            'subscriptions.reference as reference',
            'subscription_plans.name as subscriptionPlans',
            'subscriptions.name as name',
            'subscriptions.surname as surname',
            'subscriptions.paid_at as date',
            'subscriptions.status as status',
            'microsites.name as microsite'
        )
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->join('microsites', 'subscriptions.microsite_id', '=', 'microsites.id');

        if (in_array(Permissions::SUBSCRIPTIONS_INDEX->value, $permissionsUser)) {
            if (in_array(Roles::ADMIN->value, $rolesUser)) {
                $subscriptionsQuery
                    ->where('microsites.user_id', $this->user->id);
            } elseif (in_array(Roles::GUEST->value, $rolesUser)) {
                $subscriptionsQuery
                    ->where('subscriptions.payer_email', $this->user->email);
            }
        }

        if ($search) {
            $subscriptionsQuery->where(function ($query) use ($search) {
                $query->where('microsites.name', 'like', '%' . $search . '%')
                    ->orWhere('subscription_plans.name', 'like', '%' . $search . '%')
                    ->orWhere('subscriptions.reference', 'like', '%' . $search . '%')
                    ->orWhere('subscriptions.name', 'like', '%' . $search . '%')
                    ->orWhere('subscriptions.surname', 'like', '%' . $search . '%');
            });
        }

        $subscriptionsQuery->orderBy('subscriptions.id', 'desc');

        return $subscriptionsQuery->paginate(5);
    }
}
