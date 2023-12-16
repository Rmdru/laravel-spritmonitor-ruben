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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('brand', 50);
            $table->string('model', 50);
            $table->string('version', 50);
            $table->string('engine', 50)->nullable();
            $table->float('factory_specification_fuel_usage');
            $table->float('average_fuel_usage')->nullable();
            $table->integer('mileage_start');
            $table->integer('mileage_latest')->nullable();
            $table->date('purchase_date');
            $table->string('license_plate', 20)->nullable();
            $table->string('fuel_type', 30);
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
