<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Models\InvoiceUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class InvoiceUploadController extends Controller
{
    public function destroy(int $id): RedirectResponse
    {
        $invoiceUpload = InvoiceUpload::findOrFail($id);

        if ($invoiceUpload->error_file_path) {
            Storage::disk('public')->delete($invoiceUpload->error_file_path);
        }

        if ($invoiceUpload->storage_path) {
            Storage::disk('public')->delete($invoiceUpload->storage_path);
        }

        $invoiceUpload->delete();

        return to_route('invoices.index')
            ->with('success', 'invoices Upload deleted successfully.');
    }
}
