<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\PersonalAccessToken;

class appController extends Controller
{
    public function login(LoginRequest $request)
    {
        $this->deleteExpiredTokens();
        $loginDTO = $request->toDTO();

        if (Auth::attempt(['username' => $loginDTO->username, 'password' => $loginDTO->password]))
        {
            $user = Auth::user();

            $activeTokensCount = $user->tokens()->count();
            $maxActiveTokens = env('MAX_ACTIVE_TOKENS_PER_USER', 3);

            if ($activeTokensCount < $maxActiveTokens)
            {
                $token = $user->createToken($loginDTO->username . '_token', ['*'], now()
                    ->addMinutes(env('SANCTUM_TOKEN_EXPIRATION')))->plainTextToken;
                return response()->json(['token' => $token], 200);
            }

            return response()->json(['error' => 'Превышено максимальное количество активных токенов']);
        }

        return response()->json(['error' => 'Неверный логин или пароль']);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $registerDTO = $request->toDTO();

        $user = new User([
            'username' => $registerDTO->username,
            'email' => $registerDTO->email,
            'password' => $registerDTO->password,
            'birthday' => $registerDTO->birthday,
        ]);

        $user->save();

        return response()->json(['Экземпляр ресурса созданного пользователя' => $user], 201);
    }

    public function getUser(): JsonResponse
    {
        $user = Auth::user();
        $user = new UserDTO(['username' => $user->username, 'email' => $user->email,
            'password' => $user->password, 'birthday' => $user->birthday]);
        return response()->json(['Пользователь' => $user]);
    }

    public function logout()
    {
        $user = Auth::user();

        if ($user)
        {
            $user->currentAccessToken()->delete();

            return response()->json(['message' => 'Вы успешно разлогинились']);
        }

        return response()->json(['error' => 'Вы не авторизованы']);
    }

    public function getTokens()
    {
        $this->deleteExpiredTokens();
        return response()->json(['tokens' => Auth::user()->tokens->pluck('token')]);
    }

    public function logoutAll()
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Все ваши токены отозваны'], 200);
    }

    private function deleteExpiredTokens()
    {
        PersonalAccessToken::where('expires_at', '<', now())->delete();
    }
}
