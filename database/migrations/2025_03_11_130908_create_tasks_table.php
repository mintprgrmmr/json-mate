<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // Уникальный ID задачи
            $table->string('title', 255); // Название задачи (обязательно)
            $table->text('description')->nullable(); // Описание (опционально)
            $table->dateTime('due_date')->nullable(); // Срок выполнения
            $table->boolean('is_completed')->default(0); // Статус выполнения (выполнена/не выполнена)
            $table->enum('priority', ['низкий', 'средний', 'высокий'])->default('средний'); // Приоритет
            $table->string('category')->nullable(); // Категория задачи (например, "Работа", "Дом", "Личное")
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
