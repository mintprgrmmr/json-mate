<?php

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{get, post, put, delete};

uses(RefreshDatabase::class); // Очищает БД перед каждым тестом

/** @test Создание задачи */
test('Создание задачи работает', function () {
    $data = [
        'title'       => 'Тестовая задача',
        'description' => 'Описание тестовой задачи',
        'due_date'    => '2025-03-15 10:00:00',
        'priority'    => 'средний',
        'category'    => 'работа',
        'is_completed' => false,
    ];

    $response = post('/tasks', $data, ['Accept' => 'application/json']);

    $response->assertStatus(201)
             ->assertJsonFragment(['title' => 'Тестовая задача']);

    $this->assertDatabaseHas('tasks', ['title' => 'Тестовая задача']);
});

/** @test Получение списка задач */
test('Список задач успешно возвращается', function () {
    Task::factory()->count(3)->create();

    $response = get('/tasks', ['Accept' => 'application/json']);

    $response->assertStatus(200)
             ->assertJsonCount(3);
});

/** @test Получение конкретной задачи */
test('Можно получить задачу по ID', function () {
    $task = Task::factory()->create(['title' => 'Задача на тест']);

    $response = get("/tasks/{$task->id}", ['Accept' => 'application/json']);

    $response->assertStatus(200)
             ->assertJsonFragment(['title' => 'Задача на тест']);
});

/** @test Обновление задачи */
test('Можно обновить задачу', function () {
    $task = Task::factory()->create();

    $updatedData = ['title' => 'Обновленное название'];

    $response = put("/tasks/{$task->id}", $updatedData, ['Accept' => 'application/json']);

    $response->assertStatus(200)
             ->assertJsonFragment(['title' => 'Обновленное название']);

    $this->assertDatabaseHas('tasks', ['title' => 'Обновленное название']);
});

/** @test Удаление задачи */
test('Можно удалить задачу', function () {
    $task = Task::factory()->create();

    $response = delete("/tasks/{$task->id}", [], ['Accept' => 'application/json']);

    $response->assertStatus(200)
             ->assertJsonFragment(['message' => 'Task deleted successfully']);

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});
