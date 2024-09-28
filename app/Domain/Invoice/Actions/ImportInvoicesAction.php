<?php

namespace App\Domain\Invoice\Actions;

use App\Jobs\Invoice\ProcessInvoiceImport;
use Illuminate\Support\Facades\Log;
use App\Infrastructure\Persistence\Models\InvoiceUpload;

class ImportInvoicesAction
{
    public function execute(array $data): bool
    {
        $file = $data['invoices'];
        $now = now()->format('Y-m-d_H-i-s');

        $fileName = 'invoice_' . $now . '.' . $file->getClientOriginalExtension();
        $relativeFilePath = 'invoices/' . $fileName;
        $file->storeAs('public/invoices', $fileName);

        Log::info('Importing invoices saved in: ' . $relativeFilePath);

        $invoiceUpload = InvoiceUpload::create([
            'user_id' => auth()->user()->id,
            'microsite_id' => $data['microsite_id'],
            'storage_path' => $relativeFilePath,
        ]);

        Log::info('Importing invoices action initiated... ' . $invoiceUpload->id);

        ProcessInvoiceImport::dispatch($relativeFilePath, $invoiceUpload->id);

        return true;
    }
}
