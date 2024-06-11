<?php

use App\Enums\InvoiceStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sri_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->enum('status', array_column(InvoiceStatusEnum::cases(), 'value')); // Estado de la respuesta (autorizado, rechazado, etc.)
            $table->string('authorization_number')->nullable(); // Número de autorización
            $table->dateTime('authorization_date')->nullable(); // Fecha de autorización
            $table->json('response_data'); // Datos completos de la respuesta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sri_responses');
    }
};
