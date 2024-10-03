<?php

use App\Constants\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

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
            $table->decimal('amount', 10, 2);
            $table->enum('status', PaymentStatus::getPaymentStatus())->default(PaymentStatus::PENDING);
            $table->integer('attempt_count')->default(0);
            $table->timestamp('last_attempt_at')->nullable();
            $table->timestamp('next_retry_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }
};
