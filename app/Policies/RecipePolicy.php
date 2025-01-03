<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Recipe;

class RecipePolicy
{
    /**
     * Determine if the user can update a recipe.
     *
     * @param User $user
     * @param Recipe $recipe
     * @return bool
     */
    public function update(User $user, Recipe $recipe)
    {
        return $user->id === $recipe->user_id;
    }

    /**
     * Determine if the user can delete a recipe.
     *
     * @param User $user
     * @param Recipe $recipe
     * @return bool
     */
    public function delete(User $user, Recipe $recipe)
    {
        return $user->id === $recipe->user_id;
    }
}
