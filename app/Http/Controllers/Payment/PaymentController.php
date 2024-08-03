<?php

namespace App\Http\Controllers\Payment;

use App\Domain\Payment\StorePaymentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Infrastructure\Persistence\Models\Payment;
use App\Infrastructure\Persistence\Models\User;
use App\PaymentGateway\PlacetopayGateway;
use App\ViewModels\Payment\PaymentIndexViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function index(): Response
    {
        /** @var User $user */
        $user = Auth::user();
        $payments = (new PaymentIndexViewModel($user));
        return Inertia::render('Payment/Index', [
            'payments' => $payments
        ]);
    }

    public function store (StorePaymentRequest $request,StorePaymentAction $action, PlacetopayGateway $gateway ): \Symfony\Component\HttpFoundation\Response
    {
        $payment = $action->execute($request->validated());
        $response = $gateway->createSession($payment, $request);

        return  Inertia::location($response->processUrl());
    }
}
