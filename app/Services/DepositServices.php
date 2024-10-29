<?php

namespace App\Services;

use App\Models\Deposit;
use Illuminate\Http\Request;


class DepositServices {

    public function __construct()
    {
        //
    }

    public static function getUserDeposit()
    {
        return Deposit::where('user_id', auth()->user()->id)->first();
    }

    public static function addDeposit($amount)
    {
        $deposit = Deposit::where('user_id', auth()->user()->id)->first();
        if($deposit){
            $deposit->deposit_amount += $amount;
            $deposit->save();
        }
    }

    public static function withdrawDeposit($amount)
    {
        $deposit = Deposit::where('user_id', auth()->user()->id)->first();
        if($deposit){
            $deposit->deposit_amount -= $amount;
            $deposit->save();
        }
    }
}
