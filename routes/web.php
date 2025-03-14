<?php

use Illuminate\Support\Facades\Route;
use L5Swagger\Http\Controllers\SwaggerController;

Route::get('/api/docs', [SwaggerController::class, 'api'])->name('swagger.api');
Route::get('/docs', [SwaggerController::class, 'docs'])->name('swagger.docs');

Route::get('/debug', function () {
    try {
        app()->make(\L5Swagger\Http\Controllers\SwaggerController::class)->docs(request());
    } catch (\Throwable $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});

// Маршрут для доступа к API-докам
Route::get('/storage/api-docs/{file}', function ($file) {
    $path = storage_path("api-docs/{$file}");

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
});


