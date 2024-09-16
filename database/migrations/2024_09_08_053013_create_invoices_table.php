<?php

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Constants\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('microsite_id')->nullable();
            $table->string('receipt')->nullable();
            $table->string('reference')->nullable();
            $table->string('microsite_type')->default(MicrositesTypes::INVOICE->value);
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('email')->nullable();
            $table->enum('document_type', DocumentTypes::getDocumentTypes());
            $table->bigInteger('document')->nullable();
            $table->text('description')->nullable();
            $table->enum('currency_type', CurrencyTypes::getCurrencyType());
            $table->decimal('amount', 10, 2)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('status')->default(PaymentStatus::PENDING->value);
            $table->string('process_url')->nullable();
            $table->string('request_id')->nullable();
            $table->string('process_identifier')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('microsite_id')->references('id')->on('microsites')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
