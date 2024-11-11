<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTransactionLink extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_transaction_links';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'transaction_id',
        'paid',
    ];

    /**
     * Get the Shopify order associated with this link.
     */
    public function shopifyOrder()
    {
        return $this->belongsTo(ShopifyOrder::class, 'order_id');
    }

    /**
     * Get the FinAPI transaction associated with this link.
     */
    public function finapiTransaction()
    {
        return $this->belongsTo(FinapiTransaction::class, 'transaction_id');
    }
}
