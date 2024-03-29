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
            $table->string('garage', 100)->nullable();
            $table->string('type_maintenance', 100)->nullable();
            $table->json('apk', 100)->nullable();
            $table->date('apk_date')->nullable();
            $table->json('washed', 100)->nullable();
            $table->json('tyre_pressure', 100)->nullable();
            $table->text('tasks_messages')->nullable();
            $table->float('total_price')->nullable();
            $table->integer('mileage_begin')->nullable();
            $table->integer('mileage_end')->nullable();
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
