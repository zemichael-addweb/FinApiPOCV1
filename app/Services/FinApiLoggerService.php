<?php

namespace App\Services;

use App\Models\FinapiForm;
use App\Models\UserAmountLog;
use App\Models\PaymentForm;
use App\Models\FinapiRequest;
use App\Models\FinapiUser;

class FinApiLoggerService
{
    public static function logUserAmount($userId, $amount, $type, $paymentId = null, $orderRefNumber = null)
    {
        return UserAmountLog::create([
            'user_id' => $userId,
            'amount' => $amount,
            'type' => $type,
            'payment_id' => $paymentId,
            'order_ref_number' => $orderRefNumber,
        ]);
    }

    public static function logFinapiForm($formData, $paymentId = null)
    {
        return FinapiForm::create([
            'payment_id' => $paymentId,
            'finapi_user_id' => $formData['finapi_user_id'] ?? null,
            'form_id' => $formData['form_id'] ?? null,
            'form_url' => $formData['form_url'] ?? null,
            'expire_time' => $formData['expire_time'] ?? null,
            'type' => $formData['type'] ?? null,
            'status' => $formData['status'] ?? null,
            'bank_connection_id' => $formData['bank_connection_id'] ?? null,
            'standing_order_id' => $formData['standing_order_id'] ?? null,
            'error_code' => $formData['error_code'] ?? null,
            'error_message' => $formData['error_message'] ?? null,
        ]);
    }

    public static function logFinapiRequest($endpoint, $headers, $payload, $responseCode, $responseBody, $requestId)
    {
        return FinapiRequest::create([
            'endpoint' => $endpoint,
            'headers' => json_encode($headers),
            'payload' => json_encode($payload),
            'response_code' => $responseCode,
            'response_body' => json_encode($responseBody),
            'request_id' => $requestId,
        ]);
    }

    public static function logFinapiUser($userId, $username, $password, $email, $accessToken, $expireAt, $refreshToken = null)
    {
        return FinapiUser::create([
            'user_id' => $userId,
            'username' => $username,
            'password' => bcrypt($password),
            'email' => $email,
            'access_token' => $accessToken,
            'expire_at' => $expireAt,
            'refresh_token' => $refreshToken,
        ]);
    }
}
