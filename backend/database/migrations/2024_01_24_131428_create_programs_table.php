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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->boolean('active')->default(true);
            $table->boolean('all_diagnoses')->default(true);
            $table->boolean('all_ages')->default(true);
            $table->enum('gender',['male', 'female', 'all'])->default('all');
            $table->json('days');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('min_age')->nullable();
            $table->integer('max_age_male')->nullable();
            $table->integer('max_age_female')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
