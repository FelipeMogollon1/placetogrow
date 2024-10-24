<?php

namespace App\Http\Controllers\Subscription;

use App\Constants\Abilities;
use App\Constants\PaymentStatus;
use App\Constants\SubscriptionStatus;
use App\Contracts\PaymentGatewayContract;
use App\Domain\Subscription\Actions\DestroySubscriptionAction;
use App\Domain\Subscription\Actions\StoreSubscriptionAction;
use App\Domain\Subscription\Actions\UpdateSubscriptionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\StoreSubscriptionRequest;
use App\Http\Requests\Subscription\UpdateSubscriptionRequest;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\Subscription;
use App\Infrastructure\Persistence\Models\SubscriptionPayment;
use App\Infrastructure\Persistence\Models\SubscriptionPlan;
use App\ViewModels\Subscription\SubscriptionIndexViewModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
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
            'subscriptions' => $viewModel->fromAuthenticatedUser()->getSubscriptions($search),
            'filter' => $search ?? '',
        ]);
    }


    public function store(StoreSubscriptionRequest $request, StoreSubscriptionAction $action, PaymentGatewayContract $gateway): \Symfony\Component\HttpFoundation\Response
    {
        $subscription = $action->execute($request->validated());
        if ($subscription === false) {
            return redirect()->back()->with('success', 'Parece que ya tienes una subscripción activa a este plan de micrositio, por favor, revisa tus subscripciones.');
        }
        $response = $gateway->createSessionSubscription($subscription, $request);

        return Inertia::location($response->processUrl());
    }


    public function returnSubscription(Subscription $subscription, PaymentGatewayContract $gatewayContract): Response
    {
        $microsite = Microsite::find($subscription->microsite_id);
        $subscriptionPlans = SubscriptionPlan::where('id', $subscription->subscription_plan_id)->get();

        $gatewayContract->querySubscription($subscription);

        if ($subscription->status === SubscriptionStatus::ACTIVE->value) {

            $subscriptionPaymentExists = SubscriptionPayment::where('subscription_id', $subscription->id)
                ->where('status', PaymentStatus::APPROVED->value)
                ->where('subscription_plan_id', $subscription->subscription_plan_id)
                ->where(function ($query) {
                    $query->whereNotNull('request_id')->orWhere('request_id', '!=', '');
                })
                ->exists();

            if (!$subscriptionPaymentExists) {
                $gatewayContract->collectSubscription($subscription);
            }
        }

        return Inertia::render('Subscriptions/ReturnSubscription', [
            'subscription' => $subscription,
            'microsite' => $microsite,
            'subscriptionPlans' => $subscriptionPlans,
        ]);
    }

    public function show(string $id): Response
    {
        $this->authorize(Abilities::VIEW->value, Subscription::class);
        $subscription = Subscription::with(['subscriptionPlan', 'microsite'])
            ->where('id', $id)->firstOrFail();

        $subscriptionPayments = SubscriptionPayment::select(
            'subscription_payments.id',
            'subscription_plans.name as plan',
            'subscription_payments.amount as amount',
            'subscription_payments.status as status',
            'subscription_payments.attempt_count as attempt_count',
            'subscription_payments.paid_at as paid_at',
            'subscription_payments.request_id as request_id'
        )
            ->join('subscriptions', 'subscription_payments.subscription_id', '=', 'subscriptions.id')
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->join('microsites', 'subscriptions.microsite_id', '=', 'microsites.id')
            ->where('subscription_payments.subscription_id', $id)
            ->paginate(10);

        return Inertia::render('Subscriptions/Show', compact('subscription', 'subscriptionPayments'));
    }

    public function edit(string $id): Response
    {
        $this->authorize(Abilities::EDIT->value, Subscription::class);

        $subscription = SubscriptionPlan::select('*')
            ->join('microsites', 'subscription_plans.microsite_id', '=', 'microsites.id')
            ->join('subscriptions', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->where('subscriptions.id', $id)->get();

        $subscriptionPlans = SubscriptionPlan::select('subscription_plans.*')
            ->join('microsites', 'subscription_plans.microsite_id', '=', 'microsites.id')
            ->where('active', true)->get();

        return Inertia::render('Subscriptions/Edit', compact('subscription', 'subscriptionPlans'));
    }

    public function update(UpdateSubscriptionRequest $request, string $id, UpdateSubscriptionAction $updateAction): RedirectResponse
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
            return redirect()->route('subscriptions.index')
                ->with('success', 'Subscription successfully canceled and transactions updated.');
        }

        return redirect()->route('subscriptions.index')
            ->with('error', 'Failed to cancel the subscription. Please try again.');
    }

}
