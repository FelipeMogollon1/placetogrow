<?php

namespace App\ViewModels\Microsites;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class MicrositeIndexViewModel extends ViewModel
{
     public function __construct(protected User $user)
    {

    }

    public function getMicrositesByUserRole(): LengthAwarePaginator
    {
        $permissionsUser = $this->user->getAllPermissions()->pluck('name')->toArray();
        $rolesUser = $this->user->roles->pluck('name')->toArray();

        if (in_array(Permissions::MICROSITES_INDEX->value, $permissionsUser) && in_array(Roles::SA->value, $rolesUser)) {

            $microsites = Microsite::query()->AllWithCategories();

        } else {
            $microsites = Microsite::query()->AllWithCategories()
                ->where('microsites.user_id', $this->user->id);
        }
        return $microsites->paginate(5);
    }

    public function toArray(): array
    {
        $micrositesPaginator = $this->getMicrositesByUserRole();

        return [
            'microsites' => $micrositesPaginator->items(),
            'pagination' => [
                'total' => $micrositesPaginator->total(),
                'per_page' => $micrositesPaginator->perPage(),
                'current_page' => $micrositesPaginator->currentPage(),
                'last_page' => $micrositesPaginator->lastPage(),
                'from' => $micrositesPaginator->firstItem(),
                'to' => $micrositesPaginator->lastItem(),
                'links' => $micrositesPaginator->links(),
            ],
        ];
    }
}
