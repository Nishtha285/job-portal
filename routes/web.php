<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;
use App\Http\Middleware\logedInUser;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
Route::get('/jobs/detail/{jobID}', [JobsController::class, 'detail'])->name('jobDetail');
Route::post('/apply-job', [JobsController::class, 'applyJob'])->name('applyJob');
Route::group(['prefix' => 'account'], function(){
    Route::middleware('logedIn')->group(function () {
        Route::get('/login', [AccountController::class, 'login'])->name('account.login');
        Route::get('/register', [AccountController::class, 'registration'])->name('account.registration');
        Route::post('/process-register', [AccountController::class, 'processRegistration'])->name('account.processRegistration');
        Route::post('/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');
    });
    Route::middleware('authenticate')->group(function () {
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::put('/update-profile', [AccountController::class, 'profileUpdate'])->name('account.profileUpdate');
        Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::post('/update-profile-pic', [AccountController::class, 'updateProfilePic'])->name('account.updateProfilePic');
        Route::get('/create-job', [AccountController::class, 'createJob'])->name('account.createJob');
        Route::post('/save-job', [AccountController::class, 'saveJob'])->name('account.saveJob');
        Route::get('/my-jobs', [AccountController::class, 'myJob'])->name('account.myJobs');
        Route::get('/my-jobs/edit/{jobID}', [AccountController::class, 'editJob'])->name('account.editJob');
        Route::put('/update-job/{jobID}', [AccountController::class, 'updateJob'])->name('account.updateJob');
        Route::get('/view-job', [AccountController::class, 'createJob'])->name('account.viewJob');
        Route::post('/delete-job', [AccountController::class, 'deleteJob'])->name('account.deleteJob');
        Route::get('/my-job-applications', [AccountController::class, 'myJobApplication'])->name('account.myJobApplication');
        Route::post('/remove-job-application', [AccountController::class, 'removeJobs'])->name('account.removeJobs');
    });
});