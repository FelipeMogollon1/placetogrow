<?php

namespace App\Http\Controllers\Payment;

use App\Domain\Payment\StorePaymentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function store (StorePaymentRequest  $request,StorePaymentAction $action ): RedirectResponse
    {
        $action->execute($request->validated());

        return redirect()->back();
    }
}
