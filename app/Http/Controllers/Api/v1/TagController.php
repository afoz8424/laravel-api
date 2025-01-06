<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::with('recipes.category', 'recipes.tags', 'recipes.user')->get();
        return TagResource::collection($tags);
    }

    public function show(Tag $tag)
    {
        $tag = $tag->load('recipes.category', 'recipes.tags', 'recipes.user');
        return new TagResource($tag);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        return Tag::create($request->all());
    }

    public function update(Request $request, Tag $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $id->update($request->all());

        return $id;
    }

    public function destroy(Tag $id)
    {
        $id->delete();

        return response()->json(null, 204);
    }
}
