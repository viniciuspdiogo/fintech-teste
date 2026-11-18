<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        return $this->respondWithToken($user, 'Usuário registrado com sucesso!', 201);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->authenticate();
        return $this->respondWithToken(Auth::user(), 'Usuário logado com sucesso!');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso!']);
    }

    /**
     * @param User $user
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    private function respondWithToken(User $user, string $message, int $status = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => [
                'user' => $user,
                'token' => $user->createToken('authToken')->plainTextToken,
            ],
        ], $status);
    }
}
