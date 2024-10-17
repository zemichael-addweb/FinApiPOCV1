<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinapiForm extends Model
{
    protected $table = 'finapi_forms';

    protected $fillable = [
        'payment_id',
        'finapi_user_id',
        'form_id',
        'form_url',
        'expire_time',
        'type',
        'status',
        'bank_connection_id',
        'standing_order_id',
        'error_code',
        'error_message',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    public function finapiUser()
    {
        return $this->belongsTo(FinapiUser::class, 'finapi_user_id', 'id');
    }
}
