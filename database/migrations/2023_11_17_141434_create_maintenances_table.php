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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->timestamp('date');
            $table->string('garage', 100);
            $table->integer('small_maintenance');
            $table->integer('big_maintenance');
            $table->integer('washed');
            $table->integer('tyre_pressure');
            $table->string('tasks_messages')->nullable();
            $table->float('total_price');
            $table->integer('mileage_begin');
            $table->integer('mileage_end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
