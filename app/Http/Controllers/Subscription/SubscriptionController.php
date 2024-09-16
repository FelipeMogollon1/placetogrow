<?php

namespace App\Http\Controllers\Subscription;

use App\Constants\Abilities;
use App\Contracts\PaymentGatewayContract;
use App\Domain\Subscription\Actions\DestroySubscriptionAction;
use App\Domain\Subscription\Actions\StoreSubscriptionAction;
use App\Domain\Subscription\Actions\UpdateSubscriptionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\StoreSubscriptionRequest;
use App\Http\Requests\Subscription\UpdateSubscriptionRequest;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\Subscription;
use App\Infrastructure\Persistence\Models\SubscriptionPlan;
use App\ViewModels\Subscription\SubscriptionIndexViewModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    use AuthorizesRequests;
    public function index(SubscriptionIndexViewModel $viewModel): Response
    {
        $this->authorize(Abilities::VIEW_ANY->value, Subscription::class);
        $search = Request::only(['search']);

        return Inertia::render('Subscriptions/Index', [
            'subscriptions' => $viewModel
                ->fromAuthenticatedUser()->getSubscriptions($search),
            'filter' => $search ?? '',
        ]);
    }


    public function store(StoreSubscriptionRequest $request, StoreSubscriptionAction $action, PaymentGatewayContract $gateway): \Symfony\Component\HttpFoundation\Response
    {
        $subscription = $action->execute($request->validated());
        $response = $gateway->createSessionSubscription($subscription, $request);

        return Inertia::location($response->processUrl());
    }


    public function returnSubscription(Subscription $subscription, PaymentGatewayContract $gatewayContract): Response
    {
        $microsite = Microsite::query()->where('id', $subscription->microsite_id)->first();
        $subscriptionPlans = SubscriptionPlan::all();
        $gatewayContract->querySubscription($subscription);

        return Inertia::render('Subscriptions/ReturnSubscription', [
            'subscription' => $subscription,
            'microsite' => $microsite,
            'subscriptionPlans' => $subscriptionPlans

        ]);
    }

    public function show(string $id): Response
    {
        $this->authorize(Abilities::VIEW->value, Subscription::class);
        $subscription = Subscription::with(['subscriptionPlan', 'microsite'])
            ->where('id', $id)->firstOrFail();

        return Inertia::render('Subscriptions/Show', compact('subscription'));
    }

    public function edit(string $id): Response
    {
        $this->authorize(Abilities::EDIT->value, Subscription::class);

        $subscription = SubscriptionPlan::select('*')
            ->join('microsites', 'subscription_plans.microsite_id', '=', 'microsites.id')
            ->join('subscriptions', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->where('subscriptions.id', $id)->get();

        $subscriptionPlans = SubscriptionPlan::select('subscription_plans.*')
            ->join('microsites', 'subscription_plans.microsite_id', '=', 'microsites.id')->get();

        return Inertia::render('Subscriptions/Edit', compact('subscription','subscriptionPlans'));
    }

    public function update(UpdateSubscriptionRequest $request,string $id,UpdateSubscriptionAction $updateAction): RedirectResponse
    {
        $this->authorize(Abilities::UPDATE->value, Subscription::class);
        $updateAction->execute($id, $request->validated());

        return redirect()->route('subscriptions.index');
    }

    public function destroy(string $id, DestroySubscriptionAction $action): RedirectResponse
    {
        $this->authorize(Abilities::DELETE->value, Subscription::class);
        $subscription = Subscription::findOrFail($id);

        if ($action->execute($subscription)) {
            Artisan::call('app:transactions-consult');
            return redirect()->route('subscriptions.index')
                ->with('success', 'Subscription successfully canceled and transactions updated.');
        }

        return redirect()->route('subscriptions.index')
            ->with('error', 'Failed to cancel the subscription. Please try again.');
    }

}
