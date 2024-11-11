<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinapiTransaction extends Model
{
    use HasFactory;

    protected $table = 'finapi_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'finapi_id',
        'finapi_user_id',
        'finapi_form_id',
        'finapi_payment_id',
        'shopify_order_id',
        'account_id',
        'value_date',
        'bank_booking_date',
        'amount',
        'currency',
        'purpose',
        'counterpart_name',
        'type',
        'shopify_confirmation_number',
        'data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value_date' => 'date',
        'bank_booking_date' => 'date',
        'amount' => 'decimal:2',
        'data' => 'json',
    ];

    /**
     * Relationships
     */

    public function finapiUser()
    {
        return $this->belongsTo(FinapiUser::class, 'finapi_user_id');
    }

    public function finapiForm()
    {
        return $this->belongsTo(FinapiForm::class, 'finapi_form_id');
    }

    public function finapiPayment()
    {
        return $this->belongsTo(FinapiPayment::class, 'finapi_payment_id');
    }

    public function shopifyOrder()
    {
        return $this->belongsTo(ShopifyOrder::class, 'shopify_order_id');
    }
}
