<?php

use App\Constants\DocumentTypes;
use App\Constants\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('description');
            $table->string('name', 150);
            $table->string('surname', 150);
            $table->string('email');
            $table->enum('document_type', DocumentTypes::getDocumentTypes())->nullable();
            $table->string('process_url')->nullable();
            $table->string('request_id')->nullable();
            $table->string('document', 20)->nullable();
            $table->string('mobile')->nullable();
            $table->string('company')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('token')->nullable();
            $table->text('sub_token')->nullable();
            $table->text('lastDigits')->nullable();
            $table->date('validUntil')->nullable();
            $table->enum('status', PaymentStatus::getPaymentStatus())->nullable();
            $table->string('process_identifier')->nullable();
            $table->foreignId('subscription_plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('microsite_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
