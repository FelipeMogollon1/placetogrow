<?php

namespace App\Imports;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Http\Requests\Invoice\ImportExcelRequest;
use App\Infrastructure\Persistence\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class InvoicesImport implements ToCollection, WithHeadingRow
{
    protected $userId;
    protected $micrositeId;
    public $errors = [];

    public function __construct($userId, $micrositeId)
    {
        $this->userId = $userId;
        $this->micrositeId = $micrositeId;
    }

    public function collection(Collection $collection): void
    {
        foreach ($collection as $row) {
            try {
                $this->validateRow($row);

                Invoice::updateOrCreate(
                    ['document' => $row['document']],
                    [
                        'reference' => $row['reference'],
                        'user_id' => $this->userId,
                        'microsite_id' => $this->micrositeId,
                        'name' => $row['name'],
                        'surname' => $row['surname'],
                        'email' => $row['email'],
                        'document_type' => $row['document_type'],
                        'document' => (string) $row['document'],
                        'description' => $row['description'],
                        'currency_type' => $row['currency_type'],
                        'amount' => $row['amount'],
                    ]
                );
            } catch (ValidationException $e) {
                Log::warning('Fila inválida: ' . json_encode($row) . ' - Errores: ' . json_encode($e->errors()));
                $this->errors[] = [
                    'row' => $row->toArray(),
                    'errors' => $e->errors()
                ];
                continue;
            } catch (\Exception $e) {
                Log::error('Error general en la fila: ' . json_encode($row) . ' - Error: ' . $e->getMessage());
                continue;
            }
        }
    }

    private function validateRow(Collection $row): void
    {
        $data = $row->toArray();

        $rules = [
            'reference' => 'required|string',
            'user_id' => 'nullable|integer|exists:users,id',
            'microsite_id' => 'nullable|integer|exists:microsites,id',
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => [
                'required',
                'email',
                'regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/'
            ],
            'document_type' => [
                'nullable',
                'string',
                Rule::in(DocumentTypes::getDocumentTypes())
            ],
            'document' => ['required', 'max:255', function ($attribute, $value, $fail) use ($data) {
                $patterns = [
                    'CC' => '/^[1-9][0-9]{3,9}$/',
                    'CE' => '/^([a-zA-Z]{1,5})?[1-9][0-9]{3,7}$/',
                    'TI' => '/^[1-9][0-9]{4,11}$/',
                    'NIT' => '/^[1-9]\d{6,9}$/',
                ];
                $documentType = $data['document_type'];

                if (isset($patterns[$documentType])) {
                    if (!preg_match($patterns[$documentType], $value)) {
                        $fail("El $attribute no es válido para el tipo de documento $documentType.");
                    }
                } else {
                    $fail("Tipo de documento $documentType no reconocido.");
                }
            }],
            'description' => 'required|string',
            'currency_type' => ['required', Rule::in(CurrencyTypes::getCurrencyType())],
            'amount' => 'required|numeric|min:1|max:999999999',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }


    public function chunkSize(): int
    {
        return 1000;
    }

    private function isValidRow($row): bool
    {
        return isset($row['document']);
    }
}
