<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttachmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiagnoseController;
use App\Http\Controllers\Admin\NationalityController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Child\AdminChildController;
use App\Http\Controllers\Child\AdminDataFileController;
use App\Http\Controllers\Child\ChildDiagnoseController;
use App\Http\Controllers\Evaluation\CategoryController;
use App\Http\Controllers\Evaluation\EvaluationController;
use App\Http\Controllers\Evaluation\FormController;
use App\Http\Controllers\Evaluation\QuestionController;
use App\Http\Controllers\Guardian\GuardianController;
use App\Http\Controllers\Program\AdminChildProgramController;
use App\Http\Controllers\Program\ProgramController;
use App\Http\Controllers\Program\StatusChangeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API's for Admin use.
|
*/

Route::middleware(['auth:api', 'isAdmin'])->group(function () {
    // Permissions
    Route::controller(PermissionController::class)->group(function () {
        Route::get('permissions', 'index');
    })->middleware('hasPermission:permission_management');

    // Roles
    Route::controller(RoleController::class)->group(function () {
        Route::get('roles', 'index');
        Route::post('roles', 'store')->middleware('hasPermission:create-roles');
        Route::put('roles/{role}', 'update')->middleware('hasPermission:update-roles');
        Route::delete('roles/{role}', 'destroy')->middleware('hasPermission:delete-roles');
        Route::get('roles/{role}', 'show');
    })->middleware('hasPermission:role_management');

    // Admins
    Route::controller(AdminController::class)->group(function () {
        Route::get('admins', 'index');
        Route::post('admins', 'store')->middleware('hasPermission:create-admins');
        Route::put('admins/{admin}', 'update')->middleware('hasPermission:update-admins');
        Route::delete('admins/{admin}', 'destroy')->middleware('hasPermission:delete-admins');
        Route::get('admins/{admin}', 'show');
    })->middleware('hasPermission:admin_management');

    // Nationalities
    Route::controller(NationalityController::class)->group(function () {
        Route::get('nationalities', 'index');
        Route::post('nationalities', 'store')->middleware('hasPermission:create-nationalities');
        Route::put('nationalities/{nationality}', 'update')->middleware('hasPermission:update-nationalities');
        Route::delete('nationalities/{nationality}', 'destroy')->middleware('hasPermission:delete-nationalities');
        Route::get('nationalities/{nationality}', 'show');
    })->middleware('hasPermission:view-nationalities');

    // Statuses
    Route::controller(StatusController::class)->group(function () {
        Route::get('statuses', 'index');
        Route::post('statuses', 'store')->middleware('hasPermission:create-statuses');
        Route::put('statuses/{status}', 'update')->middleware('hasPermission:update-statuses');
        Route::delete('statuses/{status}', 'destroy')->middleware('hasPermission:delete-statuses');
        Route::get('statuses/{status}', 'show');
    })->middleware('hasPermission:view-statuses');

    // Programs
    Route::controller(ProgramController::class)->group(function () {
        Route::get('programs', 'index');
        Route::post('programs', 'store')->middleware('hasPermission:create-programs');
        Route::put('programs/{program}', 'update')->middleware('hasPermission:update-programs');
        Route::delete('programs/{program}', 'destroy')->middleware('hasPermission:delete-programs');
        Route::get('programs/{program}', 'show');
        Route::get('programs/{program}/children', 'children')->middleware('hasPermission:view-children');
    })->middleware('hasPermission:view-programs');

    // Categories
    Route::controller(CategoryController::class)->group(function () {
        Route::get('categories', 'index');
        Route::post('categories', 'store')->middleware('hasPermission:create-categories');
        Route::put('categories/{category}', 'update')->middleware('hasPermission:update-categories');
        Route::delete('categories/{category}', 'destroy')->middleware('hasPermission:delete-categories');
        Route::get('categories/{category}', 'show');
    })->middleware('hasPermission:view-categories');

    // Questions
    Route::controller(QuestionController::class)->group(function () {
        Route::get('questions', 'index');
        Route::post('questions', 'store')->middleware('hasPermission:create-questions');
        Route::put('questions/{question}', 'update')->middleware('hasPermission:update-questions');
        Route::delete('questions/{question}', 'destroy')->middleware('hasPermission:delete-questions');
        Route::get('questions/{question}', 'show');
    })->middleware('hasPermission:view-questions');

    // Forms
    Route::controller(FormController::class)->group(function () {
        Route::get('forms', 'index');
        Route::post('forms', 'store')->middleware('hasPermission:create-forms');
        Route::put('forms/{form}', 'update')->middleware('hasPermission:update-forms');
        Route::delete('forms/{form}', 'destroy')->middleware('hasPermission:delete-forms');
        Route::get('forms/{form}', 'show');
    })->middleware('hasPermission:view-forms');

    // Guardians
    Route::controller(GuardianController::class)->group(function () {
        Route::get('guardians', 'index');
        Route::post('guardians', 'store')->middleware('hasPermission:create-guardians');
        Route::put('guardians/{guardian}', 'update')->middleware('hasPermission:update-guardians');
        Route::delete('guardians/{guardian}', 'destroy')->middleware('hasPermission:delete-guardians');
        Route::get('guardians/{guardian}', 'show');
    })->middleware('hasPermission:view-guardians');

    // Children
    Route::controller(AdminChildController::class)->group(function () {
        Route::get('children', 'index');
        Route::post('children', 'store')->middleware('hasPermission:create-children');
        Route::put('children/{child}', 'update')->middleware('hasPermission:update-children');
        Route::delete('children/{child}', 'destroy')->middleware('hasPermission:delete-children');
        Route::get('children/{child}', 'show');
    })->middleware('hasPermission:view-children');

    // Diagnoses
    Route::controller(DiagnoseController::class)->group(function () {
        Route::get('diagnoses', 'index');
        Route::post('diagnoses', 'store')->middleware('hasPermission:create-diagnoses');
        Route::put('diagnoses/{diagnose}', 'update')->middleware('hasPermission:update-diagnoses');
        Route::delete('diagnoses/{diagnose}', 'destroy')->middleware('hasPermission:delete-diagnoses');
        Route::get('diagnoses/{diagnose}', 'show');
    })->middleware('hasPermission:view-diagnoses');

    // Child Data Files
    Route::controller(AdminDataFileController::class)->group(function () {
        Route::get('children/{child}/data-file', 'show');
        Route::post('children/{child}/data-file', 'store')->middleware('hasPermission:create-children-data-file');
        Route::put('children/{child}/data-file', 'update')->middleware('hasPermission:update-children-data-file');
        Route::delete('children/{child}/data-file', 'destroy')->middleware('hasPermission:delete-children-data-file');
    })->middleware('hasPermission:view-children-data-file');

    // Child Diagnoses
    Route::controller(ChildDiagnoseController::class)->group(function () {
        Route::get('children/{child}/diagnoses', 'index');
        Route::post('children/{child}/diagnoses', 'store')->middleware('hasPermission:create-children-diagnoses');
        Route::put('children/{child}/diagnoses/{childDiagnose}', 'update')->middleware('hasPermission:update-children-diagnoses');
        Route::delete('children/{child}/diagnoses/{childDiagnose}', 'destroy')->middleware('hasPermission:delete-children-diagnoses');
        Route::get('children/{child}/diagnoses/{childDiagnose}', 'show');
    })->middleware('hasPermission:view-children-diagnoses');

    // Child Programs
    Route::controller(AdminChildProgramController::class)->group(function () {
        Route::get('children/{child}/programs', 'index');
        Route::post('children/{child}/programs', 'store')->middleware('hasPermission:create-children-programs');
        Route::put('children/{child}/programs/{childProgram}', 'update')->middleware('hasPermission:update-children-programs');
        Route::delete('children/{child}/programs/{childProgram}', 'destroy')->middleware('hasPermission:delete-children-programs');
        Route::get('children/{child}/programs/{childProgram}', 'show');
    })->middleware('hasPermission:view-children-programs');

    // Child Program Evaluations
    Route::controller(EvaluationController::class)->group(function () {
        Route::post('childrenPrograms/{childProgram}/evaluation', 'store')->middleware('hasPermission:create-evaluations');
        Route::put('childrenPrograms/{childProgram}/evaluation', 'update')->middleware('hasPermission:update-evaluations');
        Route::delete('childrenPrograms/{childProgram}/evaluation', 'destroy')->middleware('hasPermission:delete-evaluations');
        Route::get('childrenPrograms/{childProgram}/evaluation', 'show');
        Route::post('childrenPrograms/{childProgram}/scheduleEvaluation', 'scheduleEvaluation')->middleware('hasPermission:schedule-evaluations');
    })->middleware('hasPermission:view-evaluations');

    // Child Program Status Changes
    Route::controller(StatusChangeController::class)->group(function () {
        Route::get('childrenPrograms/{childProgram}/history', 'index');
        Route::post('childrenPrograms/{childProgram}/history', 'store')->middleware('hasPermission:create-status-changes');
        Route::put('childrenPrograms/{childProgram}/history/{statusChange}', 'update')->middleware('hasPermission:update-status-changes');
        Route::delete('childrenPrograms/{childProgram}/history/{statusChange}', 'destroy')->middleware('hasPermission:delete-status-changes');
        Route::get('childrenPrograms/{childProgram}/history/{statusChange}', 'show');
    })->middleware('hasPermission:view-status-changes');

    // Attachments
    Route::controller(AttachmentController::class)->group(function () {
        Route::get('children/{child}/attachments', 'childAttachments');
        Route::get('children/{child}/plans', 'childPlans');
        Route::get('programs/{program}/attachments', 'programAttachments');
        Route::get('childrenPrograms/{childProgram}/attachments', 'childProgramAttachments');
        Route::get('attachments/{attachment}', 'show');
        Route::post('attachments', 'store')->middleware('hasPermission:create-attachments');
        Route::put('attachments/{attachment}', 'update')->middleware('hasPermission:update-attachments');
        Route::delete('attachments/{attachment}', 'destroy')->middleware('hasPermission:delete-attachments');
    })->middleware('hasPermission:view-attachments');

    // Dashboard
    Route::controller(DashboardController::class)->group(function () {
        Route::post('programsReport', 'programsReport');
    })->middleware('hasPermission:view-children-programs');
});
