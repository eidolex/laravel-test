<?php

namespace App\Services;

use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public static $shouldLoginAfterRegister = true;

    public static $allowMultiDeviceLogin = true;

    /**
     * @param array $credentials
     * @throws ValidationException if credentials are incorrect
     * @return AuthResource
     */
    public function login(array $credentials): AuthResource
    {
        /** @var User just for auto completion */
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!self::$allowMultiDeviceLogin) {
            $this->removeAllApiTokens($user);
        }

        $token = $this->generateApiToken($user);

        return new AuthResource([
            'user' => $user,
            'token' => $token,
        ]);
    }


    /**
     * @param array $data
     * @throws ValidationException if credentials are incorrect
     * @return AuthResource
     */
    public function register(array $data): AuthResource
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        /** @var User just for auto completion */
        $user = User::create($data);

        $token = self::$shouldLoginAfterRegister
            ? $this->generateApiToken($user)
            : null;

        return new AuthResource([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * @param User $user
     */
    protected function removeAllApiTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * @param User $user
     * @return string
     */
    protected function generateApiToken(User $user): string
    {
        return $user->createToken('api')->plainTextToken;
    }
}
