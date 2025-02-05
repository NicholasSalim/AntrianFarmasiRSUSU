<?php

use App\Http\Controllers\TicketController;
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
// Landing page
Route::get('/', function () {
    return view('tickets.welcome'); // Landing page
});

// Ticket generation page
Route::get('/tickets/generate', function () {
    return view('tickets.generate'); // Page with "Generate Ticket" button
});
Route::get('/tickets/display', function () {
    return view('tickets.display'); // Page with "Displayt" button
});

// Ticket routes
Route::post('/tickets/generate', [TicketController::class, 'generate'])->name('ticket.generate');
Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/tickets/display', [TicketController::class, 'display'])->name('tickets.display');
Route::post('/tickets/next', [TicketController::class, 'next'])->name('tickets.next');
Route::post('/tickets/skip/{count}', [TicketController::class, 'skip'])->name('tickets.skip');