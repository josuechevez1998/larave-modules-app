<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\BlogController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('blogs', \Modules\Blog\Http\Controllers\Api\BlogController::class);
});

// CRUD: Blog (make:crud --module=Blog)
