<?php

namespace App\Domain\Invoice\Actions;

use App\Exports\ErrorsExport;
use App\Imports\InvoicesImport;
use App\Infrastructure\Persistence\Models\InvoiceUpload;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ImportInvoicesAction
{
    public function execute(array $data): array
    {
        $file = $data['invoices'];
        $micrositeId = $data['microsite_id'];
        $userId = auth()->user()->id;
        $now = now()->format('Y-m-d_H-i-s');

        Log::info('Importing invoices action...');
        $import = new InvoicesImport($userId, $micrositeId);
        Excel::import($import, $file);

        $relativeFilePath = 'invoices/invoice_' . $now . '.' . $file->getClientOriginalExtension();

        $file->storeAs('public/invoices', 'invoice_' . $now . '.' . $file->getClientOriginalExtension());

        Log::info('Checking import errors...');

        $errorFilePath = null;

        if (!empty($import->errors) && count($import->errors) > 0) {
            $errorFileName = 'import_errors_' . $now . '.xlsx';
            $errorExports = new ErrorsExport($import->errors);

            Excel::store($errorExports, 'invoicesErrors/' . $errorFileName, 'public');
            Log::info('Error file created: ' . $errorFileName);

            $errorFilePath = 'invoicesErrors/' . $errorFileName;
        }

        InvoiceUpload::create([
            'user_id' => $userId,
            'microsite_id' => $micrositeId,
            'storage_path' => $relativeFilePath,
            'error_file_path' => $errorFilePath,
        ]);

        return [
            'success' => count($import->errors) === 0,
            'errorFilePath' => $errorFilePath
        ];
    }
}
