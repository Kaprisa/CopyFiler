<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Task;
use App\TaskCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    public function add(Request $request, $category_id)
    {
        $check = $request->has('id') ? ['id' => $request->id] : ['name' => $request->name, 'category_id' => $category_id];
        $task = Task::updateOrCreate($check, [
            'category_id' => $category_id,
            'description' => $request->description,
            'name' => $request->name,
            'executor_id' => $request->executor_id,
            'creator_id' => Auth::guard('api')->id()
        ]);
        foreach ($request->tags as $tag) {
            $t = Tag::firstOrCreate(['name' => $tag]);
            $task->tags()->attach($t->id);
        }
        return response()->json($task, 200);
    }

    public function getCategories()
    {
        $categories = TaskCategory::all();
        return response()->json($categories, 200);
    }

    public function delete($id)
    {
        $task = Task::destroy($id);
        return null;
    }
}
