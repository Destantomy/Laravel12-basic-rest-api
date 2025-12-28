<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Article;

use App\Http\Resources\UserResource;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (User::where('username', $data['username'])->exists())
        {
            return throw new HttpResponseException(response([
                'errors' => [
                    'username' => [
                        'username already exist'
                    ]
                ]
            ], 400));
        }
        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();
        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::where('username', $data['username'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => ['username or password wrong!']
                ]
            ], 401));
        }
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
            ],
            'token' => $token,
        ], 200);
    }

    public function getAllUsers()
    {
        return UserResource::collection(User::all());
    }

    public function getUser(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    public function updateUserById(UserUpdateRequest $request): UserResource // <- it means: promise will return as UserResource
    {
        $data = $request->validated();
        $user = Auth::user();
        if(isset($data['username'])) {
            $user->username = $data['username'];
        }
        if(isset($data['password'])) {
            $user->password = $data['password'];
        }
        $user->save();
        return new UserResource($user);
    }

    public function deleteUserById(Request $request): JsonResponse // <- it means: promise will return as JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();
        return response()->json([
            'data' => true
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'data' => true
        ], 200);
    }
}