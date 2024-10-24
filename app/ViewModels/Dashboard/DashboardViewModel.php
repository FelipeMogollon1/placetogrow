<?php

namespace App\ViewModels\Dashboard;

use App\Constants\PaymentStatus;
use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Spatie\ViewModels\ViewModel;

class DashboardViewModel extends ViewModel
{
    protected $user;
    protected $permissionsUser;
    protected $rolesUser;
    protected $startDate;
    protected $endDate;
    protected $micrositeId;


    public function __construct($startDate, $endDate, $micrositeId)
    {
        $this->user = auth()->user();
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->micrositeId = $micrositeId;
        $this->permissionsUser = $this->user->getAllPermissions()->pluck('name')->toArray();
        $this->rolesUser = $this->user->roles->pluck('name')->toArray();
    }

    public function getTotalPaid(): int
    {
        $cacheKeyPaid = "totalPaid_{$this->micrositeId}_{$this->startDate}_{$this->endDate}";

        return Cache::remember($cacheKeyPaid, 10, function () {
            $query = Invoice::where('status', PaymentStatus::APPROVED->value)
                ->join('microsites', 'invoices.microsite_id', '=', 'microsites.id')
                ->whereBetween('invoices.created_at', [$this->startDate, $this->endDate]);

            if (in_array(Permissions::DASHBOARD_INDEX->value, $this->permissionsUser)) {

                if (in_array(Roles::ADMIN->value, $this->rolesUser)) {
                    $query->where('microsites.user_id', $this->user->id);
                }
                if (!is_null($this->micrositeId) && $this->micrositeId !== '*') {
                    $query->where('microsites.id', $this->micrositeId);
                }
            }

            return $query->count();
        });
    }


    public function getTotalPending(): int
    {
        $cacheKeyPending = "totalPending_{$this->micrositeId}_{$this->startDate}_{$this->endDate}";

        return Cache::remember($cacheKeyPending, 10, function () {
            $query = Invoice::where('status', PaymentStatus::PENDING->value)
                ->where('invoices.expiration_date', '>=', Carbon::now())
                ->whereBetween('invoices.created_at', [$this->startDate, $this->endDate])
                ->join('microsites', 'invoices.microsite_id', '=', 'microsites.id');

            if (in_array(Permissions::DASHBOARD_INDEX->value, $this->permissionsUser)) {
                if (in_array(Roles::ADMIN->value, $this->rolesUser)) {
                    $query->where('microsites.user_id', $this->user->id);
                }

                if (!is_null($this->micrositeId) && $this->micrositeId !== '*') {
                    $query->where('microsites.id', $this->micrositeId);
                }
            }

            return $query->count();
        });
    }

    public function getTotalOverdue(): int
    {
        $cacheKeyOverdue = "totalOverdue_{$this->micrositeId}_{$this->startDate}_{$this->endDate}";

        return Cache::remember($cacheKeyOverdue, 10, function () {
            $query = Invoice::where('status', PaymentStatus::PENDING->value)
                ->where('invoices.expiration_date', '<', Carbon::now())
                ->whereBetween('invoices.created_at', [$this->startDate, $this->endDate])
                ->join('microsites', 'invoices.microsite_id', '=', 'microsites.id');

            if (in_array(Permissions::DASHBOARD_INDEX->value, $this->permissionsUser)) {
                if (in_array(Roles::ADMIN->value, $this->rolesUser)) {
                    $query->where('microsites.user_id', $this->user->id);
                }

                if (!is_null($this->micrositeId) && $this->micrositeId !== '*') {
                    $query->where('microsites.id', $this->micrositeId);
                }
            }

            return $query->count();
        });
    }

}
