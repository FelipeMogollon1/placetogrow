<?php

namespace App\ViewModels\Invoice;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class InvoiceMicrositeIndexViewModel extends ViewModel
{
    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function getMicrosites(): Collection
    {
        $permissionsUser = $this->user->getAllPermissions()->pluck('name')->toArray();
        $rolesUser = $this->user->roles->pluck('name')->toArray();

        $micrositesQuery = Microsite::query()
            ->where('microsite_type', 'invoice');

        if (in_array(Permissions::INVOICES_INDEX->value, $permissionsUser)) {
            if (in_array(Roles::ADMIN->value, $rolesUser)) {
                $micrositesQuery->where('user_id', $this->user->id);
            } elseif (in_array(Roles::GUEST->value, $rolesUser)) {
                return collect();
            }
        }

        return $micrositesQuery->orderBy('name', 'asc')->get();
    }
}
