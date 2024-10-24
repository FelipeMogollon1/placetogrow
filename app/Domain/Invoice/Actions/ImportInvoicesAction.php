<?php

namespace App\Domain\Invoice\Actions;

use App\Constants\InvoiceUploadStatus;
use App\Jobs\Invoice\ProcessInvoiceImport;
use Illuminate\Support\Facades\Log;
use App\Infrastructure\Persistence\Models\InvoiceUpload;

class ImportInvoicesAction
{
    public function execute(array $data): void
    {
        $file = $data['invoices'];

        $fileName = 'invoice_' . now()->format('Y-m-d_H-i-s') . '.' . $file->getClientOriginalExtension();
        $relativeFilePath = 'invoices/' . $fileName;
        $file->storeAs('public/invoices', $fileName);

        Log::info('Importing invoices saved in: ' . $relativeFilePath);

        $invoiceUpload = InvoiceUpload::create([
            'user_id' => auth()->user()->id,
            'microsite_id' => $data['microsite_id'],
            'expiration_date' => $data['expiration_date'],
            'storage_path' => $relativeFilePath,
            'status' => InvoiceUploadStatus::PENDING->value
        ]);

        Log::info('Importing invoices action initiated... ' . $invoiceUpload->id);
        ProcessInvoiceImport::dispatch($relativeFilePath, $invoiceUpload->id);
    }
}
