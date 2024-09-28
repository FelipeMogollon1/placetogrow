<?php

namespace App\Http\Controllers\ImportInvoice;

use App\Constants\Abilities;
use App\Contracts\PaymentGatewayContract;
use App\Domain\Invoice\Actions\ImportInvoicesAction;
use App\Exports\InvoiceTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\ImportInvoicesRequest;
use App\Infrastructure\Persistence\Models\Invoice;
use App\ViewModels\Invoice\InvoiceIndexViewModel;
use App\ViewModels\Invoice\InvoiceMicrositeIndexViewModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InvoiceController extends Controller
{
    use AuthorizesRequests;
    public function downloadTemplate(): BinaryFileResponse
    {
        Log::info('Generating the invoice template...');

        return Excel::download(new InvoiceTemplateExport(), 'invoice_template.xlsx');
    }

    public function import(ImportInvoicesRequest $request, ImportInvoicesAction $importAction): BinaryFileResponse|RedirectResponse
    {
        $result = $importAction->execute($request->validated());

        if ($result) {
            session()->flash('success', 'Import done successfully.');
        } else {
            session()->flash('error', 'Some invoices failed to import. Download error file here: ' . $errorFilePath);
        }

        return to_route('invoices.index');
    }

    public function index(Request $request, InvoiceIndexViewModel $viewModel, InvoiceMicrositeIndexViewModel $viewModelMicrosite): Response
    {
        $this->authorize(Abilities::VIEW_ANY->value, Invoice::class);

        Artisan::call('app:transactions-consult');
        $search = $request->only(['search']);

        return Inertia::render('Invoices/Index', [
            'invoices' => $viewModel->fromAuthenticatedUser()->getInvoices($search),
            'filter' => $search ?? '',
            'microsites' => $viewModelMicrosite->getMicrosites(),
            'uploadInvoice' => $viewModel->getUploadInvoices()
        ]);
    }

    public function processPayment(int $invoiceId, Request $request, PaymentGatewayContract $gateway): \Symfony\Component\HttpFoundation\Response
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $response = $gateway->createSessionInvoice($invoice, $request);

        return Inertia::location($response->processUrl());
    }


    public function show(string $id): Response
    {
        $this->authorize(Abilities::VIEW->value, Invoice::class);

        $invoice = Invoice::with('microsite')->findOrFail($id);

        return Inertia::render('Invoices/Show', compact('invoice'));
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->authorize(Abilities::DELETE->value, Invoice::class);
        Invoice::findOrFail($id)->delete();

        return to_route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }



}
