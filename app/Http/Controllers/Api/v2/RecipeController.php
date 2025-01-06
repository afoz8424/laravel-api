<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Http\Resources\RecipeResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RecipeController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $recipes = Recipe::orderBy('id', 'desc')
            ->with('category', 'tags', 'user')
            ->paginate(10);
        return RecipeResource::collection($recipes);
    }

    public function show(Recipe $recipe)
    {
        $recipe = $recipe->load('category', 'tags', 'user');
        return new RecipeResource($recipe);
    }

    public function store(StoreRecipeRequest $request)
    {
        $recipe = $request->user()->recipes()->create($request->all());
        if ($tags = json_decode($request->tags, true)) {
            $recipe->tags()->attach($tags);
        }
        $recipe->image = $request->file('image')->store('recipes', 'public');
        $recipe->save();
        return response()->json(new RecipeResource($recipe), Response::HTTP_CREATED);
    }

    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        $this->authorize('update', $recipe);
        $recipe->update($request->all());
        if($tags = json_decode($request->tags, true)) {
            $recipe->tags()->sync($tags);
        }
        if ($request->hasFile('image')) {
            $recipe->image = $request->file('image')->store('recipes', 'public');
            $recipe->save();
        }
        return response()->json(new RecipeResource($recipe), Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $recipe = Recipe::find($id);
        $this->authorize('delete', $recipe);
        if (!$recipe) {
            return response()->json([
                'message' => 'No se pudo eliminar la receta',
                'error' => 'La receta no existe'
            ], Response::HTTP_NOT_FOUND);
        }

        $recipe->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
