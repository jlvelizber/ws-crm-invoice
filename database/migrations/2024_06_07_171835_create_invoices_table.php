<?php

use App\Enums\InvoiceStatusEnum;
use App\Enums\SourceCreateInvoiceEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wp_order_id')->nullable()->unique(); // WordPress Order ID (nullable for external sources)
            $table->string('invoice_number')->unique();
            $table->date('issue_date')->nullable(); // FOR EXTERNAL USE
            $table->integer('customer_id');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax', 15, 2);
            $table->decimal('total', 15, 2);
            $table->string('access_key')->nullable(); // Clave de acceso
            $table->string('authorization_code')->nullable(); // Código de autorización
            $table->string('environment'); // Ambiente (pruebas/producción)
            $table->enum('invoice_status', array_column(InvoiceStatusEnum::cases(), 'value'))->default(InvoiceStatusEnum::PENDING->value); // Estado de la factura (pending, sent, authorized, rejected, etc.)
            $table->json('additional_info')->nullable(); // Información adicional
            $table->enum('source', array_column(SourceCreateInvoiceEnum::cases(), 'value'))->default(SourceCreateInvoiceEnum::LOCAL->value); // Fuente de la factura (wordpress, external)
            $table->timestamps();
            $table->softDeletes(); //

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
