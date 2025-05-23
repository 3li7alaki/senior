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
        Schema::create('child_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->string('institution');
            $table->string('symptoms_age')->nullable();
            $table->text('symptoms')->nullable();
            $table->foreignId('child_id')->constrained('children')->cascadeOnDelete();
            $table->foreignId('diagnose_id')->constrained('diagnoses')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_diagnoses');
    }
};
