<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes(['register' => false]);

Route::group(['middleware' => ['auth', 'checkRole:admin']], function () {
    
    Route::get('/dashboard/theaters', [App\Http\Controllers\Dashboard\TheaterController::class, 'index'])->name('dashboard.theaters');
    Route::get('/dashboard/tickets', [App\Http\Controllers\Dashboard\TicketController::class, 'index'])->name('dashboard.tickets');
    
    //client
    Route::get('/dashboard/clients', [App\Http\Controllers\Dashboard\ClientController::class, 'index'])->name('dashboard.clients');
    Route::get('/dashboard/clients/create', [App\Http\Controllers\Dashboard\ClientController::class, 'create'])->name('dashboard.clients.create');
    Route::post('/dashboard/clients', [App\Http\Controllers\Dashboard\ClientController::class, 'store'])->name('dashboard.clients.store');
    Route::get('/dashboard/clients/{id}', [App\Http\Controllers\Dashboard\ClientController::class, 'edit'])->name('dashboard.clients.edit');
    Route::put('/dashboard/clients/{id}', [App\Http\Controllers\Dashboard\ClientController::class, 'update'])->name('dashboard.clients.update');
    Route::delete('/dashboard/clients/{id}', [App\Http\Controllers\Dashboard\ClientController::class, 'destroy'])->name('dashboard.clients.delete');
    
    //Users
    Route::get('/dashboard/users', [App\Http\Controllers\Dashboard\UserController::class, 'index'])->name('dashboard.users');
    Route::get('/dashboard/users/create', [App\Http\Controllers\Dashboard\UserController::class, 'create'])->name('dashboard.users.create');
    Route::get('/dashboard/users/{id}', [App\Http\Controllers\Dashboard\UserController::class, 'edit'])->name('dashboard.users.edit');
    Route::put('/dashboard/users/{id}', [App\Http\Controllers\Dashboard\UserController::class, 'update'])->name('dashboard.users.update');
    Route::post('/dashboard/users', [App\Http\Controllers\Dashboard\UserController::class, 'store'])->name('dashboard.files.store');
    Route::delete('/dashboard/users/{id}', [App\Http\Controllers\Dashboard\UserController::class, 'destroy'])->name('dashboard.users.delete');
}); 

Route::group(['middleware' => ['auth', 'checkRole:admin,user']], function(){
    Route::get('/dashboard', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('dashboard');
    
    //files
    Route::get('/dashboard/files', [App\Http\Controllers\Dashboard\FileController::class, 'index'])->name('dashboard.files');
    Route::get('/dashboard/downloads/{file}', [App\Http\Controllers\Dashboard\FileController::class, 'downloads'])->name('dashboard.files.downloads');
    Route::get('/dashboard/files/create', [App\Http\Controllers\Dashboard\FileController::class, 'create'])->name('dashboard.files.create');
    Route::post('/dashboard/files', [App\Http\Controllers\Dashboard\FileController::class, 'store'])->name('dashboard.files.store');
    Route::delete('/dashboard/files/{file}', [App\Http\Controllers\Dashboard\FileController::class, 'destroy'])->name('dashboard.files.delete');
});