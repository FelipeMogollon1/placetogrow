<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InvoiceTemplateExport implements FromCollection, ShouldAutoSize
{
    public function collection(): Collection
    {
        Log::info('in collect InvoiceTemplateExport');
        $data = collect([
            ['reference', 'name', 'surname', 'email', 'document_type', 'document', 'description', 'currency_type', 'amount'],
            ['REF001', 'Felipe', 'Mogollón', 'afme-95@microsites.com', 'CC', '1110555222', 'Description de prueba', 'COP', '150000'],
        ]);

        return $data;

    }
}
