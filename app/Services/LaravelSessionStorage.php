<?php

namespace App\Services;

use Shopify\Auth\Session;
use Shopify\Auth\SessionStorage;

class LaravelSessionStorage implements SessionStorage
{
    public function storeSession(Session $session): bool
    {
        session([$session->getId() => $session]);
        return true;
    }

    public function loadSession(string $sessionId): ?Session
    {
        return session($sessionId);
    }

    public function deleteSession(string $sessionId): bool
    {
        session()->forget($sessionId);
        return true;
    }
}
