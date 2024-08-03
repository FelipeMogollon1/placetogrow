<?php

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique()->nullable();
            $table->string('receipt')->nullable();
            $table->string('payer_name')->nullable();
            $table->string('payer_surname')->nullable();
            $table->string('payer_email')->nullable();
            $table->string('payer_phone')->nullable();
            $table->string('payer_company')->nullable();
            $table->enum('payer_document_type', DocumentTypes::getDocumentTypes())->nullable();
            $table->string('payer_document')->unique()->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('amount')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->enum('currency', CurrencyTypes::getCurrencyType())->nullable();
            $table->enum('status', PaymentStatus::getPaymentStatus())->nullable();
            $table->string('process_url')->nullable();
            $table->string('request_id')->nullable();
            $table->string('process_identifier')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('microsite_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('microsite_id')->references('id')->on('microsites');
            $table->json('additional_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
