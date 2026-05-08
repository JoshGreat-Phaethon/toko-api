<?php

namespace App\Http\Handlers;

use App\Contracts\Interfaces\UserInterface;
use Illuminate\Support\Facades\Auth;

class AuthHandler
{
    public function __construct(private UserInterface $userRepository)
    {
        //
    }

    public function register(array $data): mixed
    {
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user = $this->userRepository->store($data);
        $user->assignRole('member');

        return $user;
    }

    public function login(array $data): array
    {
        if (!Auth::attempt($data)) {
            throw new \InvalidArgumentException('Invalid credentials');
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->token = $token;

        return $user->toArray();
    }
}
