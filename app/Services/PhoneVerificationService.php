<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PhoneVerificationService
{
    /**
     * Верифицирует код и возвращает текен авторизации
     *
     * @param string $phone
     * @param string $code
     * @return string|null
     */
    public function verifyCode(string $phone, string $code): ?string
    {
        if ($user = User::where(['phone' => $phone, 'phone_verification_code' => $code])->first()) {

            $user->update([
                'phone_verification_code' => null,
                'phone_verified_at' => now()
            ]);

            Auth::login($user);

            return $user->createToken('MyAuthApp')->plainTextToken;
        }

        return null;
    }
}
