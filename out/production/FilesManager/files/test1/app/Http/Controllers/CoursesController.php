<?php

namespace App\Http\Controllers;

use App\Course;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoursesController extends Controller
{

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required'
        ]);
        $check = $request->has('id') ? [ 'id' => $request->id ] : [ 'name' => $request->name ];
        $course = Course::updateOrCreate($check, [
            'name' => $request->name,
            'description' => $request->description,
            'image' => $request->image,
            'category_id' => $request->category_id,
            'active' => $request->active,
            'user_id' => Auth::guard('api')->user()->id
        ]);

        return response()->json($course->id, 200);
    }

    public function getAll()
    {
        $courses = Course::all();
        return response()->json($courses, 200);
    }

    public function get($id)
    {
        $course = Course::find($id);
        $blocks = $course->blocks;
        return response()->json(compact('course', 'blocks'), 200);
    }

    public function delete($id)
    {
        Course::destroy($id);
        return response()->json(null, 200);
    }

    public function getAddFormInfo()
    {
        $types = Question::Types;
        return response()->json(compact('types'), 200);
    }

}
