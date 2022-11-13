<?php

namespace App\Http\Controllers\ApiV1\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiV1\Account\SendVerificationCodeRequest;
use App\Http\Requests\ApiV1\VerifyCodeRequest;
use App\Jobs\SendVerificationCodeJob;
use App\Services\PhoneVerificationService;
use Illuminate\Http\JsonResponse;
use function response;

class PhoneVerificationController extends Controller
{
    public function __construct(
        private PhoneVerificationService $phoneVerificationService
    )
    {
    }

    /**
     * Отправляет код авторизации
     *
     * @param SendVerificationCodeRequest $request
     * @return void
     */
    public function sendVerificationCode(SendVerificationCodeRequest $request): void
    {
        SendVerificationCodeJob::dispatch($request->phone);
    }

    /**
     * Верифицирует код и возвращает текен авторизации
     *
     * @param VerifyCodeRequest $request
     * @return JsonResponse
     */
    public function verifyCode(VerifyCodeRequest $request): JsonResponse
    {
        $token = $this->phoneVerificationService->verifyCode(
            $request->phone,
            $request->code
        );

        if (!is_null($token)) {
            return response()->json(['token' => $token]);
        }

        return response()->json(['error' => 'Incorrect credentials.'], 401);
    }
}
