<?php

use App\Http\Controllers\Admin\AttachmentController;
use App\Http\Controllers\Admin\DiagnoseController;
use App\Http\Controllers\Admin\NationalityController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Child\ChildDiagnoseController;
use App\Http\Controllers\Child\GuardianChildController;
use App\Http\Controllers\Child\GuardianDataFileController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Program\GuardianChildProgramController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth routes
Route::post('login', [Controller::class, 'login']);
Route::post('register', [Controller::class, 'register']);
Route::post('forgot-password', [Controller::class, 'forgotPassword']);
Route::post('forgot-password-reset', [Controller::class, 'forgotPasswordReset']);

Route::get('unAuth', function () {
    return response()->json([
        'Session' => 'Session expired , Please Login',
        'Message' => 'Unauthorized',
    ], 403);
})->name('unAuth');

Route::middleware('auth:api')->group(function (){
    // Auth routes
    Route::get('me', [Controller::class, 'me']);
    Route::get('logout', [Controller::class, 'logout']);
    Route::put('updateInfo', [Controller::class, 'updateInfo']);

    // Notifications
    Route::controller(NotificationsController::class)->group(function () {
        Route::get('notifications', 'index');
        Route::get('new-notifications', 'newNotifications');
        Route::put('notifications/{notification}/mark-as-seen', 'markAsSeen');
        Route::put('notifications/{notification}/mark-as-unseen', 'markAsUnseen');
        Route::put('notifications/mark-all-as-seen', 'markAllAsSeen');
        Route::delete('notifications/{notification}', 'destroy');
        Route::get('notifications/{notification}', 'show');
    });

    // Guardian Routes
    Route::middleware('isGuardian')->group(function () {
        // Children
        Route::controller(GuardianChildController::class)->group(function () {
            Route::get('children', 'index');
            Route::post('children', 'store');
            Route::put('children/{child}', 'update');
            Route::get('children/{child}', 'show');
        });

        // Children Diagnoses
        Route::controller(ChildDiagnoseController::class)->group(function () {
            Route::get('children/{child}/diagnoses', 'index');
            Route::post('children/{child}/diagnoses', 'store');
            Route::put('children/{child}/diagnoses/{childDiagnose}', 'update');
            Route::delete('children/{child}/diagnoses/{childDiagnose}', 'destroy');
            Route::get('children/{child}/diagnoses/{childDiagnose}', 'show');
        });

        // Children Data Files
        Route::controller(GuardianDataFileController::class)->group(function () {
            Route::get('children/{child}/data-file', 'show');
            Route::post('children/{child}/data-file', 'store');
            Route::put('children/{child}/data-file', 'update');
        });

        // Children Programs
        Route::controller(GuardianChildProgramController::class)->group(function () {
            Route::get('children/{child}/programs', 'index');
            Route::get('children/{child}/applicable-programs', 'applicablePrograms');
            Route::post('children/{child}/programs/{program}', 'store');
            Route::put('children/{child}/programs/{childProgram}', 'update');
            Route::get('children/{child}/programs/{childProgram}', 'show');
        });

        // Attachments
        Route::controller(AttachmentController::class)->group(function () {
            Route::get('children/{child}/attachments', 'childAttachments');
            Route::get('children/{child}/plans', 'childPlans');
            Route::get('programs/{program}/attachments', 'programAttachments');
            Route::get('childrenPrograms/{childProgram}/attachments', 'childProgramAttachments');
            Route::get('attachments/{attachment}', 'show');
            Route::post('attachments', 'store');
            Route::put('attachments/{attachment}', 'update');
            Route::delete('attachments/{attachment}', 'destroy');
        });

        // Nationalities
        Route::get('nationalities', [NationalityController::class, 'index']);

        // Diagnoses
        Route::get('diagnoses', [DiagnoseController::class, 'index']);
    });
});

