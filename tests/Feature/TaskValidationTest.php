<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

/** @test Валидация создания задачи */
test('Создание задачи с некорректными данными возвращает ошибку', function () {
    $data = [
        'title'       => '', // Пустой заголовок
        'due_date'    => 'неверная дата', // Некорректный формат даты
        'priority'    => 'неизвестный', // Недопустимое значение приоритета
    ];

    $response = post('/tasks', $data, ['Accept' => 'application/json']);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['title', 'due_date', 'priority']);
});

/** @test Валидация массового удаления с несуществующими ID */
test('Попытка удалить несуществующие задачи возвращает 422', function () {
    $response = post('/tasks/delete-multiple', ['ids' => [999, 1000]], ['Accept' => 'application/json']);

    $response->assertStatus(422);
});
