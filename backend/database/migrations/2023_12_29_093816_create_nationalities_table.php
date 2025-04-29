<?php

use App\Models\Nationality;
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
        Schema::create('nationalities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar');
            $table->timestamps();
        });
        Nationality::query()->insert([
            [
                'name' => 'Bahraini',
                'name_ar' => 'بحريني'
            ],
            [
                'name' => 'Saudi',
                'name_ar' => 'سعودي'
            ],
            [
                'name' => 'Emirati',
                'name_ar' => 'إماراتي'
            ],
            [
                'name' => 'Qatari',
                'name_ar' => 'قطري'
            ],
            [
                'name' => 'Kuwaiti',
                'name_ar' => 'كويتي'
            ],
            [
                'name' => 'Omani',
                'name_ar' => 'عماني'
            ],
            [
                'name' => 'Yemeni',
                'name_ar' => 'يمني'
            ],
            [
                'name' => 'Iraqi',
                'name_ar' => 'عراقي'
            ],
            [
                'name' => 'Syrian',
                'name_ar' => 'سوري'
            ],
            [
                'name' => 'Jordanian',
                'name_ar' => 'أردني'
            ],
            [
                'name' => 'Lebanese',
                'name_ar' => 'لبناني'
            ],
            [
                'name' => 'Palestinian',
                'name_ar' => 'فلسطيني'
            ],
            [
                'name' => 'Egyptian',
                'name_ar' => 'مصري'
            ],
            [
                'name' => 'Sudanese',
                'name_ar' => 'سوداني'
            ],
            [
                'name' => 'Somali',
                'name_ar' => 'صومالي'
            ],
            [
                'name' => 'Libyan',
                'name_ar' => 'ليبي'
            ],
            [
                'name' => 'Tunisian',
                'name_ar' => 'تونسي'
            ],
            [
                'name' => 'Algerian',
                'name_ar' => 'جزائري'
            ],
            [
                'name' => 'Moroccan',
                'name_ar' => 'مغربي'
            ],
            [
                'name' => 'Mauritanian',
                'name_ar' => 'موريتاني'
            ],
            [
                'name' => 'Indian',
                'name_ar' => 'هندي'
            ],
            [
                'name' => 'Pakistani',
                'name_ar' => 'باكستاني'
            ],
            [
                'name' => 'Iranian',
                'name_ar' => 'إيراني'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nationalities');
    }
};
