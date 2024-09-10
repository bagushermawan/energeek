<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Inertia\Inertia;

class TasksController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return Inertia::render('Console/Task/View', ['tasks' => $tasks]);
    }

    public function listtasks()
    {
        $tasks = Task::with(['user', 'category'])->orderBy('user_id', 'asc')->get();
        return response()->json(['data' => $tasks], 200);
    }

    public function store(Request $request)
    {
        $tasksData = $request->all();

        foreach ($tasksData as $taskData) {
            $task = new Task();
            $task->description = $taskData['description'];
            $task->user_id = $taskData['user_id'];
            $task->category_id = $taskData['category_id'];
            $task->created_by = $taskData['created_by'];
            $task->save();
        }

        return response()->json(['message' => 'Tasks created successfully']);
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return response()->json(['data' => $task]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'description' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            'updated_by' => 'required|integer|exists:users,id'
        ]);

        $task = Task::find($id);

        if ($task) {
            $task->description = $validatedData['description'];
            $task->category_id = $validatedData['category_id'];
            $task->updated_by = $validatedData['updated_by'];
            $task->save();

            return response()->json(['message' => 'Task updated successfully']);
        } else {
            return response()->json(['message' => 'Task not found'], 404);
        }
    }


    public function destroy($id, Request $request)
    {
        $task = Task::findOrFail($id);

        $task->deleted_by = $request->user()->id;
        $task->save();

        $task->delete();

        return response()->json(['message' => 'Task soft deleted successfully']);
    }
}
