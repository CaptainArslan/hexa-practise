<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

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

Route::get('/', function () {
    return redirect('login');
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/save-device-token', [HomeController::class, 'saveToken'])->name('save.device.token');
Route::post('/send-push-notification', [HomeController::class, 'sendNotification'])->name('send.push.notification');

require __DIR__ . '/auth.php';
