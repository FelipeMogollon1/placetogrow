<?php

namespace App\ViewModels\Microsites;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Spatie\ViewModels\ViewModel;

class MicrositeIndexViewModel extends ViewModel
{
     public function __construct(protected User $user)
    {

    }

    public function getMicrositesByUserRole(): Collection
    {
        $permissionsUser = $this->user->getAllPermissions()->pluck('name')->toArray();
        $rolesUser = $this->user->roles->pluck('name')->toArray();

        if (in_array(Permissions::MICROSITES_INDEX->value, $permissionsUser) && in_array(Roles::SA->value, $rolesUser)) {

            $microsites = Microsite::query()->AllWithCategories()->get();

        } else {
            $microsites = Microsite::query()->AllWithCategories()
                ->where('microsites.user_id', $this->user->id)
                ->get();
        }
        return $microsites;
    }
    public function toArray(): array
    {
        return [
            'microsites' => $this->getMicrositesByUserRole(),
        ];
    }
}
