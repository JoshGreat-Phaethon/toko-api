<?php

namespace App\Http\Controllers;

use App\Helpers\ExceptionMapper;
use App\Http\Handlers\AuthHandler;
use App\Helpers\BaseResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthHandler $authHandler
    ) {
    }

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->authHandler->register($request->validated());

            DB::commit();
            return BaseResponse::Success(
                'User registered successfully',
                $user,
                200
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            return BaseResponse::Error(
                $th->getMessage(),
                null,
                ExceptionMapper::getStatusCode($th)
            );
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = $this->authHandler->login($request->validated());

            return BaseResponse::Success(
                'User logged in successfully',
                $user,
                200
            );
        } catch (\Throwable $th) {
            return BaseResponse::Error(
                $th->getMessage(),
                null,
                ExceptionMapper::getStatusCode($th)
            );
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user)
                $user->currentAccessToken()->delete();

            return BaseResponse::Success(
                'User logged out successfully',
                null,
                200
            );
        } catch (\Throwable $th) {
            return BaseResponse::Error(
                $th->getMessage(),
                null,
                ExceptionMapper::getStatusCode($th)
            );
        }
    }
}
