<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * @var Comment
     */
    private $comment;

    /**
     * RecipeController constructor.
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get all comments of recipe
     *
     * Todo: add pagination
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $comments = $this->comment->get();
        return new JsonResponse($comments, JsonResponse::HTTP_OK);
    }

    /**
     * Get single comment
     *
     * @param int $id
     * @return JsonResponse
     */
    public function one(int $id): JsonResponse
    {
        $comment = $this->comment->findOrFail($id);
        return new JsonResponse($comment, JsonResponse::HTTP_OK);
    }

    /**
     * Create new comment for recipe
     *
     * @param int $recipeId
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $data = $this->validate($request, [
            'title' => ['required', 'string', 'between:3,40'],
            'comment' => ['required', 'string', 'max:200'],
            'recipe_id' => ['required', 'integer', 'exists:recipes,id'],
        ]);

        $data['user_id'] = 1; // Todo: current logged in user

        $comment = $this->comment->create($data);

        return new JsonResponse($comment, JsonResponse::HTTP_CREATED);
    }

    /**
     * Update single comment
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $this->validate($request, [
            'title' => ['sometimes', 'string', 'between:3,40'],
            'comment' => ['sometimes', 'string', 'max:200'],
        ]);

        $comment = $this->comment->findOrFail($id);
        $comment->update($data);

        return new JsonResponse($comment, JsonResponse::HTTP_OK);
    }

    /**
     * Delete a comment
     *
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(int $id): JsonResponse
    {
        $comment = $this->comment->findOrFail($id);
        $comment->delete();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
