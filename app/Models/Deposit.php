<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    // deposit_id: Unique ID for the deposit.
    // email: Customer’s email to link the deposit.
    // deposit_amount: The amount deposited by the customer.
    // remaining_balance: The amount left to be applied to future orders.
    // status: Current status of the deposit (available, used, refunded).
    // deposited_at: Timestamp when the deposit was made.

    protected $fillable = [
        'deposit_id',
        'email',
        'deposit_amount',
        'remaining_balance',
        'status',
        'deposited_at'
    ];

    public function getDeposits()
    {
        return $this->all();
    }

    public function getDepositById($id)
    {
        return $this->find($id);
    }

    public function createDeposit($data)
    {
        return $this->create($data);
    }

    public function updateDeposit($id, $data)
    {
        return $this->find($id)->update($data);
    }

}
