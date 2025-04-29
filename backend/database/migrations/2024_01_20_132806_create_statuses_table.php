<?php

use App\Enums\Statuses;
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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar');
            $table->enum('type', array_column(Statuses::cases(),'value'));
            $table->timestamps();
        });
        \App\Models\Status::query()->insert([
                [
                    'name' => 'New Application',
                    'name_ar' => 'طلب جديد',
                    'type' => 'new'
                ],
                [
                    'name' => 'Pending Evaluation',
                    'name_ar' => 'يحال إلى التقييم المبدئي',
                    'type' => 'evaluation'
                ],
                [
                    'name' => 'On Waiting List',
                    'name_ar' => 'على قائمة الانتظار',
                    'type' => 'waiting'
                ],
                [
                    'name' => 'Accepted',
                    'name_ar' => 'مقبول',
                    'type' => 'accepted'
                ],
                [
                    'name' => 'Trial',
                    'name_ar' => 'فترة تجريبية',
                    'type' => 'trial'
                ],
                [
                    'name' => 'Pending Payment',
                    'name_ar' => 'بانتظار الدفع',
                    'type' => 'payment'
                ],
                [
                    'name' => 'On Break',
                    'name_ar' => 'إيقاف قيد',
                    'type' => 'break',
                ],
                [
                    'name' => 'Withdrawal',
                    'name_ar' => 'إلغاء قيد',
                    'type' => 'withdrawal'
                ],
                [
                    'name' => 'Rejected',
                    'name_ar' => 'مرفوض',
                    'type' => 'rejected'
                ],
                [
                    'name' => 'Graduated',
                    'name_ar' => 'متخرج',
                    'type' => 'graduated'
                ],
                [
                    'name' => 'Early Intervention',
                    'name_ar' => 'تدخل مبكر',
                    'type' => 'early_intervention'
                ],
                [
                    'name' => 'Habilitation',
                    'name_ar' => 'تأهيل',
                    'type' => 'habilitation'
                ],
                [
                    'name' => 'Expired',
                    'name_ar' => 'منتهي',
                    'type' => 'expired'
                ],
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
