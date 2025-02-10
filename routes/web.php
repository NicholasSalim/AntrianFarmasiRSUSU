<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


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
// Landing page





// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Requires Authentication)
Route::middleware(['auth'])->group(function () {
    
    Route::get('/home', function () {
        return view('tickets.welcome');
    })->name('home');

    Route::get('/tickets/generate', function () {
        return view('tickets.generate');
    });

    Route::get('/tickets/display', function () {
        return view('tickets.display');
    }); 

    Route::get('/tickets/queue', [TicketController::class, 'queue'])->name('tickets.queue');
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('ticket.show');
    Route::get('/tickets/display', [TicketController::class, 'showQueue'])->name('tickets.display');
    
});


// Ticket routes

Route::post('/tickets/generate', [TicketController::class, 'generate'])->name('ticket.generate');
Route::post('/tickets/next', [TicketController::class, 'next'])->name('tickets.next');
Route::post('/tickets/skip/{count}', [TicketController::class, 'skip'])->name('tickets.skip');
Route::post('/tickets/set-current/{id}', [TicketController::class, 'setCurrent'])->name('tickets.setCurrent');
Route::post('/tickets/clear', [TicketController::class, 'clear'])->name('tickets.clear');

