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
        Schema::create('data_files', function (Blueprint $table) {
            $table->id();
            $table->string('father_name')->nullable();
            $table->string('father_job')->nullable();
            $table->foreignId('father_nationality_id')->nullable()->constrained('nationalities')->nullOnDelete();
            $table->string('father_phone')->nullable();
            $table->string('father_work_phone')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_job')->nullable();
            $table->foreignId('mother_nationality_id')->nullable()->constrained('nationalities')->nullOnDelete();
            $table->string('mother_phone')->nullable();
            $table->string('mother_work_phone')->nullable();
            $table->string('house_phone')->nullable();
            $table->string('close_person_phone')->nullable();
            $table->string('siblings_count')->nullable();
            $table->string('sibling_order')->nullable();
            $table->integer('father_age_at_birth')->nullable();
            $table->integer('mother_age_at_birth')->nullable();
            $table->string('parent_relation')->nullable();
            $table->text('pregnancy_issue')->nullable();
            $table->text('birth_issue')->nullable();
            $table->text('familial_issue')->nullable();
            $table->boolean('heart_check')->nullable();
            $table->timestamp('heart_check_date')->nullable();
            $table->text('heart_check_result')->nullable();
            $table->boolean('thyroid_check')->nullable();
            $table->timestamp('thyroid_check_date')->nullable();
            $table->text('thyroid_check_result')->nullable();
            $table->boolean('sight_check')->nullable();
            $table->timestamp('sight_check_date')->nullable();
            $table->text('sight_check_result')->nullable();
            $table->boolean('hearing_check')->nullable();
            $table->timestamp('hearing_check_date')->nullable();
            $table->text('hearing_check_result')->nullable();
            $table->text('epileptic')->nullable();
            $table->text('breathing_issues')->nullable();
            $table->text('teeth_issues')->nullable();
            $table->text('surgeries_done')->nullable();
            $table->text('exams_applied')->nullable();
            $table->text('problems_faced')->nullable();
            $table->text('training_needed')->nullable();
            $table->text('other')->nullable();
            $table->text('father_national_id')->nullable();
            $table->text('mother_national_id')->nullable();
            $table->foreignId('child_id')->constrained('children')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_files');
    }
};
