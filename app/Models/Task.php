<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Task",
 *     title="Task",
 *     description="Модель задачи",
 *     required={"title"},
 *     @OA\Property(property="id", type="integer", example=1, description="ID задачи"),
 *     @OA\Property(property="title", type="string", example="Название задачи", description="Название задачи"),
 *     @OA\Property(property="description", type="string", example="Описание задачи", description="Описание задачи"),
 *     @OA\Property(property="due_date", type="string", format="date-time", nullable=true, example="2025-03-15T10:00:00Z", description="Срок выполнения"),
 *     @OA\Property(property="priority", type="string", enum={"низкий", "средний", "высокий"}, example="средний", description="Приоритет"),
 *     @OA\Property(property="category", type="string", nullable=true, example="Работа", description="Категория задачи"),
 *     @OA\Property(property="is_completed", type="boolean", example=false, description="Статус выполнения"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true, description="Дата создания"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true, description="Дата обновления")
 * )
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'priority',
        'category',
        'is_completed',
    ];
}
