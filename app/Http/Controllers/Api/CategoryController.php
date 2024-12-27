<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;

class CategoryController extends Controller
{
    public function index()
    {
        return new CategoryCollection(Category::all());
    }

    public function show(Category $category)
    {
        $category = $category->load('recipes');
        return new CategoryResource($category);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        return Category::create($request->all());
    }

    public function update(Request $request, Category $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $id->update($request->all());

        return $id;
    }

    public function destroy(Category $id)
    {
        $id->delete();

        return response()->json(null, 204);
    }
}
