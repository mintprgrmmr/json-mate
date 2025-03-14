<?php

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

/** @test Фильтрация по выполненным задачам */
test('Фильтрация по выполненным задачам работает', function () {
    Task::factory()->create(['is_completed' => true]);
    Task::factory()->create(['is_completed' => false]);

    $response = get('/tasks?is_completed=1', ['Accept' => 'application/json']);

    $response->assertStatus(200)
             ->assertJsonCount(1);
});

/** @test Фильтрация по приоритету */
test('Фильтрация по приоритету работает', function () {
    Task::factory()->create(['priority' => 'высокий']);
    Task::factory()->create(['priority' => 'средний']);

    $response = get('/tasks?priority=высокий', ['Accept' => 'application/json']);

    $response->assertStatus(200)
             ->assertJsonCount(1);
});

/** @test Сортировка по due_date */
test('Сортировка задач по due_date работает', function () {
    Task::factory()->create(['due_date' => '2025-03-15 10:00:00']);
    Task::factory()->create(['due_date' => '2025-03-10 10:00:00']);

    $response = get('/tasks?sort=due_date&direction=asc', ['Accept' => 'application/json']);

    $response->assertStatus(200);
});
