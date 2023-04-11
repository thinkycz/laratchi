<?php

declare(strict_types=1);

namespace Tomchochola\Laratchi\Auth\Services;

use Illuminate\Notifications\AnonymousNotifiable;
use Tomchochola\Laratchi\Auth\Notifications\EmailConfirmationNotification;

class EmailBrokerService
{
    /**
     * Template.
     *
     * @var class-string<self>
     */
    public static string $template = self::class;

    /**
     * Inject.
     */
    public static function inject(): self
    {
        return new static::$template();
    }

    /**
     * Send token.
     */
    public function store(string $guard, string $email): string
    {
        $token = $this->token();

        resolveCache()->set($this->cacheKey($guard, $email), $token, $this->cacheExpiration());

        return $token;
    }

    /**
     * Validate token.
     */
    public function validate(string $guard, string $email, string $token): bool
    {
        $pass = resolveCache()->get($this->cacheKey($guard, $email)) === $token;

        if (resolveApp()->isProduction()) {
            return $pass;
        }

        return $pass || $token === '111111';
    }

    /**
     * Send anonymous notification.
     */
    public function send(string $guard, string $email, string $locale): void
    {
        (new AnonymousNotifiable())->route('mail', $email)->notify((new EmailConfirmationNotification($guard, $this->store($guard, $email), $email))->locale($locale));
    }

    /**
     * Cache key.
     */
    protected function cacheKey(string $guard, string $email): string
    {
        return "email_verification:{$guard}:{$email}";
    }

    /**
     * Token.
     */
    protected function token(): string
    {
        return (string) \random_int(100000, 999999);
    }

    /**
     * Token expiration in minutes.
     */
    protected function cacheExpiration(): ?int
    {
        return configInt('auth.verification.expire');
    }
}