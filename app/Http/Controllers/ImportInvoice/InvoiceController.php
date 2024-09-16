<?php

namespace App\Http\Controllers\ImportInvoice;

use App\Contracts\PaymentGatewayContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\ImportInvoicesRequest;
use App\Imports\InvoicesImport;
use App\Infrastructure\Persistence\Models\Invoice;
use App\Infrastructure\Persistence\Models\Microsite;
use App\ViewModels\Invoice\InvoiceIndexViewModel;
use App\ViewModels\Invoice\InvoiceMicrositeIndexViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function import(ImportInvoicesRequest $request): Response
    {
        $request->validated();
        $import = new InvoicesImport(auth()->user()->id, $request->input('microsite_id'));

        Excel::import($import, $request->file('invoices'));
        $microsites = Microsite::where('microsite_type', 'invoice')->get();

        return Inertia::render('Invoices/Index', [
            'invoices' => Invoice::orderBy('name', 'asc')->paginate(5),
            'microsites' => $microsites
        ]);
    }

    public function index(Request $request, InvoiceIndexViewModel $viewModel, InvoiceMicrositeIndexViewModel $viewModelMicrosite): Response
    {
        $search = $request->only(['search']);

        return Inertia::render('Invoices/Index', [
            'invoices' => $viewModel->fromAuthenticatedUser()->getInvoices($search),
            'filter' => $search ?? '',
            'microsites' => $viewModelMicrosite->getMicrosites()
        ]);
    }

    public function processPayment(int $invoiceId, Request $request, PaymentGatewayContract $gateway): \Symfony\Component\HttpFoundation\Response
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $response = $gateway->createSessionInvoice($invoice, $request);

        return Inertia::location($response->processUrl());
    }


    public function show(Invoice $invoice): View
    {
        return view('invoices.show', compact('invoice'));
    }

    public function destroy(string $id): RedirectResponse
    {
        Invoice::findOrFail($id)->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

}
