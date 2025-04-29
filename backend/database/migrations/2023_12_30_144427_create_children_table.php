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
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->timestamp('birth_date');
            $table->string('birth_place')->nullable();
            $table->string('cpr')->unique()->nullable();
            $table->enum('gender',['male','female'])->default('male');
            $table->string('other_number')->nullable();
            $table->string('lives_with')->nullable();
            $table->string('guardian_relation')->nullable();
            $table->string('building')->nullable();
            $table->string('apartment')->nullable();
            $table->string('street')->nullable();
            $table->string('block')->nullable();
            $table->string('area')->nullable();
            $table->string('other_center')->nullable();
            $table->string('other_center_year')->nullable();
            $table->text('photo')->nullable();
            $table->text('national_id')->nullable();
            $table->foreignId('guardian_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('nationality_id')->nullable()->constrained('nationalities')->nullOnDelete();
            $table->string('last_handler')->nullable();
            $table->boolean('data_file_needed')->default(false);
            $table->boolean('ministry_registered')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
