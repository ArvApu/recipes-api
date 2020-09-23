<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
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
     * Get all users
     *
     * TODO: add pagination
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        // TODO: super admin only
        $users = $this->user->get();
        return new JsonResponse($users, JsonResponse::HTTP_OK);
    }

    /**
     * Get one user
     *
     * @param int $id
     * @return JsonResponse
     */
    public function one(int $id): JsonResponse
    {
        // TODO: super admin only
        $users = $this->user->findOrFail($id);
        return new JsonResponse($users, JsonResponse::HTTP_OK);
    }






    /**
     * Get one user's recipes
     *
     * @param int $id
     * @return JsonResponse
     */
    public function recipes(int $id): JsonResponse
    {
        $user = $this->user->findOrFail($id);
        $recipes = $user->recipes;
        return new JsonResponse($recipes, JsonResponse::HTTP_OK);
    }

    /**
     * Get one user's comments
     *
     * @param int $id
     * @return JsonResponse
     */
    public function comments(int $id): JsonResponse
    {
        $user = $this->user->findOrFail($id);
        $comments = $user->comments;
        return new JsonResponse($comments, JsonResponse::HTTP_OK);
    }

    /**
     * Create user
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        // TODO: super admin only
        $data = $this->validate($request, [
            'username' => ['required', 'string', 'between:3,60', 'unique:users'],
            'email' => ['required', 'string', 'max:140', 'unique:users'],
        ]);

        $user = $this->user->create($data);

        // Todo: send email to oauth

        return new JsonResponse($user, JsonResponse::HTTP_CREATED);
    }

    /**
     * Update user
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // Todo: super admin and user itself only
        $data = $this->validate($request, [
            'username' => ['sometimes', 'string', 'between:3,60', 'unique:users'],
            'email' => ['sometimes', 'string', 'max:140', 'unique:users'],
        ]);

        $user = $this->user->findOrFail($id);

        $user->update($data);

        return new JsonResponse($user, JsonResponse::HTTP_OK);
    }

    /**
     * Destroy user
     *
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(int $id): JsonResponse
    {
        // Todo: super admin and user itself only
        $user = $this->user->findOrFail($id);
        $user->delete();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
