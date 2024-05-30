<?php

use App\Enums\PlanTermType;
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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->unique();
            $table->string('description')->nullable();
            $table->double('price');
            $table->boolean('active')->default(1);
            $table->string('image_url')->nullable();
            $table->integer('term_number')->default(1);
            $table->enum(
                'term_type_time',
                array_column(PlanTermType::cases(), 'value')
            )->default(PlanTermType::YEAR->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
