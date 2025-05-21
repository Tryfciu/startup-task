<?php

use App\Http\Controllers\UserController\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);
Route::post('users/{user}/send-notification', [UserController::class, 'sendNotification']);