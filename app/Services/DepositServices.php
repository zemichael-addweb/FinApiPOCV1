<?php

namespace App\Services;

use App\Models\User;
use App\Models\Deposit;
use Illuminate\Support\Facades\DB;
use App\Models\DepositTransaction;
use Illuminate\Support\Facades\Auth;

class DepositServices
{
    public function __construct()
    {
        //
    }

    private static function getUser($userId = null)
    {
        if ($userId) {
            return User::find($userId);
        }
        return Auth::user();
    }

    public static function getBankConnections($userId = null)
    {
        $user = self::getUser($userId);
        return $user ? Deposit::where('user_id', $user->id)->first() : null;
    }

    public static function saveBankConnection($amount, $userId = null, $finapiPaymentId = null)
    {
        $user = self::getUser($userId);
        if ($user) {
            return DB::transaction(function () use ($user, $amount, $finapiPaymentId) {
                $deposit = Deposit::firstOrCreate(
                    ['user_id' => $user->id],
                    ['remaining_balance' => 0.00]
                );

                $deposit->remaining_balance += $amount;
                $deposit->save();

                // Log transaction
                $transaction = DepositTransaction::create([
                    'deposit_id' => $deposit->id,
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'transaction_type' => 'DEPOSIT',
                    'status' => 'SUCCESS',
                    'transaction_id' => $finapiPaymentId,
                    'currency' => 'EUR',
                    'transaction_date' => now(),
                ]);

                LoggerService::logUserAmount($user->id, $amount, 'DEPOSIT', $finapiPaymentId);

                return $transaction;
            });
        }
        return null;
    }

    public static function withdrawDeposit($amount, $userId = null, $finapiPaymentId = null)
    {
        $user = self::getUser($userId);
        if ($user) {
            return DB::transaction(function () use ($user, $amount, $finapiPaymentId) {
                $deposit = Deposit::where('user_id', $user->id)->first();

                if ($deposit && $deposit->remaining_balance >= $amount) {
                    $deposit->remaining_balance -= $amount;
                    $deposit->save();

                    // Log transaction
                    $transaction = DepositTransaction::create([
                        'deposit_id' => $deposit->id,
                        'user_id' => $user->id,
                        'amount' => $amount,
                        'transaction_type' => 'WITHDRAWAL',
                        'status' => 'SUCCESS',
                        'transaction_id' => $finapiPaymentId,
                        'currency' => 'EUR',
                        'transaction_date' => now(),
                    ]);

                    LoggerService::logUserAmount($user->id, $amount, 'DEPOSIT', $finapiPaymentId);

                    return $transaction;
                }
                return null;
            });
        }
        return null;
    }
}
