<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // payment_id: Unique ID for the payment (from FinAPI or generated).
    // order_id: Foreign key linking to the orders table.
    // transaction_id: The bank transfer transaction ID (from FinAPI).
    // paid_amount: The amount paid by the customer.
    // payment_status: Status of the payment (pending, completed, failed, refunded).
    // received_at: Timestamp when the payment was received.
    // payment_method: Bank transfer, along with any specific FinAPI-related details.
    // reference_number: Order reference number from the bank transfer (to match with the order_reference).

    protected $fillable = [
        'payment_id',
        'order_id',
        'transaction_id',
        'paid_amount',
        'payment_status',
        'received_at',
        'payment_method',
        'reference_number'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function getPayments()
    {
        return $this->all();
    }

    public function getPaymentById($id)
    {
        return $this->find($id);
    }

    public function getPaymentByOrder($orderId){
        return $this->where('order_id', $orderId)->get();
    }
}
