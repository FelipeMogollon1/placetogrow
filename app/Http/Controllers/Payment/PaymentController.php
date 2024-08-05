<?php

namespace App\Http\Controllers\Payment;

use App\Contracts\PaymentGatewayContract;
use App\Domain\Payment\StorePaymentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\Payment;
use App\Infrastructure\Persistence\Models\User;
use App\ViewModels\Payment\PaymentIndexViewModel;
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

    public function store (StorePaymentRequest $request,StorePaymentAction $action, PaymentGatewayContract $gateway ): \Symfony\Component\HttpFoundation\Response
    {
        $payment = $action->execute($request->validated());

        $response = $gateway->createSession($payment, $request);

        return  Inertia::location($response->processUrl());
    }

    public function show(string $id): Response
    {
        $payment = Payment::with('microsite')->findOrFail($id);

        return Inertia::render('Payment/Show', compact('payment'));
    }

    public function returnBusiness(Payment $payment, PaymentGatewayContract $gatewayContract): Response
    {
        $microsite = Microsite::query()->where('id',$payment->microsite_id)->first();
        $gatewayContract->queryPayment($payment);

        return Inertia::render('Payment/BackBusiness', [
            'payment' => $payment,
            'microsite' => $microsite
        ]);
    }

}
