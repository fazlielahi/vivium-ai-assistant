<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;

Route::get('/', fn() => view('chat'));
Route::post('/chat', [ChatController::class, 'send']);
Route::get('/admin', [AdminController::class, 'index']);