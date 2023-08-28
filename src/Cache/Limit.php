<?php

declare(strict_types=1);

namespace Tomchochola\Laratchi\Cache;

use Closure;
use Illuminate\Cache\RateLimiting\Limit as IlluminateLimit;
use Symfony\Component\HttpFoundation\Response;
use Tomchochola\Laratchi\Http\RequestSignature;

class Limit extends IlluminateLimit
{
    /**
     * @inheritDoc
     *
     * @param Closure(int): Response|Closure(int): never|Closure(): Response|Closure(): never|null $responseCallback
     */
    public function __construct(string $key, int $maxAttempts, int $decayMinutes, ?Closure $responseCallback = null)
    {
        parent::__construct($key, $maxAttempts, $decayMinutes);

        $this->responseCallback = $responseCallback;
    }

    /**
     * Default constructor.
     *
     * @param Closure(int): Response|Closure(int): never|Closure(): Response|Closure(): never|null $responseCallback
     */
    public static function default(string $key = '', int $maxAttempts = 3, int $decayMinutes = 60, ?Closure $responseCallback = null): self
    {
        return new self(RequestSignature::default($key)->hash(), $maxAttempts, $decayMinutes, $responseCallback);
    }
}
