<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinapiBankConnection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'finapi_id',
        'finapi_form_id',
        'finapi_user_id',
        'bank_name',
        'blz',
        'bank_group',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function getBankInfo()
    {
        return $this->data['bank'] ?? null;
    }

    public function getInterfaces()
    {
        return $this->data['interfaces'] ?? null;
    }

    public function getAccountIds()
    {
        return $this->data['accountIds'] ?? [];
    }
}
