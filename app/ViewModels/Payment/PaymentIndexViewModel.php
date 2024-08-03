<?php

namespace App\ViewModels\Payment;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Payment;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class PaymentIndexViewModel extends ViewModel
{
    public function __construct(protected User $user)
    {

    }

    public function getPaymentByMicrosite(): LengthAwarePaginator
    {
        $permissionsUser = $this->user->getAllPermissions()->pluck('name')->toArray();
        $rolesUser = $this->user->roles->pluck('name')->toArray();

        $paymentsQuery = Payment::select('payments.*', 'microsites.name as microsite_name')
            ->join('microsites', 'payments.microsite_id', '=', 'microsites.id');

        $paymentsQuery->when(
            in_array(Permissions::PAYMENTS_INDEX->value, $permissionsUser),
            function ($query) use ($rolesUser) {
                if (in_array(Roles::ADMIN->value, $rolesUser)) {
                    $query->where('microsites.user_id', $this->user->id);
                } elseif (in_array(Roles::GUEST->value, $rolesUser)) {
                    $query->join('users', 'payments.payer_email', '=', 'users.email')
                        ->where('payments.payer_email', $this->user->email);
                }
            }
        );
        $paymentsQuery->orderBy('microsites.name', 'asc');

        return $paymentsQuery->paginate(5);
    }

    public function toArray(): array
    {
        return $this->getPaymentByMicrosite()->toArray();
    }
}
