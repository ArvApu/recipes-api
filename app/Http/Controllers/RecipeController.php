<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * @var User
     */
    private $user;

    /**
     * RecipeController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get all recipes
     *
     * TODO: add pagination, sorting and filtering
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function allForUser(int $userId): JsonResponse
    {
        $recipes = $this->user->findOrFail($userId)->recipes;
        return new JsonResponse($recipes, JsonResponse::HTTP_OK);
    }

    /**
     * Get one recipe
     *
     * @param int $userId
     * @param int $recipeId
     * @return JsonResponse
     */
    public function oneForUser(int $userId, int $recipeId): JsonResponse
    {
        $recipe = $this->user->findOrFail($userId)->recipes()->findOrFail($recipeId);
        return new JsonResponse($recipe, JsonResponse::HTTP_OK);
    }

    /**
     * Create recipe
     *
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createForUser(Request $request, int $userId): JsonResponse
    {
        $data = $this->validate($request, [
            'name' => ['required', 'string', 'between:3,50'],
            'description' => ['required', 'string', 'max:150'],
            'recipe' => ['required', 'string'],
        ]);

        /** @var \App\Models\Recipe $recipe */
        $recipe = $this->user->findOrFail($userId)->recipes()->create($data);

        return new JsonResponse($recipe, JsonResponse::HTTP_CREATED, [
            'Location' => route('get_recipe', ['userId' => $userId, 'recipeId' => $recipe->id]),
        ]);
    }

    /**
     * Update recipe
     *
     * @param Request $request
     * @param int $userId
     * @param int $recipeId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateForUser(Request $request, int $userId, int $recipeId): JsonResponse
    {
        $data = $this->validate($request, [
            'name' => ['sometimes', 'string', 'between:5,50'],
            'description' => ['sometimes', 'string', 'max:150'],
            'recipe' => ['sometimes', 'string'],
        ]);

        $recipe = $this->user->findOrFail($userId)->recipes()->findOrFail($recipeId);

        $recipe->update($data);

        return new JsonResponse($recipe, JsonResponse::HTTP_OK);
    }

    /**
     * Destroy recipe
     *
     * @param int $userId
     * @param int $recipeId
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroyForUser(int $userId, int $recipeId): JsonResponse
    {
        $recipe = $this->user->findOrFail($userId)->recipes()->findOrFail($recipeId);
        $recipe->delete();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
