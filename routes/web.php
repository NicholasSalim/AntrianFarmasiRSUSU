<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::view('/forgot-password', 'auth.forgot-password');
Route::post('/forgot-password/send-code', [ForgotPasswordController::class, 'sendCode']);
Route::post('/forgot-password/verify-code', [ForgotPasswordController::class, 'verifyCode']);
Route::post('/forgot-password/reset-password', [ForgotPasswordController::class, 'resetPassword']);

Route::get('/home', function () {
    return view('tickets.welcome');
})->name('home');

Route::get('/tickets/generate', [TicketController::class, 'ticketSelection'])->name('ticket.selection');
Route::get('/tickets/selfprint', [TicketController::class, 'selfprint'])->name('ticket.selfprint');
Route::get('/tickets/display', function () {
    return view('tickets.display');
}); 
Route::get('/tickets/queue', [TicketController::class, 'queue'])->name('tickets.queue');
Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/tickets/display', [TicketController::class, 'showQueue'])->name('tickets.display');

// Ticket routes
Route::post('/tickets/generate', [TicketController::class, 'generate'])->name('ticket.generate');
Route::post('/tickets/next', [TicketController::class, 'next'])->name('tickets.next');
Route::post('/tickets/next/{queueType}', [TicketController::class, 'nextByType'])->name('tickets.nextByType'); // New route
Route::post('/tickets/skip/{count}', [TicketController::class, 'skip'])->name('tickets.skip');
Route::post('/tickets/set-current/{id}', [TicketController::class, 'setCurrent'])->name('tickets.setCurrent');
Route::post('/tickets/clear', [TicketController::class, 'clear'])->name('tickets.clear');

Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.update');
Route::get('/tickets/queue-data', [TicketController::class, 'getQueueData'])->name('tickets.queueData');
