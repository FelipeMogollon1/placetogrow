<?php

use App\Constants\CurrencyTypes;
use App\Constants\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')
                ->constrained('subscriptions')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->foreignId('subscription_plan_id')
                ->constrained('subscription_plans')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            $table->enum('currency', CurrencyTypes::getCurrencyType());
            $table->decimal('amount', 10, 2);
            $table->enum('status', PaymentStatus::getPaymentStatus())->default(PaymentStatus::PENDING);
            $table->integer('attempt_count')->default(0);
            $table->string('request_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }
};
