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
            $table->date('date');
            $table->string('garage', 100);
            $table->string('type_maintenance', 100)->nullable();
            $table->string('washed', 100);
            $table->string('tyre_pressure', 100);
            $table->string('tasks_messages', 100)->nullable();
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
