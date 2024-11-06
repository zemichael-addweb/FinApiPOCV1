<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'finapi_payment_id',
        'remaining_balance',
        'status',
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
