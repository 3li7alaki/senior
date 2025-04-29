<?php

use App\Enums\UsersLocales;
use App\Enums\UsersTypes;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('password');
            $table->string('relation')->nullable();
            $table->enum('type',array_column(UsersTypes::cases(),'value'));
            $table->enum('locale',['en','ar'])->default('ar')->nullable();
            $table->foreignId('role_id')->nullable()->constrained('roles');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
