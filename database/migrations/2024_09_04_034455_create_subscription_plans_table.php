<?php

use App\Constants\CurrencyTypes;
use App\Constants\SubscriptionPeriods;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('description');
            $table->decimal('amount', 10, 2);
            $table->enum('currency', CurrencyTypes::getCurrencyType());
            $table->enum('subscription_period', SubscriptionPeriods::getAllSubscriptionPeriods());
            $table->integer('expiration_time');
            $table->string('additional_info')->nullable();
            $table->string('expiration_additional_info')->nullable();
            $table->unsignedBigInteger('microsite_id');
            $table->timestamps();

            $table->foreign('microsite_id')
                ->references('id')
                ->on('microsites')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
