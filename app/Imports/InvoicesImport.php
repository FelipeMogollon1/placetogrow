<?php

namespace App\Imports;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\PaymentStatus;
use App\Infrastructure\Persistence\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
    private $validRecordsCount = 0;

    public function __construct(int $userId, int $micrositeId)
    {
        $this->userId = $userId;
        $this->micrositeId = $micrositeId;
    }

    public function collection(Collection $collection): void
    {
        foreach ($collection as $row) {
            $rowArray = $row->toArray();

            try {
                $this->validateRow($rowArray);

                $existingInvoice = Invoice::where('document', (string) $rowArray['document'])
                    ->where('microsite_id', $this->micrositeId)
                    ->orWhere('amount', $rowArray['amount'])
                    ->first();

                if ($existingInvoice && $existingInvoice->status === PaymentStatus::APPROVED->value) {
                    Log::info("La factura con el documento {$rowArray['document']} ya está aprobada y no puede ser modificada.");
                    continue;
                }

                Invoice::updateOrCreate(
                    [
                        'document' => $rowArray['document'],
                        'microsite_id' => $this->micrositeId,
                    ],
                    [
                        'reference' => $rowArray['reference'] ?: Str::random(10),
                        'user_id' => $this->userId,
                        'microsite_id' => $this->micrositeId,
                        'name' => $rowArray['name'],
                        'surname' => $rowArray['surname'],
                        'email' => $rowArray['email'],
                        'document_type' => $rowArray['document_type'],
                        'document' => (string) $rowArray['document'],
                        'description' => $rowArray['description'] ?? 'Payment by ' . $rowArray['reference'],
                        'currency_type' => $rowArray['currency_type'],
                        'amount' => $rowArray['amount'],
                    ]
                );
                $this->validRecordsCount++;

            } catch (ValidationException $e) {
                $this->logValidationErrors($row, $e);
            } catch (\Exception $e) {
                $this->logGeneralError($row, $e);
            }
        }
        Log::info("Total de registros válidos importados: {$this->validRecordsCount}");

    }

    private function validateRow(array $data): void
    {
        $rules = [
            'reference' => 'nullable|string',
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => [
                'required',
                'email',
                'regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/'
            ],
            'document_type' => [
                'required',
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

                if (!isset($patterns[$documentType])) {
                    $fail("Tipo de documento $documentType no reconocido.");
                    return;
                }

                if (!preg_match($patterns[$documentType], $value)) {
                    $fail("El $attribute no es válido para el tipo de documento $documentType.");
                }
            }],
            'description' => 'nullable|string',
            'currency_type' => ['required', Rule::in(CurrencyTypes::getCurrencyType())],
            'amount' => array_merge([
                'required',
                'numeric',
                'min:1',
                'max:999999999',
            ], $this->amountRule($data['currency_type'])),
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    private function logValidationErrors($row, ValidationException $e): void
    {
        Log::warning('Fila inválida: ' . json_encode($row) . ' - Errores: ' . json_encode($e->errors()));
        $this->errors[] = [
            'row' => $row->toArray(),
            'errors' => $e->errors(),
            'type' => 'validation'
        ];
    }

    private function logGeneralError($row, \Exception $e): void
    {
        Log::error('Error general en la fila: ' . json_encode($row) . ' - Error: ' . $e->getMessage());
        $this->errors[] = [
            'row' => $row->toArray(),
            'error' => $e->getMessage(),
            'type' => 'general'
        ];
    }


    public function chunkSize(): int
    {
        return 1000;
    }

    public function headings(): array
    {
        return [
            'reference',
            'name',
            'surname',
            'email',
            'document_type',
            'document',
            'description',
            'currency_type',
            'amount',
        ];
    }

    public function getValidRecordsCount(): int
    {
        return $this->validRecordsCount;
    }

    private function amountRule($data): array
    {
        $rules = [];

        if ($data=== CurrencyTypes::USD->value) {
            $rules[] = 'max:99999';
        } elseif ($data=== CurrencyTypes::COP->value) {
            $rules[] = 'max:999999999';
        }
        return $rules;
    }
}
