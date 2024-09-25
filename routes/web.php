<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('admin')->group( function(){
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'adminProfile'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminController::class, 'adminProfileUpdate'])->name('admin.profile.update');

});

Route::get('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login');
Route::post('/admin/submit', [AdminController::class, 'adminSubmit'])->name('admin.submit');
Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');
Route::get('/admin/forget_password', [AdminController::class, 'adminForgetPassword'])->name('admin.forgetpassword');
Route::post('/admin/forget_password', [AdminController::class, 'adminSubmitEmail'])->name('admin.submit.email');
Route::get('/admin/reset-password/{token}/{email}', [AdminController::class, 'adminGetToken']);
Route::post('/admin/reset-password', [AdminController::class, 'adminResetPassword'])->name('admin.reset.password');