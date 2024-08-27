<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');                           // Nombre del cliente
            $table->string('identification');                 // Cédula/RUC del cliente
            $table->string('email')->nullable();              // Email del cliente
            $table->string('phone')->nullable();              // Teléfono del cliente
            $table->string('address')->nullable();            // Dirección del cliente
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
