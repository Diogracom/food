<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
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

//Admin Routes

Route::middleware('admin')->group( function(){
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'adminProfile'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminController::class, 'adminProfileUpdate'])->name('admin.profile.update');
    Route::get('/admin/change/password', [AdminController::class, 'adminChangePassword'])->name('admin.change.password');
    Route::post('/admin/change/password', [AdminController::class, 'adminChangePasswordNew'])->name('admin.change.password.new');

});

Route::get('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login');
Route::post('/admin/submit', [AdminController::class, 'adminSubmit'])->name('admin.submit');
Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');
Route::get('/admin/forget_password', [AdminController::class, 'adminForgetPassword'])->name('admin.forgetpassword');
Route::post('/admin/forget_password', [AdminController::class, 'adminSubmitEmail'])->name('admin.submit.email');
Route::get('/admin/reset-password/{token}/{email}', [AdminController::class, 'adminGetToken']);
Route::post('/admin/reset-password', [AdminController::class, 'adminResetPassword'])->name('admin.reset.password');


//client Routes
Route::middleware('client')->group( function(){
    Route::get('/client/dashboard', [ClientController::class, 'clientDashboard'])->name('client.dashboard');
    Route::get('/client/profile', [ClientController::class, 'clientProfile'])->name('client.profile');
    Route::post('/client/profile/update', [ClientController::class, 'clientProfileUpdate'])->name('client.profile.update');
    Route::get('/client/change/password', [ClientController::class, 'clientChangePassword'])->name('client.change.password');
    Route::post('/client/change/password', [ClientController::class, 'clientChangePasswordNew'])->name('client.change.password.new');

});

Route::get('/client/login', [ClientController::class, 'clientLogin'])->name('client.login');
Route::get('/client/register', [ClientController::class, 'clientRegister'])->name('client.register');
Route::post('/client/register', [ClientController::class, 'clientRegisterSubmit'])->name('client.register.submit');
Route::post('/client/submit', [ClientController::class, 'clientSubmit'])->name('client.submit');
Route::get('/client/logout', [ClientController::class, 'clientLogout'])->name('client.logout');
Route::get('/client/forget_password', [ClientController::class, 'clientForgetPassword'])->name('client.forgetpassword');
Route::post('/client/forget_password', [ClientController::class, 'clientSubmitEmail'])->name('client.submit.email');
Route::get('/client/reset-password/{token}/{email}', [ClientController::class, 'clientGetToken']);
Route::post('/client/reset-password', [ClientController::class, 'clientResetPassword'])->name('client.reset.password');