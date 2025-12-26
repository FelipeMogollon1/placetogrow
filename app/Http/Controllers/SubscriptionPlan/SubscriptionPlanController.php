<?php

namespace App\Http\Controllers\SubscriptionPlan;

use App\Domain\SubscriptionPlan\Actions\DestroySubscriptionPlanAction;
use App\Domain\SubscriptionPlan\Actions\StoreSubscriptionPlanAction;
use App\Domain\SubscriptionPlan\Actions\UpdateSubscriptionPlanAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionPlan\StoreSubscriptionPlanRequest;
use App\Http\Requests\SubscriptionPlan\UpdateSubscriptionPlanRequest;
use Illuminate\Http\RedirectResponse;

class SubscriptionPlanController extends Controller
{
    public function store(StoreSubscriptionPlanRequest $request, StoreSubscriptionPlanAction $action): RedirectResponse
    {
        $action->execute($request->validated());

        return redirect()->route('microsites.show', $request->microsite_id)
            ->with('success', 'Subscription plan created.');
    }


    public function update(UpdateSubscriptionPlanRequest $request, string $id, UpdateSubscriptionPlanAction $action): RedirectResponse
    {
        $micrositeId = $action->execute($id, $request->validated());

        return redirect()->route('microsites.show', $micrositeId)
            ->with('success', 'Subscription plan updated.');
    }


    public function destroy(int $id, DestroySubscriptionPlanAction $action): RedirectResponse
    {
        $micrositeId = $action->execute($id);

        return redirect()->route('microsites.show', $micrositeId)
            ->with('success', 'Subscription plan deactivated.');
    }
}
