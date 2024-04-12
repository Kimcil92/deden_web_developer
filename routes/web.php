<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('/reset-password', [AuthController::class, 'resetpass'])->name('reset-password');
Route::get('/register', [AuthController::class, 'regis'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/rental', [DashboardController::class, 'rental'])->name('rental');
    Route::get('/car-management', [DashboardController::class, 'car'])->name('car-management');
    Route::get('/car-available', [DashboardController::class, 'avail'])->name('car-available');
    Route::get('/car-return', [DashboardController::class, 'showReturnForm'])->name('car-return');
    Route::post('/return/check', [DashboardController::class, 'checkPoliceNumber'])->name('return.check');
    Route::post('/return/pay', [DashboardController::class, 'payForReturn'])->name('return.pay');
    Route::post('/car-management', [DashboardController::class, 'store'])->name('car-management.store');
    Route::put('car-management/{id}', [DashboardController::class, 'update'])->name('car-management.update');
    Route::delete('car-management/{id}', [DashboardController::class, 'destroy'])->name('car-management.destroy');
    Route::post('/sewa/{id}', [DashboardController::class, 'sewa'])->name('sewa');


    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});
