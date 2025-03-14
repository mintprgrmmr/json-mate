<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/tasks', [TaskController::class, 'index']); // Получить список всех задач
Route::post('/tasks', [TaskController::class, 'store']); // Создать новую задачу
Route::get('/tasks/{id}', [TaskController::class, 'show']); // Получить одну задачу
Route::put('/tasks/{id}', [TaskController::class, 'update']); // Обновить задачу
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']); // Удалить задачу
Route::post('/tasks/delete-multiple', [TaskController::class, 'destroyMultiple']);

