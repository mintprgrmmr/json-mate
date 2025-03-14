<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;

/**
 * @OA\Info(
 *     title="JSON-Mate - Документация API",
 *     version="1.0.0",
 *     description="Документация API для управления задачами"
 * )
 *
 * @OA\Tag(
 *     name="Tasks",
 *     description="Операции с задачами"
 * )
 */
class TaskController extends Controller
{
    /**
     * Получить все задачи
     *
     * @OA\Get(
     *     path="/tasks",
     *     tags={"Tasks"},
     *     summary="Получить список всех задач",
     *     @OA\Parameter(name="search", in="query", description="Поиск задач по названию", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="is_completed", in="query", description="Фильтр по выполненным задачам (0 - не выполнено, 1 - выполнено)", required=false, @OA\Schema(type="boolean")),
     *     @OA\Parameter(name="priority", in="query", description="Фильтр по приоритету (низкий, средний, высокий)", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="category", in="query", description="Фильтр по категории", required=false, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Список задач", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Task")))
     * )
     */
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('is_completed')) {
            $query->where('is_completed', $request->boolean('is_completed'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('sort')) {
            $allowedSorts = ['due_date', 'created_at'];
            $sortField = in_array($request->sort, $allowedSorts) ? $request->sort : 'created_at';
            $query->orderBy($sortField, $request->get('direction', 'asc'));
        }

        return response()->json(TaskResource::collection($query->get()), 200);
    }

    /**
     * Создать новую задачу
     *
     * @OA\Post(
     *     path="/tasks",
     *     tags={"Tasks"},
     *     summary="Создать новую задачу",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(response=201, description="Созданная задача", @OA\JsonContent(ref="#/components/schemas/Task"))
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'due_date'     => 'nullable|date',
            'priority'     => 'nullable|string|in:низкий,средний,высокий',
            'category'     => 'nullable|string',
            'is_completed' => 'nullable|boolean',
        ]);

        $validated['is_completed'] = $validated['is_completed'] ?? false;

        $task = Task::create($validated);

        return response()->json(new TaskResource($task), 201);
    }

    /**
     * Получить одну задачу
     *
     * @OA\Get(
     *     path="/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Получить задачу по ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Данные задачи", @OA\JsonContent(ref="#/components/schemas/Task")),
     *     @OA\Response(response=404, description="Задача не найдена")
     * )
     */
    public function show($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json(new TaskResource($task), 200);
    }

    /**
     * Обновить задачу
     *
     * @OA\Put(
     *     path="/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Обновить задачу",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Task")),
     *     @OA\Response(response=200, description="Обновленная задача", @OA\JsonContent(ref="#/components/schemas/Task")),
     *     @OA\Response(response=404, description="Задача не найдена")
     * )
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $validated = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'description'  => 'nullable|string',
            'due_date'     => 'nullable|date',
            'priority'     => 'nullable|string|in:низкий,средний,высокий',
            'category'     => 'nullable|string',
            'is_completed' => 'nullable|boolean',
        ]);

        if ($request->has('is_completed')) {
            $validated['is_completed'] = $request->boolean('is_completed');
        }

        $task->update($validated);

        return response()->json(new TaskResource($task), 200);
    }

    /**
     * Удалить задачу
     *
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Удалить задачу по ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Задача удалена"),
     *     @OA\Response(response=404, description="Задача не найдена")
     * )
     */
    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully'], 200);
    }

    /**
     * Удалить несколько задач
     *
     * @OA\Post(
     *     path="/tasks/delete-multiple",
     *     tags={"Tasks"},
     *     summary="Удалить несколько задач",
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         @OA\Property(property="ids", type="array", @OA\Items(type="integer"))
     *     )),
     *     @OA\Response(response=200, description="Задачи удалены"),
     *     @OA\Response(response=422, description="Задачи не найдены")
     * )
     */
    public function destroyMultiple(Request $request)
    {
        $validated = $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|distinct',
        ]);

        $existingIds = Task::whereKey($validated['ids'])->pluck('id')->toArray();

        if (empty($existingIds)) {
            return response()->json(['message' => 'No tasks were found'], 422);
        }

        Task::whereKey($existingIds)->delete();

        return response()->json(['message' => 'Selected tasks deleted successfully'], 200);
    }
}
