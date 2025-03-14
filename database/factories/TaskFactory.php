<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Определяем шаблон данных для создания тестовых задач
     */
    public function definition(): array
    {
        return [
            'title'        => $this->faker->sentence(3),
            'description'  => $this->faker->text(50),
            'due_date'     => $this->faker->dateTimeBetween('now', '+1 year'),
            'priority'     => $this->faker->randomElement(['низкий', 'средний', 'высокий']),
            'category'     => $this->faker->randomElement(['работа', 'дом', 'личное']),
            'is_completed' => $this->faker->boolean,
        ];
    }
}

