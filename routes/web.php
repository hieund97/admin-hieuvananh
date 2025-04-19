<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\WishController;

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

Route::get('/', [WishController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/login', [LoginController::class, 'index'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/dashboard', [WishController::class, 'index'])->middleware('auth')->name('list.wish');
Route::delete('/wish/{id}', [WishController::class, 'destroy'])->name('wish.destroy');
Route::patch('/wish/{id}/status', [WishController::class, 'updateStatus'])->name('wish.updateStatus');
Route::patch('/wish/accept-all', [WishController::class, 'updateAllStatuses'])->name('wish.acceptAll');
