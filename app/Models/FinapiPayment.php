<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinapiPayment extends Model
{
    use HasFactory;

    protected $table = 'finapi_payments';

    protected $fillable = [
        'finapi_id',
        'user_id',
        'finapi_user_id',
        'deposit_id',
        'order_ref_number',
        'purpose',
        'currency',
        'payment_id',
        'finapi_form_id',
        'account_id',
        'iban',
        'bank_id',
        'type',
        'amount',
        'order_count',
        'status',
        'bank_message',
        'request_date',
        'execution_date',
        'instructed_execution_date',
        'instant_payment',
        'status_v2',
        'msg_id'
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'execution_date' => 'datetime',
        'instructed_execution_date' => 'date',
        'instant_payment' => 'boolean',
        'amount' => 'decimal:2'
    ];

    // Relationships

    /**
     * Get the user associated with the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the finAPI user associated with the payment.
     */
    public function finapiUser()
    {
        return $this->belongsTo(FinapiUser::class);
    }

    /**
     * Get the related form.
     */
    public function form()
    {
        return $this->belongsTo(FinapiForm::class);
    }
}
