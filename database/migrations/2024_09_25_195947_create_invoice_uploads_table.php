<?php

use App\Constants\InvoiceUploadStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('invoice_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('microsite_id')->constrained()->onDelete('cascade');
            $table->date('expiration_date')->nullable();
            $table->string('storage_path')->nullable();
            $table->string('error_file_path')->nullable();
            $table->unsignedInteger('valid_records_count')->nullable();
            $table->unsignedInteger('total_records')->nullable();
            $table->enum('status', InvoiceUploadStatus::getInvoiceUploadStatus())->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'microsite_id', 'storage_path']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_uploads');
    }
};
