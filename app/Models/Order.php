<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "order_id",
        "order_reference",
        "customer_name",
        "order_amount",
        "order_status",
        "ordered_at",
        "payment_deadline",
        "is_b2b",
    ];

    public function getOrders()
    {
        return $this->all();
    }

    public function getOrderById($id)
    {
        return $this->find($id);
    }

    public function payments()
    {
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
