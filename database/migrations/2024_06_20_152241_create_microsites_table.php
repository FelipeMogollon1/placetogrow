<?php

use App\Constants\MicrositesTypes;
use App\Constants\DocumentTypes;
use App\Constants\CurrencyTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('microsites', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 150)->unique();
            $table->string('name', 150);
            $table->string('logo')->nullable();
            $table->enum('document_type', DocumentTypes::getDocumentTypes())->nullable();
            $table->string('document', 20)->nullable();
            $table->enum('microsite_type', MicrositesTypes::getMicrositesTypes());
            $table->enum('currency', CurrencyTypes::getCurrencyType());
            $table->unsignedBigInteger('payment_expiration_time');
            $table->foreignId('category_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('form_id')->nullable()->constrained();
            $table->timestamp('enabled_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('microsites');
    }
};
