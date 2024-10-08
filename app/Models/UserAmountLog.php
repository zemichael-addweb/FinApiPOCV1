<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAmountLog extends Model
{
    protected $table = 'user_amount_logs';

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'payment_id',
        'order_ref_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
