<?php

use App\Constants\AdditionalValueTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('header')->nullable();
            $table->string('footer')->nullable();
            $table->string('color')->nullable();
            $table->string('additional_info')->nullable();
            $table->string('expiration_additional_info')->nullable();
            $table->json('configuration');
            $table->unsignedBigInteger('tries');
            $table->unsignedBigInteger('backoff');
            $table->string('additionalValue')->nullable();
            $table->enum('additionalValueType', AdditionalValueTypes::getAdditionalValueTypes())->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
