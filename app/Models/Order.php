<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

//     order_id: The Shopify order ID.
//     order_reference: A unique reference number associated with the order (this will be used by customers to make payments).
//     customer_name: Name of the customer (for fallback matching).
//     order_amount: The total amount of the order.
//     order_status: Current status of the order (pending, paid, canceled).
//     created_at: The date when the order was placed.
//     payment_deadline: A date field to track when the 7-day payment window expires.
//     is_b2b: A boolean field to indicate if this is a B2B customer order.

    protected $fillable = [
        'order_id',
        'order_reference',
        'customer_name',
        'order_amount',
        'order_status',
        'ordered_at',
        'payment_deadline',
        'is_b2b'
    ];

    public function getOrders()
    {
        return $this->all();
    }

    public function getOrderById($id)
    {
        return $this->find($id);
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }

    public function createOrder($data)
    {
        return $this->create($data);
    }

    public function updateOrder($id, $data)
    {
        return $this->find($id)->update($data);
    }
}
