<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    protected $userRepository;

    public function __construct(AuthRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validateFieldsLogin($request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        return $request;
    }

    public function attemptLogin(array $credentials): ?string
    {
        $token = $this->userRepository->getToken($credentials);
        return $token;
    }

    public function getAuthUser()
    {
        $authUser = $this->userRepository->getAuthUser();
        return $authUser;
    }

    public function validateFieldsRegister($request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        return $request;
    }

    public function registerUser($userDataRegister)
    {
        $userRegistered = $this->userRepository->registerUser($userDataRegister);
        return $userRegistered;
    }

    public function logout()
    {
        $userLogout = $this->userRepository->logout();
        return $userLogout;
    }

    public function refresh()
    {
        $userRefresh = $this->userRepository->refresh();
        return $userRefresh;
    }
}
