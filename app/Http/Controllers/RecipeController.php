<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * @var Recipe
     */
    private $recipe;

    /**
     * RecipeController constructor.
     * @param Recipe $recipe
     */
    public function __construct(Recipe $recipe)
    {
        $this->recipe = $recipe;
    }

    /**
     * Get all recipes
     *
     * TODO: add pagination
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $recipes = $this->recipe->get();
        return new JsonResponse($recipes, JsonResponse::HTTP_OK);
    }

    /**
     * Get one recipe
     *
     * @param int $id
     * @return JsonResponse
     */
    public function one(int $id): JsonResponse
    {
        $recipes = $this->recipe->findOrFail($id);
        return new JsonResponse($recipes, JsonResponse::HTTP_OK);
    }

    /**
     * Get all comments of recipe
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function comments(Request $request, int $id): JsonResponse
    {
        $recipe = $this->recipe->findOrFail($id);
        return new JsonResponse($recipe->comments, JsonResponse::HTTP_OK);
    }

    /**
     * Create recipe
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $data = $this->validate($request, [
            'name' => ['required', 'string', 'between:3,50'],
            'description' => ['required', 'string', 'max:150'],
            'recipe' => ['required', 'string'],
        ]);

        $data['user_id'] = 1; // TODO: user should be logged in user: Auth:user()

        $recipe = $this->recipe->create($data);

        return new JsonResponse($recipe, JsonResponse::HTTP_CREATED);
    }

    /**
     * Create new comment for recipe
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function comment(int $id, Request $request): JsonResponse
    {
        $data = $this->validate($request, [
            'title' => ['required', 'string', 'between:3,40'],
            'comment' => ['required', 'string', 'max:200'],
        ]);

        $recipe = $this->recipe->findOrFail($id);

        $data['user_id'] = 1; // Todo: current logged in user

        $comment = $recipe->comments()->create($data);

        return new JsonResponse($comment, JsonResponse::HTTP_CREATED);
    }

    /**
     * Update recipe
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // TODO: check if user is owner of this comment

        $data = $this->validate($request, [
            'name' => ['sometimes', 'string', 'between:5,50'],
            'description' => ['sometimes', 'string', 'max:150'],
            'recipe' => ['sometimes', 'string'],
        ]);

        $recipe = $this->recipe->findOrFail($id);

        $recipe->update($data);

        return new JsonResponse($recipe, JsonResponse::HTTP_OK);
    }

    /**
     * Destroy recipe
     *
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(int $id): JsonResponse
    {
        // TODO: user should be logged in user: Auth:user()

        $recipe = $this->recipe->findOrFail($id);
        $recipe->delete();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
