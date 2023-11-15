<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
  public function getToken($credentials) {
    $token = Auth::attempt($credentials);
    return $token;
  }

  public function getAuthUser() {
    $authUser = Auth::user();
    return $authUser;
  }

  public function registerUser($userDataRegister) {
    $userRegistered = User::create([
        'name' => $userDataRegister['name'],
        'email' => $userDataRegister['email'],
        'password' => Hash::make($userDataRegister['password']),
    ]);
    return $userRegistered;
  }

  public function logout() {
    $logout = Auth::logout();
    return $logout;
  }

  public function refresh() {
    $refresh = Auth::refresh();
    return $refresh;
  }

}