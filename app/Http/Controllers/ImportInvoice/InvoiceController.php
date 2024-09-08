<?php

namespace App\Http\Controllers\ImportInvoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\ImportInvoicesRequest;
use App\Imports\InvoicesImport;
use App\Infrastructure\Persistence\Models\Invoice;
use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{

    public function import(ImportInvoicesRequest $request): Response
    {
        $request->validated();
        Excel::import(new InvoicesImport, $request->file('invoices'));

        return Inertia::render('Invoices/Index', [
            'invoices' => Invoice::orderBy('name', 'asc')->paginate(5),
            'success' => 'Invoices imported successfully.',
        ]);
    }

    public function index(): Response
    {

        $invoices = Invoice::orderBy('name', 'asc')->paginate(5);

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices
        ]);
    }


    public function create(): View
    {
        return view('invoices.create');
    }


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'nullable|string',
            'surname' => 'nullable|string',
            'email' => 'nullable|email',
            'document_type' => 'nullable|string',
            'document' => 'nullable|numeric',
            'description' => 'nullable|string',
            'currency_type' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'user_id' => 'required|exists:users,id',
            'microsite_id' => 'required|exists:microsites,id',
        ]);

        Invoice::create($request->all());

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }


    public function show(Invoice $invoice): View
    {
        return view('invoices.show', compact('invoice'));
    }


    public function edit(Invoice $invoice): View
    {
        return view('invoices.edit', compact('invoice'));
    }


    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $request->validate([
            'name' => 'nullable|string',
            'surname' => 'nullable|string',
            'email' => 'nullable|email',
            'document_type' => 'nullable|string',
            'document' => 'nullable|numeric',
            'description' => 'nullable|string',
            'currency_type' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'user_id' => 'required|exists:users,id',
            'microsite_id' => 'required|exists:microsites,id',
        ]);

        $invoice->update($request->all());

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }


    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
