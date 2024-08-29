<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Service\UserService;
use Exception;

class UserController extends Controller
{

    public function __construct(protected UserService $userService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = $this->userService->listAllUsers();
            return UserResource::collection($users);
        } catch (Exception $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $users = $this->userService->createUser($request->all());
            return new UserResource($users);
        } catch (Exception $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            $resource = $this->userService->findUserById($user->id);
            return new UserResource($resource);
        } catch (Exception $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], $th->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {

        try {
            $users = $this->userService->updateUser($request->all(), $user->id);
            return new UserResource($users);
        } catch (Exception $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user->id);
            return response()->json(['message' => 'User deleted succesfully'], 200);
        } catch (Exception $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }
}
