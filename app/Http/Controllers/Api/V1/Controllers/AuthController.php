<?php

namespace App\Http\Controllers\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $this->authService->validateFieldsLogin($request);
        $credentials = $request->only('email', 'password');
        $token = $this->authService->attemptLogin($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = $this->authService->getAuthUser();
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(Request $request)
    {
        $this->authService->validateFieldsRegister($request);
        $userRegistered = $this->authService->registerUser($request->all());

        return response()->json([
            'message' => 'User created successfully',
            'user' => $userRegistered
        ]);
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => $this->authService->getAuthUser(),
            'authorisation' => [
                'token' => $this->authService->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
