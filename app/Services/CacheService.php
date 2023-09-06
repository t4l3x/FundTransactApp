<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    private static CacheService $instance;



    public static function getInstance(): CacheService
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function get($key, $default = null)
    {
        return Cache::get($key, $default);
    }

    public function put($key, $value, $minutes): void
    {
        Cache::put($key, $value, $minutes);
    }

    public function forget($key): void
    {
        Cache::forget($key);
    }

    public function has(string $cacheKey): bool
    {
        return Cache::has($cacheKey);
    }
}
