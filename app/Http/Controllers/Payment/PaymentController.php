<?php

namespace App\Http\Controllers\Payment;

use App\Constants\Abilities;
use App\Contracts\PaymentGatewayContract;
use App\Domain\Payment\Actions\StorePaymentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\Payment;
use App\ViewModels\Payment\PaymentIndexViewModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    public function index(PaymentIndexViewModel $viewModel): Response
    {
        $this->authorize(Abilities::VIEW_ANY->value, Payment::class);
        $search = Request::only(['search']);

        return Inertia::render('Payment/Index', [
            'payments' => $viewModel
                ->fromAuthenticatedUser()->getPayments($search),
            'filter' => $search ?? '',
        ]);
    }

    public function store(StorePaymentRequest $request, StorePaymentAction $action, PaymentGatewayContract $gateway): \Symfony\Component\HttpFoundation\Response
    {
        $payment = $action->execute($request->validated());
        $response = $gateway->createSession($payment, $request);

        return Inertia::location($response->processUrl());
    }

    public function show(string $id): Response
    {
        $this->authorize(Abilities::VIEW->value, Payment::class);
        $payment = Payment::with('microsite')->findOrFail($id);

        return Inertia::render('Payment/Show', compact('payment'));
    }

    public function returnBusiness(Payment $payment, PaymentGatewayContract $gatewayContract): Response
    {
        $microsite = Microsite::query()->where('id', $payment->microsite_id)->first();
        $gatewayContract->queryPayment($payment);

        return Inertia::render('Payment/BackBusiness', [
            'payment' => $payment,
            'microsite' => $microsite
        ]);
    }

}
