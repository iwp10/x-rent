<?php

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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('license_plate');
            $table->integer('engine_cc');
            $table->string('transmission');
            $table->integer('helm_included')->default(2);
            $table->text('condition_notes')->nullable();
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
            $table->unsignedBigInteger('price_daily');
            $table->unsignedBigInteger('price_weekly');
            $table->unsignedBigInteger('price_monthly');
            $table->string('image')->nullable();

            // Extra metadata to preserve rich frontend design
            $table->enum('type', ['motor', 'mobil'])->default('motor');
            $table->string('brand')->nullable();
            $table->string('year', 10)->nullable();
            $table->text('facilities')->nullable();
            $table->json('features')->nullable();
            $table->json('gallery')->nullable();
            $table->json('specs')->nullable();
            $table->json('requirements')->nullable();
            $table->string('badge')->nullable();
            $table->string('subtitle')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
