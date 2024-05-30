<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UsersAndRoles;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\PersonalAccessToken;

use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        DB::beginTransaction();

        try {
            $this->deleteExpiredTokens();
            $loginDTO = $request->toDTO();

            if (Auth::attempt(['username' => $loginDTO->username, 'password' => $loginDTO->password])) {
                $user = Auth::user();

                switch (self::confirmCode($loginDTO->tfa_code))
                {
                    case 'Код не действителен':
                        DB::commit();
                        return self::getCode();

                    case 'Код не подтверждён':
                        return response()->json(['message' => 'Неверный код'], 422);

                    case 'Код подтверждён':
                        $user->tfa_code_count = 0;
                        $user->save();

                        $activeTokensCount = $user->tokens()->count();
                        $maxActiveTokens = env('MAX_ACTIVE_TOKENS_PER_USER', 3);

                        if ($activeTokensCount < $maxActiveTokens) {
                            $token = $user->createToken($loginDTO->username . '_token', ['*'], now()
                                ->addMinutes(env('SANCTUM_TOKEN_EXPIRATION')))->plainTextToken;
                            DB::commit();
                            return response()->json(['token' => $token], 200);
                        }

                        DB::rollback();
                        return response()->json(['error' => 'Превышено максимальное количество активных токенов']);
                }
            }

            DB::rollback();
            return response()->json(['error' => 'Неверный логин или пароль']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Ошибка аутентификации'], 500);
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $registerDTO = $request->toDTO();

            $user = new User([
                'username' => $registerDTO->username,
                'email' => $registerDTO->email,
                'password' => $registerDTO->password,
                'birthday' => $registerDTO->birthday,
            ]);

            $user->save();
            $userAndRole = new UsersAndRoles();
            $userAndRole->user_id = $user->id;
            $userAndRole->role_id = Role::where('cipher', 'GUEST')->value('id');
            $userAndRole->created_by = $user->id;
            $userAndRole->save();

            DB::commit();
            return response()->json(['Экземпляр ресурса созданного пользователя' => UserDTO::fromModelToDTO($user)], 201);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Ошибка регистрации пользователя'], 500);
        }
    }

    public function logout()
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();

            if ($user) {
                $user->currentAccessToken()->delete();

                DB::commit();
                return response()->json(['message' => 'Вы успешно разлогинились']);
            }

            DB::rollback();
            return response()->json(['error' => 'Вы не авторизованы']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Ошибка выхода из системы'], 500);
        }
    }

    public function logoutAll()
    {
        DB::beginTransaction();

        try {
            Auth::user()->tokens()->delete();

            DB::commit();
            return response()->json(['message' => 'Все ваши токены отозваны'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Ошибка выхода из всех сеансов'], 500);
        }
    }

    private function deleteExpiredTokens()
    {
        PersonalAccessToken::where('expires_at', '<', now())->delete();
    }

    public function getCode()
    {
        $user = Auth::user();
        $user->tfa_code_count += 1;
        $user->tfa_code = null;
        $user->tfa_code_valid_until = null;
        $user->save();

        if ($user->tfa_code_count > 3)
        {
            if ($user->delay_until == null)
            {
                $user->delay_until = Carbon::now()->addSeconds(30);
                $user->save();
            }

            if (Carbon::now()->lt($user->delay_until))
            {
                return response()->json(['message' => 'Подождите']);
            }
        }

        $user->tfa_code = mt_rand(100000, 999999);
        $user->tfa_code_valid_until = Carbon::now()->addSeconds(env('TFA_CODE_EXPIRATION', 60));
        $user->save();

        return response()->json(['tfa_code' => $user->tfa_code]);
    }

    private function confirmCode($tfa_code)
    {
        $user = Auth::user();

        if ($user->tfa_code && Carbon::now()->lt($user->tfa_code_valid_until))
        {
            if ($tfa_code == $user->tfa_code)
            {
                return 'Код подтверждён';
            }
            return 'Код не подтверждён';
        }
        return 'Код не действителен';
    }
}

