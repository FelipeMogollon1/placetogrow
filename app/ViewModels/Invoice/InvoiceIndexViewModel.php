<?php

namespace App\ViewModels\Invoice;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Invoice;
use App\Infrastructure\Persistence\Models\InvoiceUpload;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class InvoiceIndexViewModel extends ViewModel
{
    public function __construct(protected User $user)
    {

    }

    public static function fromAuthenticatedUser(): ?InvoiceIndexViewModel
    {
        return new self(auth()->user());
    }

    public function getInvoices(array $filters): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $permissionsUser = $this->user->getAllPermissions()->pluck('name')->toArray();
        $rolesUser = $this->user->roles->pluck('name')->toArray();

        $query = Invoice::query()

            ->select(
                'invoices.id',
                'invoices.reference',
                'invoices.name',
                'invoices.surname',
                'invoices.currency_type',
                'invoices.amount',
                'invoices.status',
                'invoices.expiration_date',
                'microsites.name as microsite_name',
                'forms.additionalValue as additionalValue',
                'forms.additionalValueType as additionalValueType',
            )
            ->join('microsites', 'invoices.microsite_id', '=', 'microsites.id')
            ->join('forms', 'forms.id', '=', 'microsites.form_id');


        if (in_array(Permissions::INVOICES_INDEX->value, $permissionsUser)) {
            if (in_array(Roles::ADMIN->value, $rolesUser)) {
                $query->where('microsites.user_id', $this->user->id);
            } elseif (in_array(Roles::GUEST->value, $rolesUser)) {
                $query->leftJoin('users', 'users.email', '=', 'invoices.email')
                    ->where('invoices.document_type', $this->user->document_type)
                    ->where('invoices.document', $this->user->document)
                    ->where('invoices.email', $this->user->email);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoices.reference', 'like', '%' . $search . '%')
                    ->orWhere('microsites.name', 'like', '%' . $search . '%')
                    ->orWhere('invoices.name', 'like', '%' . $search . '%')
                    ->orWhere('invoices.surname', 'like', '%' . $search . '%')
                    ->orWhere('invoices.amount', 'like', '%' . $search . '%')
                    ->orWhere('invoices.expiration_date', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('invoices.id', 'desc')->paginate(10);
    }


    public function getUploadInvoices(): LengthAwarePaginator
    {
        $permissionsUser = $this->user->getAllPermissions()->pluck('name')->toArray();
        $rolesUser = $this->user->roles->pluck('name')->toArray();

        $query = InvoiceUpload::query()
            ->select(
                'microsites.name as microsite',
                'users.name as name',
                'invoice_uploads.storage_path',
                'invoice_uploads.error_file_path',
                'invoice_uploads.created_at',
                'invoice_uploads.valid_records_count',
                'invoice_uploads.total_records',
                'invoice_uploads.status',
                'invoice_uploads.id'
            )
            ->join('microsites', 'microsites.id', '=', 'invoice_uploads.microsite_id')
            ->join('users', 'users.id', '=', 'invoice_uploads.user_id');

        if (in_array(Permissions::INVOICES_INDEX->value, $permissionsUser)) {
            if (in_array(Roles::ADMIN->value, $rolesUser)) {
                $query->where('microsites.user_id', $this->user->id);
            } elseif (in_array(Roles::GUEST->value, $rolesUser)) {
                return new LengthAwarePaginator([], 0, 5, 1);
            }
        }

        return $query->orderBy('invoice_uploads.created_at', 'desc')->paginate(5);
    }

}
