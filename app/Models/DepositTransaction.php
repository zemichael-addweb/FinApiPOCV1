<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'deposit_id',
        'user_id',
        'amount',
        'transaction_type',
        'status',
        'transaction_id',
        'currency',
        'transaction_date',
        'metadata',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'transaction_date' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the deposit associated with the transaction.
     */
    public function deposit()
    {
        return $this->belongsTo(Deposit::class);
    }

    /**
     * Get the user associated with the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include successful transactions.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'SUCCESS');
    }

    /**
     * Scope a query to only include pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    /**
     * Scope a query to filter transactions by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }
}
