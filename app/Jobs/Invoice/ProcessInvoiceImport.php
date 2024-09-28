<?php

namespace App\Jobs\Invoice;

use App\Imports\InvoicesImport;
use App\Infrastructure\Persistence\Models\InvoiceUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ProcessInvoiceImport implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected string $filePath;
    protected int $invoiceUploadId;

    public function __construct(string $filePath, int $invoiceUploadId)
    {
        $this->filePath = $filePath;
        $this->invoiceUploadId = $invoiceUploadId;
    }

    public function handle(): void
    {
        Log::info('Processing invoice import for file: ' . $this->filePath);
        $invoiceUpload = InvoiceUpload::find($this->invoiceUploadId);

        $import = new InvoicesImport([
            'id' => $invoiceUpload->id,
            'microsite_id' => $invoiceUpload->microsite_id,
            'user_id' => $invoiceUpload->user_id,
        ], $this->filePath);

        Excel::import($import, storage_path('app/public/' . $this->filePath));

        Log::info('Invoice upload record actualizado con datos adicionales.');

    }
}
