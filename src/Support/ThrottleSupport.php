<?php

declare(strict_types=1);

namespace Tomchochola\Laratchi\Support;

use Closure;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class ThrottleSupport
{
    /**
     * Register throttle.
     *
     * @param (Closure(int): never)|null $onError
     *
     * @return array{Closure(): void, Closure(): void}
     */
    public static function throttle(Limit $limit, ?Closure $onError = null): array
    {
        $hash = Typer::assertString($limit->key);

        $key = "throttle:{$hash}";

        $rateLimiter = resolveRateLimiter();

        $failed = $rateLimiter->tooManyAttempts($key, $limit->maxAttempts);

        if ($failed) {
            if ($onError === null) {
                throw new ThrottleRequestsException();
            }

            $onError($rateLimiter->availableIn($key));
        }

        return [
            static function () use ($key, $rateLimiter, $limit): void {
                $rateLimiter->hit($key, $limit->decayMinutes * 60);
            },
            static function () use ($key, $rateLimiter): void {
                $rateLimiter->clear($key);
            },
        ];
    }

    /**
     * Register throttle and hit.
     *
     * @param (Closure(int): never)|null $onError
     *
     * @return Closure(): void
     */
    public static function hit(Limit $limit, ?Closure $onError = null): Closure
    {
        [$hit, $clear] = static::throttle($limit, $onError);

        $hit();

        return $clear;
    }
}
