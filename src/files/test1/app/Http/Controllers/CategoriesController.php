<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function add(Request $request)
    {
        $category = Category::create([
            'name' => $request->name
        ]);
        return response()->json($category, 200);
    }

    public function delete($id)
    {
        Category::destroy($id);
        return response()->json(null, 200);
    }

    public function getAll()
    {
        $categories = Category::all();
        return response()->json($categories, 200);
    }
}
