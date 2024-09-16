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

    public static function fromAuthenticatedUser(): ?PaymentIndexViewModel
    {
        return new self(auth()->user());
    }

    public function getPayments(array $filters): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $permissionsUser = $this->user->getAllPermissions()->pluck('name')->toArray();
        $rolesUser = $this->user->roles->pluck('name')->toArray();

        $paymentsQuery = Payment::select('payments.*', 'microsites.microsite_type', 'microsites.name as microsite_name')
            ->join('microsites', 'payments.microsite_id', '=', 'microsites.id');

        if (in_array(Permissions::PAYMENTS_INDEX->value, $permissionsUser)) {

            if (in_array(Roles::ADMIN->value, $rolesUser)) {

                $paymentsQuery
                    ->where('microsites.user_id', $this->user->id);

            } elseif (in_array(Roles::GUEST->value, $rolesUser)) {

                $paymentsQuery
                    ->join('users', 'payments.payer_email', '=', 'users.email')
                    ->where('payments.payer_email', $this->user->email);
            }

        }

        if ($search) {
            $paymentsQuery->where(function ($query) use ($search) {
                $query->where('microsites.name', 'like', '%' . $search . '%')
                    ->orWhere('payments.reference', 'like', '%' . $search . '%')
                    ->orWhere('payments.payer_name', 'like', '%' . $search . '%')
                    ->orWhere('payments.payer_surname', 'like', '%' . $search . '%');
            });
        }

        $paymentsQuery->orderBy('payments.id', 'desc');

        return $paymentsQuery->paginate(5);
    }

}
