<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get("/", function(){
    return view("auth.login");
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class,"index"])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class,"index"])->name('dashboard');
    Route::get('/emi-details', [DashboardController::class,"emiDetails"])->name('emailDetails');
    Route::post('/process-emi-data', [DashboardController::class,"processEmiData"])->name('processEmiData');
});


require __DIR__.'/auth.php';
