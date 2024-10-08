<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentForm extends Model
{
    protected $table = 'payment_forms';

    protected $fillable = [
        'payment_id',
        'form_id',
        'form_url',
        'expire_time',
        'type',
        'status',
        'bank_connection_id',
        // 'payment_id',
        'standing_order_id',
        'error_code',
        'error_message',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
