<?php

namespace App\Http\Controllers\Subscription;

use App\Contracts\PaymentGatewayContract;
use App\Contracts\SubscriptionGatewayContract;
use App\Domain\Subscription\Actions\StoreSubscriptionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\StoreSubscriptionRequest;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\Payment;
use App\Infrastructure\Persistence\Models\Subscription;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{

    public function index(): Response
    {
        $subscriptions = Subscription::with(['subscriptionPlan', 'microsite'])
            ->orderBy('name', 'asc')
            ->paginate(5);

        return Inertia::render('Subscriptions/Index', [
            'subscriptions' => $subscriptions
        ]);
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriptionRequest $request, StoreSubscriptionAction $action, PaymentGatewayContract $gateway ): \Symfony\Component\HttpFoundation\Response
    {
        $subscription = $action->execute($request->validated());
        $response = $gateway->createSessionSubscription($subscription, $request);

        return Inertia::location($response->processUrl());
    }


    public function returnSubscription(Subscription $subscription, PaymentGatewayContract $gatewayContract): Response
    {
        $microsite = Microsite::query()->where('id',$subscription->microsite_id)->first();
        $gatewayContract->querySubscription($subscription);

        return Inertia::render('Subscriptions/ReturnSubscription', [
            'subscription' => $subscription,
            'microsite' => $microsite
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}
