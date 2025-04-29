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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_ar');
            $table->string('name');
            $table->string('group')->nullable();
            $table->string('group_ar')->nullable();
            $table->timestamps();
        });
        \App\Models\Permission::query()->insert([
            // Roles
            [
                'name' => 'permission-management',
                'title' => 'Manage Permissions',
                'title_ar' => 'إدارة الصلاحيات',
                'group' => 'Roles',
                'group_ar' => 'الأدوار'
            ],
            [
                'name' => 'role-management',
                'title' => 'Manage Roles',
                'title_ar' => 'إدارة الأدوار',
                'group' => 'Roles',
                'group_ar' => 'الأدوار'
            ],
            [
                'name' => 'create-roles',
                'title' => 'Create Roles',
                'title_ar' => 'إضافة الأدوار',
                'group' => 'Roles',
                'group_ar' => 'الأدوار'
            ],
            [
                'name' => 'update-roles',
                'title' => 'Update Roles',
                'title_ar' => 'تعديل الأدوار',
                'group' => 'Roles',
                'group_ar' => 'الأدوار'
            ],
            [
                'name' => 'delete-roles',
                'title' => 'Delete Roles',
                'title_ar' => 'حذف الأدوار',
                'group' => 'Roles',
                'group_ar' => 'الأدوار'
            ],
            // Admins
            [
                'name' => 'admin-management',
                'title' => 'Manage Admins',
                'title_ar' => 'إضافة مدراء النظام',
                'group' => 'Admins',
                'group_ar' => 'مدراء النظام'
            ],
            [
                'name' => 'create-admins',
                'title' => 'Create Admins',
                'title_ar' => 'إنشاء مدراء النظام',
                'group' => 'Admins',
                'group_ar' => 'مدراء النظام'
            ],
            [
                'name' => 'update-admins',
                'title' => 'Update Admins',
                'title_ar' => 'تعديل مدراء النظام',
                'group' => 'Admins',
                'group_ar' => 'مدراء النظام'
            ],
            [
                'name' => 'delete-admins',
                'title' => 'Delete Admins',
                'title_ar' => 'حذف مدراء النظام',
                'group' => 'Admins',
                'group_ar' => 'مدراء النظام'
            ],
            // Programs
            [
                'name' => 'view-nationalities',
                'title' => 'View Nationalities',
                'title_ar' => 'عرض الجنسيات',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            [
                'name' => 'create-nationalities',
                'title' => 'Create Nationalities',
                'title_ar' => 'إضافة الجنسيات',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            [
                'name' => 'update-nationalities',
                'title' => 'Update Nationalities',
                'title_ar' => 'تعديل الجنسيات',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            [
                'name' => 'delete-nationalities',
                'title' => 'Delete Nationalities',
                'title_ar' => 'حذف الجنسيات',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            [
                'name' => 'view-statuses',
                'title' => 'View Statuses',
                'title_ar' => 'عرض الحالات',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            [
                'name' => 'create-statuses',
                'title' => 'Create Statuses',
                'title_ar' => 'إضافة الحالات',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            [
                'name' => 'update-statuses',
                'title' => 'Update Statuses',
                'title_ar' => 'تعديل الحالات',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            [
                'name' => 'delete-statuses',
                'title' => 'Delete Statuses',
                'title_ar' => 'حذف الحالات',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            [
                'name' => 'view-diagnoses',
                'title' => 'View Diagnoses',
                'title_ar' => 'عرض التشخيصات',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            [
                'name' => 'create-diagnoses',
                'title' => 'Create Diagnoses',
                'title_ar' => 'إضافة التشخيصات',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            [
                'name' => 'update-diagnoses',
                'title' => 'Update Diagnoses',
                'title_ar' => 'تعديل التشخيصات',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            [
                'name' => 'delete-diagnoses',
                'title' => 'Delete Diagnoses',
                'title_ar' => 'حذف التشخيصات',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            [
                'name' => 'view-programs',
                'title' => 'View Programs',
                'title_ar' => 'عرض البرامج',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            [
                'name' => 'create-programs',
                'title' => 'Create Programs',
                'title_ar' => 'إضافة البرامج',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            [
                'name' => 'update-programs',
                'title' => 'Update Programs',
                'title_ar' => 'تعديل البرامج',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            [
                'name' => 'delete-programs',
                'title' => 'Delete Programs',
                'title_ar' => 'حذف البرامج',
                'group' => 'Programs',
                'group_ar' => 'البرامج'
            ],
            // Evaluation
            [
                'name' => 'view-questions',
                'title' => 'View Questions',
                'title_ar' => 'عرض الأسئلة',
                'group' => 'Evaluation',
                'group_ar' => 'التقييم'
            ],
            [
                'name' => 'create-questions',
                'title' => 'Create Questions',
                'title_ar' => 'إضافة الأسئلة',
                'group' => 'Evaluation',
                'group_ar' => 'التقييم'
            ],
            [
                'name' => 'update-questions',
                'title' => 'Update Questions',
                'title_ar' => 'تعديل الأسئلة',
                'group' => 'Evaluation',
                'group_ar' => 'التقييم'
            ],
            [
                'name' => 'delete-questions',
                'title' => 'Delete Questions',
                'title_ar' => 'حذف الأسئلة',
                'group' => 'Evaluation',
                'group_ar' => 'التقييم'
            ],
            [
                'name' => 'view-forms',
                'title' => 'View Forms',
                'title_ar' => 'عرض الاستمارات',
                'group' => 'Evaluation',
                'group_ar' => 'التقييم'
            ],
            [
                'name' => 'create-forms',
                'title' => 'Create Forms',
                'title_ar' => 'إضافة الاستمارات',
                'group' => 'Evaluation',
                'group_ar' => 'التقييم'
            ],
            [
                'name' => 'update-forms',
                'title' => 'Update Forms',
                'title_ar' => 'تعديل الاستمارات',
                'group' => 'Evaluation',
                'group_ar' => 'التقييم'
            ],
            [
                'name' => 'delete-forms',
                'title' => 'Delete Forms',
                'title_ar' => 'حذف الاستمارات',
                'group' => 'Evaluation',
                'group_ar' => 'التقييم'
            ],
            [
                'name' => 'view-evaluations',
                'title' => 'View Evaluations',
                'title_ar' => 'عرض التقييمات',
                'group' => 'Evaluation',
                'group_ar' => 'التقييم'
            ],
            [
                'name' => 'create-evaluations',
                'title' => 'Create Evaluations',
                'title_ar' => 'إضافة التقييمات',
                'group' => 'Evaluation',
                'group_ar' => 'التقييم'
            ],
            [
                'name' => 'update-evaluations',
                'title' => 'Update Evaluations',
                'title_ar' => 'تعديل التقييمات',
                'group' => 'Evaluation',
                'group_ar' => 'التقييم'
            ],
            [
                'name' => 'delete-evaluations',
                'title' => 'Delete Evaluations',
                'title_ar' => 'حذف التقييمات',
                'group' => 'Evaluation',
                'group_ar' => 'التقييم'
            ],
            [
                'name' => 'schedule-evaluations',
                'title' => 'Schedule Evaluations',
                'title_ar' => 'جدولة التقييمات',
                'group' => 'Evaluation',
                'group_ar' => 'التقييم'
            ],
            // Children
            [
                'name' => 'view-guardians',
                'title' => 'View Guardians',
                'title_ar' => 'عرض أولياء الأمور',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'create-guardians',
                'title' => 'Create Guardians',
                'title_ar' => 'إضافة أولياء الأمور',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'update-guardians',
                'title' => 'Update Guardians',
                'title_ar' => 'تعديل أولياء الأمور',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'delete-guardians',
                'title' => 'Delete Guardians',
                'title_ar' => 'حذف أولياء الأمور',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'view-children',
                'title' => 'View Children',
                'title_ar' => 'عرض الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'create-children',
                'title' => 'Create Children',
                'title_ar' => 'إضافة الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'update-children',
                'title' => 'Update Children',
                'title_ar' => 'تعديل الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'delete-children',
                'title' => 'Delete Children',
                'title_ar' => 'حذف الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'view-children-diagnoses',
                'title' => 'View Children Diagnoses',
                'title_ar' => 'عرض تشخيصات الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'create-children-diagnoses',
                'title' => 'Create Children Diagnoses',
                'title_ar' => 'إضافة تشخيصات الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'update-children-diagnoses',
                'title' => 'Update Children Diagnoses',
                'title_ar' => 'تعديل تشخيصات الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'delete-children-diagnoses',
                'title' => 'Delete Children Diagnoses',
                'title_ar' => 'حذف تشخيصات الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'view-children-data-file',
                'title' => 'View Children Data Files',
                'title_ar' => 'عرض ملفات الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'create-children-data-file',
                'title' => 'Create Children Data Files',
                'title_ar' => 'إضافة ملفات الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'update-children-data-file',
                'title' => 'Update Children Data Files',
                'title_ar' => 'تعديل ملفات الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'delete-children-data-file',
                'title' => 'Delete Children Data Files',
                'title_ar' => 'حذف ملفات الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'view-children-programs',
                'title' => 'View Children Programs',
                'title_ar' => 'عرض برامج الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'create-children-programs',
                'title' => 'Create Children Programs',
                'title_ar' => 'إضافة برامج الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'update-children-programs',
                'title' => 'Update Children Programs',
                'title_ar' => 'تعديل برامج الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'delete-children-programs',
                'title' => 'Delete Children Programs',
                'title_ar' => 'حذف برامج الأطفال',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'view-status-changes',
                'title' => 'View Program Status Change Log',
                'title_ar' => 'عرض سجل تغيير حالات البرامج',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'create-status-changes',
                'title' => 'Create Program Status Change',
                'title_ar' => 'إضافة سجل تغيير حالة البرامج',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'update-status-changes',
                'title' => 'Update Program Status Change',
                'title_ar' => 'تعديل سجل تغيير حالة البرامج',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'delete-status-changes',
                'title' => 'Delete Program Status Change',
                'title_ar' => 'حذف سجل تغيير حالة البرامج',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            // Other
            [
                'name' => 'view-attachments',
                'title' => 'View Attachments',
                'title_ar' => 'عرض المرفقات',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'create-attachments',
                'title' => 'Create Attachments',
                'title_ar' => 'إضافة المرفقات',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'update-attachments',
                'title' => 'Update Attachments',
                'title_ar' => 'تعديل المرفقات',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ],
            [
                'name' => 'delete-attachments',
                'title' => 'Delete Attachments',
                'title_ar' => 'حذف المرفقات',
                'group' => 'Children',
                'group_ar' => 'الأطفال'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
