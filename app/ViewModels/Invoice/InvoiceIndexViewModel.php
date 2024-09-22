<?php

namespace App\ViewModels\Invoice;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Invoice;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class InvoiceIndexViewModel extends ViewModel
{
    public function __construct(protected User $user)
    {

    }

    public static function fromAuthenticatedUser(): ?InvoiceIndexViewModel
    {
        return new self(auth()->user());
    }

    public function getInvoices(array $filters): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $permissionsUser = $this->user->getAllPermissions()->pluck('name')->toArray();
        $rolesUser = $this->user->roles->pluck('name')->toArray();

        $query = Invoice::query()
            ->select('invoices.*', 'microsites.microsite_type', 'microsites.name as microsite_name')
            ->join('microsites', 'invoices.microsite_id', '=', 'microsites.id');

        if (in_array(Permissions::INVOICES_INDEX->value, $permissionsUser)) {
            if (in_array(Roles::ADMIN->value, $rolesUser)) {
                $query->where('microsites.user_id', $this->user->id);
            } elseif (in_array(Roles::GUEST->value, $rolesUser)) {
                $query->leftJoin('users', 'users.email', '=', 'invoices.email')
                    ->where('invoices.email', $this->user->email);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoices.reference', 'like', '%' . $search . '%')
                    ->orWhere('invoices.name', 'like', '%' . $search . '%')
                    ->orWhere('invoices.surname', 'like', '%' . $search . '%')
                    ->orWhere('invoices.email', 'like', '%' . $search . '%')
                    ->orWhere('invoices.document_type', 'like', '%' . $search . '%')
                    ->orWhere('invoices.document', 'like', '%' . $search . '%')
                    ->orWhere('invoices.amount', 'like', '%' . $search . '%')
                    ->orWhere('microsites.name', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('invoices.id', 'desc')->paginate(10);
    }


}
