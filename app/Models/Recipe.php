<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    /** @use HasFactory<\Database\Factories\RecipeFactory> */
    use HasFactory;

    public function tags ()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function category ()
    {
        return $this->belongsTo(Category::class);
    }

    public function user ()
    {
        return $this->belongsTo(User::class);
    }
}