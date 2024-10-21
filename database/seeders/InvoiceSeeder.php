<?php

namespace Database\Seeders;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Constants\PaymentStatus;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Invoice;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::whereHas('roles', function ($query) {
            $query->where('name', Roles::ADMIN->value);
        })->first();

        $micrositeInvoices = Microsite::where('microsite_type', MicrositesTypes::INVOICE->value)->get();
        $statuses = [PaymentStatus::PENDING->value, PaymentStatus::APPROVED->value];

        if ($micrositeInvoices->isEmpty()) {
            return;
        }

        for ($i = 1; $i <= 15; $i++) {
            Invoice::create([
                'user_id' => $adminUser->id,
                'microsite_id' => $micrositeInvoices->random()->id,
                'receipt' => 'RECEIPT-' . $i,
                'reference' => 'REF-' . strtoupper(substr(uniqid(), -6)),
                'microsite_type' => MicrositesTypes::INVOICE->value,
                'name' => 'Nombre ' . $i,
                'surname' => 'Apellido ' . $i,
                'email' => 'guest@microsites.com',
                'document_type' => DocumentTypes::CC->value,
                'document' => '333333333',
                'description' => 'Descripción de la factura ' . $i,
                'currency_type' => CurrencyTypes::COP->value,
                'amount' => rand(10000, 50000),
                'status' => $statuses[array_rand($statuses)],
                'expiration_date' => match (rand(1, 3)) {
                    1 => now()->subDays(rand(1, 30)),
                    2 => now(),
                    3 => now()->addDays(rand(1, 30)),
                },
            ]);
        }
    }
}
