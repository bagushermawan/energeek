<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TasksController extends Controller
{
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
}
