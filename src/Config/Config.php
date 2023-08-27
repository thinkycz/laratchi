<?php

declare(strict_types=1);

namespace Tomchochola\Laratchi\Config;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Tomchochola\Laratchi\Container\InjectTrait;
use Tomchochola\Laratchi\Exceptions\Panicker;
use Tomchochola\Laratchi\Support\AssertTrait;
use Tomchochola\Laratchi\Support\AssignTrait;
use Tomchochola\Laratchi\Support\ParserTrait;
use Tomchochola\Laratchi\Support\ParseTrait;
use Tomchochola\Laratchi\Support\Resolver;

class Config
{
    use AssertTrait;
    use AssignTrait;
    use InjectTrait;
    use ParserTrait;
    use ParseTrait;

    /**
     * Config repository.
     */
    public Repository $repository;

    /**
     * App.
     */
    public Application $app;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->repository = Resolver::resolveConfigRepository();
        $this->app = Resolver::resolveApp();
    }

    /**
     * Mixed getter.
     */
    public function mixed(?string $key = null): mixed
    {
        if (! $this->app->bound('env')) {
            Panicker::panic(__METHOD__, 'env is not bound to the container');
        }

        if ($key === null) {
            return $this->repository->all();
        }

        return $this->repository->get($key);
    }

    /**
     * Mixed setter.
     *
     * @return $this
     */
    public function assign(string $key, mixed $value): static
    {
        if (! $this->app->bound('env')) {
            Panicker::panic(__METHOD__, 'env is not bound to the container');
        }

        if (! $this->repository->has($key)) {
            Panicker::panic(__METHOD__, 'undefined config key', ['key' => $key]);
        }

        $this->repository->set($key, $value);

        return $this;
    }

    /**
     * App locale getter.
     */
    public function appLocale(): string
    {
        return $this->assertString('app.locale');
    }

    /**
     * App locales getter.
     *
     * @return array<int, string>
     */
    public function appLocales(): array
    {
        $locales = [];

        foreach ($this->assertArray('app.locales') as $locale) {
            $locales[] = assertString($locale);
        }

        return $locales;
    }

    /**
     * App name getter.
     */
    public function appName(): string
    {
        return $this->assertString('app.name');
    }

    /**
     * App debug getter.
     */
    public function appDebug(): bool
    {
        return $this->assertBool('app.debug');
    }

    /**
     * Auth guards getter.
     *
     * @return array<int, string>
     */
    public function authGuards(): array
    {
        $guards = [];

        foreach (\array_keys($this->assertArray('auth.guards')) as $guard) {
            $guards[] = assertString($guard);
        }

        return $guards;
    }

    /**
     * App env getter.
     */
    public function appEnv(): string
    {
        return assertString($this->app->environment());
    }

    /**
     * App env is.
     *
     * @param array<string> $envs
     */
    public function appEnvIs(array $envs): bool
    {
        return \in_array($this->appEnv(), $envs, true);
    }

    /**
     * Map app env.
     *
     * @template T
     *
     * @param array<string, T> $mapping
     *
     * @return T
     */
    public function appEnvMap(array $mapping): mixed
    {
        return $mapping[$this->appEnv()];
    }

    /**
     * App timezone getter.
     */
    public function appTimezone(): string
    {
        return $this->assertString('app.timezone');
    }

    /**
     * Auth defaults guard setter.
     *
     * @return $this
     */
    public function setAuthDefaultsGuard(string $guard): static
    {
        resolveAuthManager()->shouldUse($guard);

        return $this;
    }

    /**
     * Auth defaults guard getter.
     */
    public function authDefaultsGuard(): string
    {
        return $this->assertString('auth.defaults.guard');
    }

    /**
     * Auth defaults passwords getter.
     */
    public function authDefaultsPasswords(): string
    {
        return $this->assertString('auth.defaults.passwords');
    }

    /**
     * Auth passwords getter.
     *
     * @return array<int, string>
     */
    public function authPasswords(): array
    {
        $passwords = [];

        foreach (\array_keys($this->assertArray('auth.passwords')) as $password) {
            $passwords[] = assertString($password);
        }

        return $passwords;
    }

    /**
     * Auth providers getter.
     *
     * @return array<int, string>
     */
    public function authProviders(): array
    {
        $providers = [];

        foreach (\array_keys($this->assertArray('auth.providers')) as $provider) {
            $providers[] = assertString($provider);
        }

        return $providers;
    }

    /**
     * Auth defaults provider getter.
     */
    public function authDefaultsProvider(): string
    {
        return $this->assertString('auth.defaults.provider');
    }

    /**
     * Auth defaults provider setter.
     *
     * @return $this
     */
    public function setAuthDefaultsProvider(string $provider): static
    {
        return $this->assign('auth.defaults.provider', $provider);
    }

    /**
     * Set auth defaults passwords.
     *
     * @return $this
     */
    public function setAuthDefaultsPasswords(string $passwords): static
    {
        return $this->assign('auth.defaults.passwords', $passwords);
    }

    /**
     * App locale setter.
     *
     * @return $this
     */
    public function setAppLocale(string $locale): static
    {
        $this->app->setLocale($locale);

        return $this;
    }

    /**
     * App fallback locale setter.
     *
     * @return $this
     */
    public function setAppFallbackLocale(string $locale): static
    {
        $this->app->setFallbackLocale($locale);

        return $this;
    }

    /**
     * App fallback locale getter.
     */
    public function appFallbackLocale(): string
    {
        return $this->assertString('app.fallback_locale');
    }
}
