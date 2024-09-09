<?php

namespace App\Http\Controllers\Subscription;

use App\Domain\SubscriptionPlan\Actions\StoreSubscriptionPlanAction;
use App\Domain\SubscriptionPlan\Actions\UpdateSubscriptionPlanAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionPlan\StoreSubscriptionPlanRequest;
use App\Http\Requests\SubscriptionPlan\UpdateSubscriptionPlanRequest;
use App\Infrastructure\Persistence\Models\SubscriptionPlan;
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
        $action->execute($id, $request->validated());

        return redirect()->route('microsites.show', $request->microsite_id)
            ->with('success', 'Subscription plan updated.');
    }


    public function destroy(int $id): RedirectResponse
    {
        $subscriptionPlan = SubscriptionPlan::findOrFail($id);
        $micrositeId = $subscriptionPlan->microsite_id;
        $subscriptionPlan->delete();

        return redirect()->route('microsites.show', $micrositeId)
            ->with('success', 'Subscription plan deleted.');
    }
}
