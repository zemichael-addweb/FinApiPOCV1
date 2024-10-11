<?php

namespace App\Models;

use App\Services\FinAPIService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinAPIAccessToken extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'access_token',
        'token_type',
        'scope',
        'expires_in',
        'expired'
    ];

    protected $casts = [
        'expires_in' => 'integer'
    ];

    public function isExpired() {
        $isExpired = $this->created_at->addSeconds($this->expires_in)->isPast();
        if($isExpired) {
            $this->expired = true;
            $this->save();
        }
        return $isExpired;
    }

    public function getAuthorizationHeader() {
        $accessToken = self::getAccessToken();
        return "{$accessToken->token_type} {$accessToken->access_token}";
    }

    public static function getAccessToken($type) {
        $accessToken = self::orderBy('created_at', 'desc')->first();

        if(!$accessToken || $accessToken->isExpired()) {
            // TODO refresh token
            $authentication = FinAPIService::authenticate();
            if($authentication && !isset($authentication->error)){
                $accessToken = self::orderBy('created_at', 'desc')->first();
            }
        }

        return $accessToken;
    }
}
