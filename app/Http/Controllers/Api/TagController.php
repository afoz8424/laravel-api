<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        return Tag::with('recipes')->get();
    }

    public function show(Tag $id)
    {
        return $id->load('recipes');
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
