<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * TODO: add pagination, sorting and filtering
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        $users = $this->user->get();
        return new JsonResponse($users, JsonResponse::HTTP_OK);
    }

    /**
     * Get one user
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function one(int $userId): JsonResponse
    {
        $users = $this->user->findOrFail($userId);
        return new JsonResponse($users, JsonResponse::HTTP_OK);
    }

    /**
     * Get current user
     *
     * @return JsonResponse
     */
    public function current(): JsonResponse
    {
        return new JsonResponse(Auth::user(), JsonResponse::HTTP_OK);
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
        $data = $this->validate($request, [
            'username' => ['required', 'string', 'between:3,60', 'unique:users'],
            'email' => ['required', 'string', 'max:140', 'unique:users'],
        ]);

        $user = $this->user->create($data);

        // Todo: send email to oauth

        return new JsonResponse($user, JsonResponse::HTTP_CREATED, [
            'Location' => route('get_user', ['userId' => $user->id]),
        ]);
    }

    /**
     * Update user
     *
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, int $userId): JsonResponse
    {
        $data = $this->validate($request, [
            'username' => ['sometimes', 'string', 'between:3,60', 'unique:users'],
            'email' => ['sometimes', 'string', 'max:140', 'unique:users'],
        ]);

        $user = $this->user->findOrFail($userId);

        $user->update($data);

        return new JsonResponse($user, JsonResponse::HTTP_OK);
    }

    /**
     * Destroy user
     *
     * @param int $userId
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(int $userId): JsonResponse
    {
        $user = $this->user->findOrFail($userId);
        $user->delete();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
