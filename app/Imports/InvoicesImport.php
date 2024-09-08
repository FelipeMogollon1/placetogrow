<?php

namespace App\Imports;

use App\Infrastructure\Persistence\Models\Invoice;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class InvoicesImport implements ToCollection, WithHeadingRow
{

    public function collection(Collection $collection): void
    {
        foreach ($collection as $row) {
            if ($this->isValidRow($row)) {
                Invoice::updateOrCreate(
                    ['document' => $row['document']], // Unique field to avoid duplicates
                    [
                        'reference' => $row['reference'],
                        'user_id' => $row['user_id'],
                        'microsite_id' => $row['microsite_id'],
                        'name' => $row['name'],
                        'surname' => $row['surname'],
                        'email' => $row['email'],
                        'document_type' => $row['document_type'],
                        'document' => $row['document'],
                        'description' => $row['description'],
                        'currency_type' => $row['currency_type'],
                        'amount' => $row['amount'],
                    ]
                );
            }
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
    private function isValidRow($row): bool
    {
        return isset($row['document']) && isset($row['user_id']) && isset($row['microsite_id']);
    }

}
