<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipeController extends Controller
{
    public function index()
    {
        return Recipe::with('category', 'tags', 'user')->get();
    }

    public function show(Recipe $id)
    {
        return $id->load('category', 'tags', 'user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'user_id' => 'required',
            'description' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
        ]);

        return Recipe::create($request->all());
    }

    public function update(Request $request, Recipe $id)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'user_id' => 'required',
            'description' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
        ]);

        $id->update($request->all());

        return $id;
    }

    public function destroy(Recipe $id)
    {
        $id->delete();

        return response()->json(null, 204);
    }
}
