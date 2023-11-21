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
        Schema::create('refuelings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->timestamp('date');
            $table->string('gas_station', 100);
            $table->integer('fuel_type');
            $table->float('amount');
            $table->float('unit_price');
            $table->float('total_price');
            $table->integer('mileage_begin');
            $table->integer('mileage_end');
            $table->float('fuel_usage_onboard_computer');
            $table->float('fuel_usage');
            $table->float('costs_per_kilometer');
            $table->integer('tyres');
            $table->integer('heater');
            $table->integer('airconditioning');
            $table->integer('trailer');
            $table->integer('luggage');
            $table->integer('city_driving');
            $table->integer('country_road_driving');
            $table->integer('highway_driving');
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refuelings');
    }
};
