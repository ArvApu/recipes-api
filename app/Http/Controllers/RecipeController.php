<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
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
     * @param Recipe $recipe
     * @return JsonResponse
     */
    public function all(Recipe $recipe): JsonResponse
    {
        $recipes = $recipe->get();
        return new JsonResponse($recipes, JsonResponse::HTTP_OK);
    }

    /**
     * Get all recipes for certain user
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
        $recipe = $this->user->findOrFail($userId)->recipes()->with('author')->findOrFail($recipeId);
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
        $this->validate($request, [
            'name' => ['required', 'string', 'between:3,50'],
            'description' => ['required', 'string', 'max:500'],
            'recipe' => ['required', 'string', 'max:1000'],
            'duration' => ['required', 'integer', 'min:10'],
            'picture' => ['required', 'image', 'dimensions:min_width=400,min_height=300', 'max:10000']
        ]);

        $data = $request->except('picture');
        $data['user_id'] = $userId;

        /** @var \App\Models\Recipe $recipe */
        $recipe = $this->user->findOrFail($userId)->recipes()->create($data);

        $recipe->uploadImage($request->file('picture'));

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
            'name' => ['sometimes', 'required', 'string', 'between:5,50'],
            'description' => ['sometimes', 'required', 'string', 'max:500'],
            'recipe' => ['sometimes', 'required', 'string', 'max:1000'],
            'duration' => ['sometimes', 'integer', 'min:10'],
            'picture' => ['sometimes', 'image', 'dimensions:min_width=400,min_height=300', 'max:10000']
        ]);

        /** @var \App\Models\Recipe $recipe */
        $recipe = $this->user->findOrFail($userId)->recipes()->findOrFail($recipeId);

        if(isset($data['picture'])) {
            $recipe->uploadImage($data['picture']);
            unset($data['picture']);
        }

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
