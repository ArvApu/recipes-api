<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * @var User
     */
    private $user;

    /**
     * CommentController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get all comments of recipe
     *
     * TODO: add pagination, soritng and filtering
     *
     * @param int $userId
     * @param int $recipeId
     * @return JsonResponse
     */
    public function allForUserRecipe(int $userId, int $recipeId): JsonResponse
    {
        $comments = $this->user->findOrFail($userId)->recipes()->findOrFail($recipeId)->comments;
        return new JsonResponse($comments, JsonResponse::HTTP_OK);
    }

    /**
     * Get single comment
     *
     * @param int $userId
     * @param int $recipeId
     * @param int $commentId
     * @return JsonResponse
     */
    public function oneForUserRecipe(int $userId, int $recipeId, int $commentId): JsonResponse
    {
        $comment = $this->user->findOrFail($userId)->recipes()->findOrFail($recipeId)->comments()->findOrFail($commentId);
        return new JsonResponse($comment, JsonResponse::HTTP_OK);
    }

    /**
     * Create new comment for recipe
     *
     * @param Request $request
     * @param int $userId
     * @param int $recipeId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createForUserRecipe(Request $request, int $userId, int $recipeId): JsonResponse
    {
        $data = $this->validate($request, [
            'title' => ['required', 'string', 'between:3,40'],
            'comment' => ['required', 'string', 'max:200'],
        ]);

        // TODO: check if user who is posting is logged: Auth:user()-> === $userId

        $data['user_id'] = $userId;

        /** @var \App\Models\Recipe $recipe */
        $recipe = $this->user->findOrFail($userId)->recipes()->findOrFail($recipeId);
        /** @var \App\Models\Comment $comment **/
        $comment = $recipe->comments()->create($data);

        return new JsonResponse($comment, JsonResponse::HTTP_CREATED, [
            'Location' => route('get_comment', [
                'userId' => $userId,
                'recipeId' => $recipeId,
                'commentId' => $comment->id
            ]),
        ]);
    }

    /**
     * Update single comment
     *
     * @param Request $request
     * @param int $userId
     * @param int $recipeId
     * @param int $commentId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateForUserRecipe(Request $request, int $userId, int $recipeId, int $commentId): JsonResponse
    {
        // TODO: is owner
        // TODO: check if user who is posting is logged: Auth:user()-> === $userId

        $data = $this->validate($request, [
            'title' => ['sometimes', 'string', 'between:3,40'],
            'comment' => ['sometimes', 'string', 'max:200'],
        ]);

        /** @var \App\Models\Recipe $recipe */
        $recipe = $this->user->findOrFail($userId)->recipes()->findOrFail($recipeId);
        $comment = $recipe->comments()->findOrFail($commentId);

        $comment->update($data);

        return new JsonResponse($comment, JsonResponse::HTTP_OK);
    }

    /**
     * Delete a comment
     *
     * @param int $userId
     * @param int $recipeId
     * @param int $commentId
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroyForUserRecipe(int $userId, int $recipeId, int $commentId): JsonResponse
    {
        // TODO: is owner
        // TODO: check if user who is deleting is logged: Auth:user()-> === $userId

        /** @var \App\Models\Recipe $recipe */
        $recipe = $this->user->findOrFail($userId)->recipes()->findOrFail($recipeId);
        $recipe->comments()->findOrFail($commentId)->delete();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
