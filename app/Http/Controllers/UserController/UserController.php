<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController\Requests\CreateUserRequest;
use App\Http\Controllers\UserController\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repository\UserRepository;
use App\Service\SendNotificationService\SendNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private SendNotificationService $sendNotificationService
    ) {}

    public function index(Request $request): JsonResponse
    {
        return UserResource::collection($this->userRepository->getAllUsers())->response($request);
    }

    public function show(Request $request, User $user): JsonResponse
    {
        return UserResource::make($user)->response($request);
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        $user = $this->userRepository->createUser(...$request->all());

        return UserResource::make($user)->response($request);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user = $this->userRepository->updateUser($user, ...$request->all());

        return UserResource::make($user)->response($request);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->userRepository->deleteUser($user);

        return response()->json();
    }

    public function sendNotification(User $user): JsonResponse
    {
        $this->sendNotificationService->sendNotification($user);
        return response()->json();
    }
}