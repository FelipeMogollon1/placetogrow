<?php

namespace App\Http\Controllers\Subscription;

use App\Constants\Abilities;
use App\Contracts\PaymentGatewayContract;
use App\Domain\Subscription\Actions\StoreSubscriptionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\StoreSubscriptionRequest;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\Subscription;
use App\Infrastructure\Persistence\Models\SubscriptionPlan;
use App\ViewModels\Subscription\SubscriptionIndexViewModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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


    public function store(StoreSubscriptionRequest $request, StoreSubscriptionAction $action, PaymentGatewayContract $gateway ): \Symfony\Component\HttpFoundation\Response
    {
        $subscription = $action->execute($request->validated());
        $response = $gateway->createSessionSubscription($subscription, $request);

        return Inertia::location($response->processUrl());
    }


    public function returnSubscription(Subscription $subscription, PaymentGatewayContract $gatewayContract): Response
    {
        $microsite = Microsite::query()->where('id',$subscription->microsite_id)->first();
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

    public function destroy(string $id,PaymentGatewayContract $gateway): \Symfony\Component\HttpFoundation\Response
    {
        dd($id);
        $subscription = SubscriptionPlan::findOrFail($id);
        $response = $gateway->cancelSubscription($subscription);

        return Inertia::location($response->processUrl());

    }
}
