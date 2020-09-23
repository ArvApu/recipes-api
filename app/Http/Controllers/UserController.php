<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// TODO: super admin only
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
     * Get all recipes
     *
     * TODO: add pagination
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $users = $this->user->get();
        return new JsonResponse($users, JsonResponse::HTTP_OK);
    }

    /**
     * Get one recipe
     *
     * @param int $id
     * @return JsonResponse
     */
    public function one(int $id): JsonResponse
    {
        $users = $this->user->findOrFail($id);
        return new JsonResponse($users, JsonResponse::HTTP_OK);
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
            'username' => ['required', 'string', 'between:3,60', 'unique:users'],
            'email' => ['required', 'string', 'max:140', 'unique:users'],
        ]);

        $user = $this->user->create($data);

        // Todo: send email to oauth

        return new JsonResponse($user, JsonResponse::HTTP_CREATED);
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
     * Destroy recipe
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
