<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinapiForm extends Model
{
    protected $table = 'finapi_webforms';

    protected $fillable = [
        'finapi_id',
        'payment_id',
        'finapi_user_id',
        'finapi_payment_id',
        'order_ref_number',
        'form_url',
        'expire_time',
        'type',
        'status',
        'bank_connection_id',
        'standing_order_id',
        'error_code',
        'error_message',
    ];

    public function finapiUser()
    {
        return $this->belongsTo(FinapiUser::class, 'finapi_user_id', 'id');
    }
}
