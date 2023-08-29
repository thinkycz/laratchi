<?php

declare(strict_types=1);

namespace Tomchochola\Laratchi\Cache;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit as IlluminateLimit;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Tomchochola\Laratchi\Encoding\Hash;
use Tomchochola\Laratchi\Support\Resolver;

class Throttler
{
    /**
     * Rete limiter.
     */
    public RateLimiter $limiter;

    /**
     * Constructor.
     */
    public function __construct(public IlluminateLimit $limit)
    {
        $this->limiter = Resolver::resolveRateLimiter();
    }

    /**
     * Default constructor.
     *
     * @param Closure(): never|Closure(): Response|Closure(int): never|Closure(int): Response|null $responseCallback
     */
    public static function default(string $key = '', int $maxAttempts = 3, int $decayMinutes = 60, Closure|null $responseCallback = null): self
    {
        return new self(Limit::default($key, $maxAttempts, $decayMinutes, $responseCallback));
    }

    /**
     * Hash getter.
     */
    public function hash(): string
    {
        return static::class . ':' . Hash::encode([$this->limit->key]);
    }

    /**
     * Throttle.
     *
     * @param Closure(): never|Closure(): Response|Closure(int): never|Closure(int): Response|null $callback
     *
     * @return $this
     */
    public function throttle(Closure|null $callback = null): static
    {
        if ($this->failed()) {
            $this->throw($callback);
        }

        return $this;
    }

    /**
     * Throttle given callback.
     *
     * @param Closure($this): void|Closure(): void $closure
     * @param Closure(): never|Closure(): Response|Closure(int): never|Closure(int): Response|null $onError
     *
     * @return $this
     */
    public function callback(Closure $closure, Closure|null $onError = null): static
    {
        $this->throttle($onError);

        try {
            $closure($this);
        } catch (Throwable $exception) {
            $this->hit();

            throw $exception;
        }

        return $this;
    }

    /**
     * Throw exception.
     *
     * @param Closure(): never|Closure(): Response|Closure(int): never|Closure(int): Response|null $callback
     */
    public function throw(Closure|null $callback = null): never
    {
        if ($callback !== null) {
            throw new HttpResponseException($callback($this->availableIn()));
        }

        if (isset($this->limit->responseCallback)) {
            throw new HttpResponseException(($this->limit->responseCallback)($this->availableIn()));
        }

        throw new ThrottleRequestsException();
    }

    /**
     * Throttle failed getter.
     */
    public function failed(): bool
    {
        return $this->limiter->tooManyAttempts($this->hash(), $this->limit->maxAttempts);
    }

    /**
     * Available in getter.
     */
    public function availableIn(): int
    {
        return $this->limiter->availableIn($this->hash());
    }

    /**
     * Hit throttle.
     *
     * @return $this
     */
    public function hit(): static
    {
        $this->limiter->hit($this->hash(), $this->limit->decayMinutes * 60);

        return $this;
    }

    /**
     * Clear throttle.
     *
     * @return $this
     */
    public function clear(): static
    {
        $this->limiter->clear($this->hash());

        return $this;
    }
}
