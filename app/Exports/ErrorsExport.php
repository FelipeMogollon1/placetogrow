<?php

namespace App\Exports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ErrorsExport implements FromCollection, WithHeadings
{
    protected Collection $errors;

    public function __construct(array $errors)
    {
        $this->errors = collect($errors);
    }

    public function collection(): Collection
    {
        Log::info('in collect ErrorsExport');

        return $this->errors->map(function ($error) {
            $rowData = is_array($error['row']) ? $error['row'] : json_decode($error['row'], true);

            $errorMessages = [];

            if (!empty($error['errors']) || !empty($error['error'])) {
                foreach ($error['errors'] ?? $error['error'] as $field => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $message) {
                            $errorMessages[] = "$field: $message";
                        }
                    }
                }
            }

            return [
                'reference' => $rowData['reference'] ?? null,
                'name' => $rowData['name'] ?? null,
                'surname' => $rowData['surname'] ?? null,
                'email' => $rowData['email'] ?? null,
                'document_type' => $rowData['document_type'] ?? null,
                'document' => $rowData['document'] ?? null,
                'description' => $rowData['description'] ?? null,
                'currency_type' => $rowData['currency_type'] ?? null,
                'amount' => $rowData['amount'] ?? null,
                'error_type' => $error['type'],
                'error_messages' => implode(', ', $errorMessages),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Reference',
            'Name',
            'Surname',
            'Email',
            'Document Type',
            'Document',
            'Description',
            'Currency Type',
            'Amount',
            'Error Type',
            'Error Messages',
        ];
    }
}
