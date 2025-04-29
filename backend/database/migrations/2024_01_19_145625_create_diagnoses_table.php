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
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->enum('type', ['preset', 'other'])->default('preset');
            $table->foreignId('child_id')->nullable()->constrained('children')->cascadeOnDelete();
            $table->timestamps();
        });
        \App\Models\Diagnose::query()->insert([
            [
                'name' => 'اضطراب التوحد',
                'type' => 'preset',
            ],
            [
                'name' => 'إعاقة ذهنية',
                'type' => 'preset',
            ],
            [
                'name' => 'متالزمة داون',
                'type' => 'preset',
            ],
            [
                'name' => 'صعوبات تعلم',
                'type' => 'preset',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnoses');
    }
};
