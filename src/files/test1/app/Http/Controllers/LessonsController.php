<?php

namespace App\Http\Controllers;

use App\Lesson;
use App\Question;
use Illuminate\Http\Request;

class LessonsController extends Controller
{
    public function save(Request $request, $id)
    {
        $check = $request->has('id') ? [ 'id' => $request->id ] : [ 'name' => $request->name, 'block_id' => $id ];
        $lesson = Lesson::updateOrCreate($check, [
            'block_id' => $id,
            'active' => $request->active,
            'name' => $request->name,
            'resources' => json_encode($request->resourses),
            'sort' => Lesson::count() + 1, //Todo исправить!!!
            'text' => $request->text,
            'examples' => $request->examples,
            'video' => $request->video
        ]);

        $lesson->questions()->delete();
        foreach ($request->questions as $index => $q) {
            $arr = [
                'text' => $q['text'],
                'type' => array_has($q, 'type') ? $q['type'] : 'test',
                'answer' => $q['type'] === 'task' ? $q['answer'] : json_encode($q['answers']),
                'points' => $q['points'],
                'sort' => $index + 1,
                'lesson_id' => $lesson->id
            ];
            if (array_has($q, 'controller')) $arr = array_merge($arr, ['controller' => $q['controller']]);
            if (array_has($q, 'comment')) $arr = array_merge($arr, ['comment' => $q['comment']]);
            if (array_has($q, 'active')) $arr = array_merge($arr, ['active' => $q['active']]);
            Question::create($arr);
        }

        return response()->json($lesson->id, 200);
    }

    public function delete($id)
    {
        Lesson::destroy($id);
        return response()->json(null, 200);
    }

    public function get($id)
    {
        $lesson = Lesson::find($id);
        return response()->json($lesson, 200);
    }
}
