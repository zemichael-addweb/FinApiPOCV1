<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'finapi_user_id',
        'order_ref_number',
        'amount',
        'currency',
        'type',
        'status',
    ];

    public function order(){
        return $this->belongsTo(
            Order::class,
            'order_reference',
            'order_ref_number'
        );
    }

    public function getPayments()
    {
        return $this->all();
    }

    public function getPaymentById($id)
    {
        return $this->find($id);
    }

    public function getPaymentByOrderReference($orderReference){
        return $this->where('order_reference', $orderReference)->get();
    }
}
