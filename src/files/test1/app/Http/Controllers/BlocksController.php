<?php

namespace App\Http\Controllers;

use App\Block;
use Illuminate\Http\Request;

class BlocksController extends Controller
{
    public function save(Request $request, $id)
    {
        $check = $request->has('id') ? [ 'id' => $request->id ] : [ 'name' => $request->name, 'course_id' => $id ];
        $block = Block::updateOrCreate($check, [
            'course_id' => $id,
            'name' => $request->name,
            'active' => $request->active,
            'sort' => $request->sort
        ]);

        return response()->json($block->id, 200);
    }

    public function get($id)
    {
        $block = Block::with('lessons')->find($id);
        return response()->json($block, 200);
    }

    public function delete($id)
    {
        Block::destroy($id);
        return response()->json(null, 200);
    }
}
