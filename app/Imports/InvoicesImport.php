<?php

namespace App\Imports;

use App\Constants\InvoiceUploadStatus;
use App\Constants\PaymentStatus;
use App\Exports\ErrorsExport;
use App\Infrastructure\Persistence\Models\Invoice;
use App\Infrastructure\Persistence\Models\InvoiceUpload;
use App\Services\InvoiceValidationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class InvoicesImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    protected int $invoiceUploadId;
    protected $userId;
    protected mixed $micrositeId;
    protected mixed $expirationDate;
    protected mixed $filePath;
    public array $errors = [];
    public int $validRecordsCount = 0;
    public int $total_records = 0;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(array $data, string $filePath)
    {
        $this->invoiceUploadId = $data['id'] ?? null;
        $this->userId = $data['user_id'] ?? null;
        $this->micrositeId = $data['microsite_id'];
        $this->expirationDate = $data['expiration_date'];
        $this->filePath = $filePath;

        if (!$this->userId) {
            Log::error('User ID is missing en InvoicesImport constructor.');
            throw new \Exception('User ID is required for InvoicesImport.');
        }

        Log::info('InvoicesImport creado con user_id: ' . $this->userId . ', microsite_id: ' . $this->micrositeId);
    }

    public function collection(Collection $collection): void
    {
        $validatorService = new InvoiceValidationService();

        foreach ($collection as $row) {
            $rowArray = $row->toArray();
            $this->total_records++;

            try {
                $validatorService->validate($rowArray);

                $existingInvoice = Invoice::where([
                    ['document', '=', (string)$rowArray['document']],
                    ['microsite_id', '=', $this->micrositeId],
                    ['amount', '=', $rowArray['amount']],
                    ['reference', '=', $rowArray['reference']]
                ])->first();

                if ($existingInvoice && $existingInvoice->status === PaymentStatus::APPROVED->value) {
                    Log::info("La factura con el documento {$rowArray['document']} ya está aprobada y no puede ser modificada.");
                    continue;
                }

                if ($existingInvoice) {
                    Log::info("La factura con el documento {$rowArray['document']} ya existe y será omitida.");
                    continue;
                }

                Invoice::updateOrCreate(
                    [
                        'document' => $rowArray['document'],
                        'microsite_id' => $this->micrositeId,
                        'reference' => $rowArray['reference']
                    ],
                    [
                        'reference' => $rowArray['reference'] ?: Str::random(10),
                        'user_id' => $this->userId,
                        'microsite_id' => $this->micrositeId,
                        'expiration_date' => $this->expirationDate,
                        'name' => $rowArray['name'],
                        'surname' => $rowArray['surname'],
                        'email' => $rowArray['email'],
                        'document_type' => $rowArray['document_type'],
                        'document' => (string) $rowArray['document'],
                        'description' => $rowArray['description'] ?? 'Payment by invoice',
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

        $errorFilePath = $this->handleErrors();
        $this->createInvoiceUploadRecord($errorFilePath);
        Log::info("Total de registros válidos importados: {$this->validRecordsCount}");
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

    public function handleErrors(): ?string
    {
        $errorFilePath = null;
        if (!empty($this->errors) && count($this->errors) > 0) {
            log::info('errors found');
            $now = now()->format('Y-m-d_H-i-s');
            $fileName = basename($this->filePath);
            $timestamp = pathinfo($fileName, PATHINFO_FILENAME);

            $parts = explode('_', $timestamp, 2);
            if (count($parts) < 2) {
                $timestamp = $now;
            } else {
                $timestamp = $parts[1];
            }

            $errorFileName = 'import_errors_' . $timestamp . '.xlsx';
            $errorFilePath = 'invoicesErrors/' . $errorFileName;

            $errorExports = new ErrorsExport($this->errors);
            Excel::store($errorExports, $errorFilePath, 'public');

            Log::info('Archivo de errores creado: ' . $errorFileName);
        }

        return $errorFilePath;
    }

    public function createInvoiceUploadRecord(?string $errorFilePath): void
    {
        if ($errorFilePath) {
            $status = InvoiceUploadStatus::COMPLETED_WITH_ERRORS->value;
        } else {
            $status = InvoiceUploadStatus::COMPLETED->value;
        }
        Log::info('register invoice upload status ' . $status);

        InvoiceUpload::updateOrCreate(
            [
                'user_id' => $this->userId,
                'microsite_id' => $this->micrositeId,
                'storage_path' => $this->filePath,
            ],
            [
                'error_file_path' => $errorFilePath,
                'valid_records_count' => $this->validRecordsCount,
                'total_records' => $this->total_records,
                'status' => $status
            ]
        );

        Log::info('register update invoice upload status');
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
}
