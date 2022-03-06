<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','verified'])->group(function(){
    Route::apiResource('/user', UserController::class);
});
