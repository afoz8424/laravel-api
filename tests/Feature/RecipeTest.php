<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;

class RecipeTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function test_index(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $recipes = Recipe::factory(2)->create();
        $response = $this->getJson('api/recipes');
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'id', 
                        'type', 
                        'attributes' => ['category', 'author', 'title', 'description', 'ingredients', 'instructions', 'image', 'tags']
                    ]
                ]
            ]);
    }

    public function test_show(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $recipe = Recipe::factory()->create();
        $response = $this->getJson("api/recipes/{$recipe->id}");
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id', 
                    'type', 
                    'attributes' => ['category', 'author', 'title', 'description', 'ingredients', 'instructions', 'image', 'tags']
                ]
            ]);
    }

    public function test_store(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        $data = [
            'category_id' => $category->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'ingredients' => $this->faker->text,
            'instructions' => $this->faker->text,
            'tags' => $tag->id,
            'image' => UploadedFile::fake()->image('recipe.png')
        ];

        $response = $this->postJson('api/recipes', $data);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_update(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $recipe = Recipe::factory()->create();
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        $data = [
            'category_id' => $category->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'ingredients' => $this->faker->text,
            'instructions' => $this->faker->text,
            'tags' => $tag->id,
            'image' => UploadedFile::fake()->image('recipe.png')
        ];

        $response = $this->putJson("api/recipes/{$recipe->id}", $data);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_delete(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $recipe = Recipe::factory()->create();
        $response = $this->deleteJson("api/recipes/{$recipe->id}");
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
