<?php

namespace App\Cache;

use Illuminate\Support\Str;
use Illuminate\Contracts\Cache\Repository as Cache;

class WalletUpdateLimiter
{
    private $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function lock($id)
    {
        return $this->cache->add(
            $this->walletKey($id),
            true,
            60 * 2
        );
    }

    private function walletKey($id)
    {
        return Str::lower(static::class . $id);
    }

    public function release($id)
    {
        return $this->cache->forget(
            $this->walletKey($id)
        );
    }

    public function isLocked($id): bool
    {
        return $this->cache->has(
            $this->walletKey($id)
        );
    }
}
