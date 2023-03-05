<?php

declare(strict_types=1);

if (! \function_exists('randomElement')) {
    /**
     * Select random element from array or return null on empty array.
     *
     * @template T
     *
     * @param array<T> $arr
     *
     * @return ?T
     */
    function randomElement(array $arr): mixed
    {
        if (\count($arr) === 0) {
            return null;
        }

        return $arr[\array_rand($arr)];
    }
}

if (! \function_exists('mustRandomElement')) {
    /**
     * Select random element from array.
     *
     * @template T
     *
     * @param non-empty-array<T> $arr
     *
     * @return T
     */
    function mustRandomElement(array $arr): mixed
    {
        return $arr[\array_rand($arr)];
    }
}

if (! \function_exists('extendedTrim')) {
    /**
     * Trim string using defaults plus provided characters.
     */
    function extendedTrim(string $string, string $characters = ''): string
    {
        return \trim($string, " \t\n\r\0\x0B{$characters}");
    }
}

if (! \function_exists('arrayFilterNull')) {
    /**
     * Filter null values from array.
     *
     * @param array<mixed> $array
     *
     * @return array<mixed>
     */
    function arrayFilterNull(array $array): array
    {
        return \array_filter($array, static fn (mixed $el): bool => $el !== null);
    }
}

if (! \function_exists('nonProductionThrow')) {
    /**
     * Throw exception only on production.
     */
    function nonProductionThrow(Throwable $throwable): void
    {
        if (isEnv(['staging', 'production']) === false) {
            throw $throwable;
        }

        resolveExceptionHandler()->report($throwable);
    }
}

if (! \function_exists('mustTransString')) {
    /**
     * Mandatory string translation resolver.
     *
     * @param array<string, string> $replace
     */
    function mustTransString(string $key, array $replace = [], ?string $locale = null, bool $fallback = true): string
    {
        $resolved = resolveTranslator()->get($key, $replace, $locale, $fallback);

        \assert(\is_string($resolved) && $resolved !== $key, "[{$key}] translation is missing");
        \assert(\trim($resolved) !== '', "[{$key}] translation is empty");

        return $resolved;
    }
}

if (! \function_exists('mustTransJsonString')) {
    /**
     * Mandatory string json translation resolver.
     *
     * @param array<string, string> $replace
     */
    function mustTransJsonString(string $message, array $replace = [], ?string $locale = null, bool $fallback = true): string
    {
        $resolved = resolveTranslator()->get($message, $replace, $locale, $fallback);

        \assert(\is_string($resolved), "[{$message}] translation is missing");
        \assert(\trim($resolved) !== '', "[{$message}] translation is empty");

        return $resolved;
    }
}

if (! \function_exists('mustTransArray')) {
    /**
     * Mandatory array translation resolver.
     *
     * @param array<string, string> $replace
     *
     * @return array<mixed>
     */
    function mustTransArray(string $key, array $replace = [], ?string $locale = null, bool $fallback = true): array
    {
        $resolved = resolveTranslator()->get($key, $replace, $locale, $fallback);

        \assert(\is_array($resolved), "[{$key}] translation is not array");

        return $resolved;
    }
}

if (! \function_exists('pathJoin')) {
    /**
     * Join paths using directory separator.
     *
     * @param array<string> $paths
     */
    function pathJoin(array $paths): string
    {
        return \implode(\DIRECTORY_SEPARATOR, $paths);
    }
}

if (! \function_exists('configBool')) {
    /**
     * Config boolean resolver.
     *
     * @param array<mixed> $in
     */
    function configBool(string $key, ?bool $default = null, array $in = []): ?bool
    {
        $value = resolveConfig()->get($key, $default) ?? $default;

        \assert($value === null || \is_bool($value), "[{$key}] config is not bool or null");
        \assert(\count($in) <= 0 || \in_array($value, $in, true), "[{$key}] config is not in available options");

        return $value;
    }
}

if (! \function_exists('mustConfigBool')) {
    /**
     * Mandatory config boolean resolver.
     *
     * @param array<mixed> $in
     */
    function mustConfigBool(string $key, ?bool $default = null, array $in = []): bool
    {
        $value = configBool($key, $default, $in);

        \assert($value !== null, "[{$key}] config is not bool");

        return $value;
    }
}

if (! \function_exists('configInt')) {
    /**
     * Config int resolver.
     *
     * @param array<mixed> $in
     */
    function configInt(string $key, ?int $default = null, array $in = []): ?int
    {
        $value = resolveConfig()->get($key, $default) ?? $default;

        \assert($value === null || \is_int($value), "[{$key}] config is not int or null");
        \assert(\count($in) <= 0 || \in_array($value, $in, true), "[{$key}] config is not in available options");

        return $value;
    }
}

if (! \function_exists('mustConfigInt')) {
    /**
     * Mandatory config int resolver.
     *
     * @param array<mixed> $in
     */
    function mustConfigInt(string $key, ?int $default = null, array $in = []): int
    {
        $value = configInt($key, $default, $in);

        \assert($value !== null, "[{$key}] config is not int");

        return $value;
    }
}

if (! \function_exists('configFloat')) {
    /**
     * Config float resolver.
     *
     * @param array<mixed> $in
     */
    function configFloat(string $key, ?float $default = null, array $in = []): ?float
    {
        $value = resolveConfig()->get($key, $default) ?? $default;

        \assert($value === null || \is_float($value), "[{$key}] config is not float or null");
        \assert(\count($in) <= 0 || \in_array($value, $in, true), "[{$key}] config is not in available options");

        return $value;
    }
}

if (! \function_exists('mustConfigFloat')) {
    /**
     * Mandatory config float resolver.
     *
     * @param array<mixed> $in
     */
    function mustConfigFloat(string $key, ?float $default = null, array $in = []): float
    {
        $value = configFloat($key, $default, $in);

        \assert($value !== null, "[{$key}] config is not float");

        return $value;
    }
}

if (! \function_exists('configArray')) {
    /**
     * Config array resolver.
     *
     * @param array<mixed> $default
     *
     * @return ?array<mixed>
     */
    function configArray(string $key, ?array $default = null): ?array
    {
        $value = resolveConfig()->get($key, $default) ?? $default;

        \assert($value === null || \is_array($value), "[{$key}] config is not array or null");

        return $value;
    }
}

if (! \function_exists('mustConfigArray')) {
    /**
     * Mandatory config array resolver.
     *
     * @param array<mixed> $default
     *
     * @return array<mixed>
     */
    function mustConfigArray(string $key, ?array $default = null): array
    {
        $value = configArray($key, $default);

        \assert($value !== null, "[{$key}] config is not array");

        return $value;
    }
}

if (! \function_exists('configString')) {
    /**
     * Config string resolver.
     *
     * @param array<mixed> $in
     */
    function configString(string $key, ?string $default = null, array $in = []): ?string
    {
        $value = resolveConfig()->get($key, $default) ?? $default;

        \assert(\count($in) <= 0 || \in_array($value, $in, true), "[{$key}] config is not in available options");
        \assert($value === null || \is_string($value), "[{$key}] config is not string or null");

        return $value;
    }
}

if (! \function_exists('mustConfigString')) {
    /**
     * Mandatory config string resolver.
     *
     * @param array<mixed> $in
     */
    function mustConfigString(string $key, ?string $default = null, array $in = []): string
    {
        $value = configString($key, $default, $in);

        \assert($value !== null, "[{$key}] config is not string");

        return $value;
    }
}

if (! \function_exists('inject')) {
    /**
     * Resolve a service from the container.
     *
     * @template T
     *
     * @param class-string<T> $class
     * @param array<mixed> $parameters
     *
     * @return T
     */
    function inject(string $class, array $parameters = []): mixed
    {
        $resolved = resolveApp()->make($class, $parameters);

        \assert($resolved instanceof $class);

        return $resolved;
    }
}

if (! \function_exists('resolveApp')) {
    /**
     * Resolve app.
     */
    function resolveApp(): Illuminate\Foundation\Application
    {
        return Illuminate\Foundation\Application::getInstance();
    }
}

if (! \function_exists('resolveArtisan')) {
    /**
     * Resolve console kernel.
     */
    function resolveArtisan(): Illuminate\Foundation\Console\Kernel
    {
        $resolved = Illuminate\Support\Facades\Artisan::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Foundation\Console\Kernel);

        return $resolved;
    }
}

if (! \function_exists('resolveKernel')) {
    /**
     * Resolve HTTP kernel.
     */
    function resolveKernel(): Illuminate\Foundation\Http\Kernel
    {
        $resolved = resolveApp()->make(Illuminate\Contracts\Http\Kernel::class);

        \assert($resolved instanceof Illuminate\Foundation\Http\Kernel);

        return $resolved;
    }
}

if (! \function_exists('resolveConsoleKernel')) {
    /**
     * Resolve console kernel.
     */
    function resolveConsoleKernel(): Illuminate\Foundation\Console\Kernel
    {
        return resolveArtisan();
    }
}

if (! \function_exists('resolveHttpKernel')) {
    /**
     * Resolve HTTP kernel.
     */
    function resolveHttpKernel(): Illuminate\Foundation\Http\Kernel
    {
        return resolveKernel();
    }
}

if (! \function_exists('resolveAuthManager')) {
    /**
     * Resolve auth manager.
     */
    function resolveAuthManager(): Illuminate\Auth\AuthManager
    {
        $resolved = Illuminate\Support\Facades\Auth::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Auth\AuthManager);

        return $resolved;
    }
}

if (! \function_exists('resolveBlade')) {
    /**
     * Resolve blade.
     */
    function resolveBlade(): Illuminate\View\Compilers\BladeCompiler
    {
        $resolved = Illuminate\Support\Facades\Blade::getFacadeRoot();

        \assert($resolved instanceof Illuminate\View\Compilers\BladeCompiler);

        return $resolved;
    }
}

if (! \function_exists('resolveBroadcastManager')) {
    /**
     * Resolve broadcast manager.
     */
    function resolveBroadcastManager(): Illuminate\Broadcasting\BroadcastManager
    {
        $resolved = Illuminate\Support\Facades\Broadcast::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Broadcasting\BroadcastManager);

        return $resolved;
    }
}

if (! \function_exists('resolveBus')) {
    /**
     * Resolve bus.
     */
    function resolveBus(): Illuminate\Contracts\Bus\QueueingDispatcher
    {
        $resolved = Illuminate\Support\Facades\Bus::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Contracts\Bus\QueueingDispatcher);

        return $resolved;
    }
}

if (! \function_exists('resolveCacheManager')) {
    /**
     * Resolve cache manager.
     */
    function resolveCacheManager(): Illuminate\Cache\CacheManager
    {
        $resolved = Illuminate\Support\Facades\Cache::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Cache\CacheManager);

        return $resolved;
    }
}

if (! \function_exists('resolveConfig')) {
    /**
     * Resolve config.
     */
    function resolveConfig(): Illuminate\Config\Repository
    {
        $resolved = Illuminate\Support\Facades\Config::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Config\Repository);

        return $resolved;
    }
}

if (! \function_exists('resolveCookieJar')) {
    /**
     * Resolve cookie.
     */
    function resolveCookieJar(): Illuminate\Cookie\CookieJar
    {
        $resolved = Illuminate\Support\Facades\Cookie::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Cookie\CookieJar);

        return $resolved;
    }
}

if (! \function_exists('resolveEncrypter')) {
    /**
     * Resolve encrypter.
     */
    function resolveEncrypter(): Illuminate\Encryption\Encrypter
    {
        $resolved = Illuminate\Support\Facades\Crypt::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Encryption\Encrypter);

        return $resolved;
    }
}

if (! \function_exists('resolveDatabaseManager')) {
    /**
     * Resolve database manager.
     */
    function resolveDatabaseManager(): Illuminate\Database\DatabaseManager
    {
        $resolved = Illuminate\Support\Facades\DB::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Database\DatabaseManager);

        return $resolved;
    }
}

if (! \function_exists('resolveEventDispatcher')) {
    /**
     * Resolve event dispatcher.
     */
    function resolveEventDispatcher(): Illuminate\Contracts\Events\Dispatcher
    {
        $resolved = Illuminate\Support\Facades\Event::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Contracts\Events\Dispatcher);

        return $resolved;
    }
}

if (! \function_exists('resolveFilesystem')) {
    /**
     * Resolve filesystem.
     */
    function resolveFilesystem(): Illuminate\Filesystem\Filesystem
    {
        $resolved = Illuminate\Support\Facades\File::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Filesystem\Filesystem);

        return $resolved;
    }
}

if (! \function_exists('resolveGate')) {
    /**
     * Resolve gate.
     */
    function resolveGate(): Illuminate\Auth\Access\Gate
    {
        $resolved = Illuminate\Support\Facades\Gate::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Auth\Access\Gate);

        return $resolved;
    }
}

if (! \function_exists('resolveHashManager')) {
    /**
     * Resolve hash manager.
     */
    function resolveHashManager(): Illuminate\Hashing\HashManager
    {
        $resolved = Illuminate\Support\Facades\Hash::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Hashing\HashManager);

        return $resolved;
    }
}

if (! \function_exists('resolveHasher')) {
    /**
     * Resolve hasher.
     */
    function resolveHasher(?string $driver = null): Illuminate\Contracts\Hashing\Hasher
    {
        $hasher = resolveHashManager()->driver($driver);

        \assert($hasher instanceof Illuminate\Contracts\Hashing\Hasher);

        return $hasher;
    }
}

if (! \function_exists('resolveHttp')) {
    /**
     * Resolve HTTP client.
     */
    function resolveHttp(): Illuminate\Http\Client\Factory
    {
        $resolved = Illuminate\Support\Facades\Http::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Http\Client\Factory);

        return $resolved;
    }
}

if (! \function_exists('resolveTranslator')) {
    /**
     * Resolve translator.
     */
    function resolveTranslator(): Illuminate\Translation\Translator
    {
        $resolved = Illuminate\Support\Facades\Lang::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Translation\Translator);

        return $resolved;
    }
}

if (! \function_exists('resolveLogger')) {
    /**
     * Resolve logger.
     */
    function resolveLogger(): Illuminate\Log\LogManager
    {
        $resolved = Illuminate\Support\Facades\Log::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Log\LogManager);

        return $resolved;
    }
}

if (! \function_exists('resolveMailManager')) {
    /**
     * Resolve mail manager.
     */
    function resolveMailManager(): Illuminate\Contracts\Mail\Factory
    {
        $resolved = Illuminate\Support\Facades\Mail::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Contracts\Mail\Factory);

        return $resolved;
    }
}

if (! \function_exists('resolveNotificator')) {
    /**
     * Resolve notification manager.
     */
    function resolveNotificator(): Illuminate\Contracts\Notifications\Factory
    {
        $resolved = Illuminate\Support\Facades\Notification::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Contracts\Notifications\Factory);

        return $resolved;
    }
}

if (! \function_exists('resolveParallelTesting')) {
    /**
     * Resolve parallel testing.
     */
    function resolveParallelTesting(): Illuminate\Testing\ParallelTesting
    {
        $resolved = Illuminate\Support\Facades\ParallelTesting::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Testing\ParallelTesting);

        return $resolved;
    }
}

if (! \function_exists('resolvePasswordBrokerManager')) {
    /**
     * Resolve password broker manager.
     */
    function resolvePasswordBrokerManager(): Illuminate\Auth\Passwords\PasswordBrokerManager
    {
        $resolved = Illuminate\Support\Facades\Password::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Auth\Passwords\PasswordBrokerManager);

        return $resolved;
    }
}

if (! \function_exists('resolveQueueManager')) {
    /**
     * Resolve queue manager.
     */
    function resolveQueueManager(): Illuminate\Queue\QueueManager
    {
        $resolved = Illuminate\Support\Facades\Queue::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Queue\QueueManager);

        return $resolved;
    }
}

if (! \function_exists('resolveRateLimiter')) {
    /**
     * Resolve rate limiter.
     */
    function resolveRateLimiter(): Illuminate\Cache\RateLimiter
    {
        $resolved = Illuminate\Support\Facades\RateLimiter::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Cache\RateLimiter);

        return $resolved;
    }
}

if (! \function_exists('resolveRedirector')) {
    /**
     * Resolve redirector.
     */
    function resolveRedirector(): Illuminate\Routing\Redirector
    {
        $resolved = Illuminate\Support\Facades\Redirect::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Routing\Redirector);

        return $resolved;
    }
}

if (! \function_exists('resolveRedisManager')) {
    /**
     * Resolve redis manager.
     */
    function resolveRedisManager(): Illuminate\Redis\RedisManager
    {
        $resolved = Illuminate\Support\Facades\Redis::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Redis\RedisManager);

        return $resolved;
    }
}

if (! \function_exists('resolveRequest')) {
    /**
     * Resolve request.
     */
    function resolveRequest(): Illuminate\Http\Request
    {
        $resolved = Illuminate\Support\Facades\Request::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Http\Request);

        return $resolved;
    }
}

if (! \function_exists('resolveResponseFactory')) {
    /**
     * Resolve response factory.
     */
    function resolveResponseFactory(): Illuminate\Routing\ResponseFactory
    {
        $resolved = Illuminate\Support\Facades\Response::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Routing\ResponseFactory);

        return $resolved;
    }
}

if (! \function_exists('resolveRouter')) {
    /**
     * Resolve router.
     */
    function resolveRouter(): Illuminate\Routing\Router
    {
        $resolved = Illuminate\Support\Facades\Route::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Routing\Router);

        return $resolved;
    }
}

if (! \function_exists('resolveRouteRegistrar')) {
    /**
     * Resolve route registrar.
     */
    function resolveRouteRegistrar(): Illuminate\Routing\RouteRegistrar
    {
        return new Illuminate\Routing\RouteRegistrar(resolveRouter());
    }
}

if (! \function_exists('resolveSchema')) {
    /**
     * Resolve schema.
     */
    function resolveSchema(): Illuminate\Database\Schema\Builder
    {
        $resolved = Illuminate\Support\Facades\Schema::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Database\Schema\Builder);

        return $resolved;
    }
}

if (! \function_exists('resolveSessionManager')) {
    /**
     * Resolve session manager.
     */
    function resolveSessionManager(): Illuminate\Session\SessionManager
    {
        $resolved = Illuminate\Support\Facades\Session::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Session\SessionManager);

        return $resolved;
    }
}

if (! \function_exists('resolveSession')) {
    /**
     * Resolve session.
     */
    function resolveSession(?string $driver = null): Illuminate\Session\Store
    {
        $resolved = resolveSessionManager()->driver($driver);

        \assert($resolved instanceof Illuminate\Session\Store);

        return $resolved;
    }
}

if (! \function_exists('resolveFilesystemManager')) {
    /**
     * Resolve filesystem manager.
     */
    function resolveFilesystemManager(): Illuminate\Filesystem\FilesystemManager
    {
        $resolved = Illuminate\Support\Facades\Storage::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Filesystem\FilesystemManager);

        return $resolved;
    }
}

if (! \function_exists('resolveUrlFactory')) {
    /**
     * Resolve url factory.
     */
    function resolveUrlFactory(): Illuminate\Routing\UrlGenerator
    {
        $resolved = Illuminate\Support\Facades\URL::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Routing\UrlGenerator);

        return $resolved;
    }
}

if (! \function_exists('resolveValidatorFactory')) {
    /**
     * Resolve validator factory.
     */
    function resolveValidatorFactory(): Illuminate\Validation\Factory
    {
        $resolved = Illuminate\Support\Facades\Validator::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Validation\Factory);

        return $resolved;
    }
}

if (! \function_exists('resolveViewFactory')) {
    /**
     * Resolve view factory.
     */
    function resolveViewFactory(): Illuminate\View\Factory
    {
        $resolved = Illuminate\Support\Facades\View::getFacadeRoot();

        \assert($resolved instanceof Illuminate\View\Factory);

        return $resolved;
    }
}

if (! \function_exists('resolveExceptionHandler')) {
    /**
     * Resolve exception handler.
     */
    function resolveExceptionHandler(): Illuminate\Foundation\Exceptions\Handler
    {
        $resolved = resolveApp()->make(Illuminate\Contracts\Debug\ExceptionHandler::class);

        \assert($resolved instanceof Illuminate\Foundation\Exceptions\Handler);

        return $resolved;
    }
}

if (! \function_exists('resolveDate')) {
    /**
     * Resolve date factory.
     */
    function resolveDate(): Illuminate\Support\DateFactory
    {
        $resolved = Illuminate\Support\Facades\Date::getFacadeRoot();

        \assert($resolved instanceof Illuminate\Support\DateFactory);

        return $resolved;
    }
}

if (! \function_exists('resolveMix')) {
    /**
     * Resolve mix.
     */
    function resolveMix(): Illuminate\Foundation\Mix
    {
        return inject(Illuminate\Foundation\Mix::class);
    }
}

if (! \function_exists('resolveVite')) {
    /**
     * Resolve vite.
     */
    function resolveVite(): Illuminate\Foundation\Vite
    {
        return inject(Illuminate\Foundation\Vite::class);
    }
}

if (! \function_exists('mustBeGuest')) {
    /**
     * Throw if authenticated.
     *
     * @param array<string|null> $guards
     * @param (Closure(): never)|null $onError
     */
    function mustBeGuest(array $guards = [null], ?Closure $onError = null): void
    {
        $authManager = resolveAuthManager();

        foreach ($guards as $guard) {
            if (! $authManager->guard($guard)->guest()) {
                if ($onError !== null) {
                    $onError();
                }

                throw new Tomchochola\Laratchi\Exceptions\MustBeGuestHttpException();
            }
        }
    }
}

if (! \function_exists('isGuest')) {
    /**
     * Check if authenticated.
     *
     * @param array<string|null> $guards
     */
    function isGuest(array $guards = [null]): bool
    {
        $authManager = resolveAuthManager();

        foreach ($guards as $guard) {
            if (! $authManager->guard($guard)->guest()) {
                return false;
            }
        }

        return true;
    }
}

if (! \function_exists('resolveUser')) {
    /**
     * Resolve user or null.
     *
     * @template T of Illuminate\Contracts\Auth\Authenticatable
     *
     * @param array<string|null> $guards
     * @param class-string<T> $template
     *
     * @return ?T
     */
    function resolveUser(array $guards = [null], string $template = Illuminate\Contracts\Auth\Authenticatable::class): ?Illuminate\Contracts\Auth\Authenticatable
    {
        $authManager = resolveAuthManager();

        foreach ($guards as $guard) {
            $user = $authManager->guard($guard)->user();

            if ($user !== null) {
                \assert($user instanceof $template);

                return $user;
            }
        }

        return null;
    }
}

if (! \function_exists('mustResolveUser')) {
    /**
     * Resolve user or throw 401.
     *
     * @template T of Illuminate\Contracts\Auth\Authenticatable
     *
     * @param array<string|null> $guards
     * @param class-string<T> $template
     * @param (Closure(): never)|null $onError
     *
     * @return T
     */
    function mustResolveUser(array $guards = [null], string $template = Illuminate\Contracts\Auth\Authenticatable::class, ?Closure $onError = null): Illuminate\Contracts\Auth\Authenticatable
    {
        $authManager = resolveAuthManager();

        foreach ($guards as $guard) {
            $user = $authManager->guard($guard)->user();

            if ($user !== null) {
                \assert($user instanceof $template);

                return $user;
            }
        }

        if ($onError !== null) {
            $onError();
        }

        throw new Illuminate\Auth\AuthenticationException();
    }
}

if (! \function_exists('requestSignature')) {
    /**
     * Make request signature.
     *
     * @param array<mixed> $data
     */
    function requestSignature(array $data = [], bool $defaults = true): Tomchochola\Laratchi\Http\Requests\RequestSignature
    {
        return new Tomchochola\Laratchi\Http\Requests\RequestSignature($data, $defaults);
    }
}

if (! \function_exists('envString')) {
    /**
     * Env string resolver.
     *
     * @param array<mixed> $in
     */
    function envString(string $key, ?string $default = null, bool $trim = true, array $in = []): ?string
    {
        $value = env($key, $default) ?? $default;

        if ($value === null) {
            return $default;
        }

        $value = \filter_var($value);

        \assert($value !== false, "[{$key}] env is not string or null");

        if ($trim) {
            $value = \trim($value);
        }

        if ($value === '') {
            $value = $default;
        }

        \assert(\count($in) <= 0 || \in_array($value, $in, true), "[{$key}] env is not in available options");

        return $value;
    }
}

if (! \function_exists('mustEnvString')) {
    /**
     * Mandatory env string resolver.
     *
     * @param array<mixed> $in
     */
    function mustEnvString(string $key, ?string $default = null, bool $trim = true, array $in = []): string
    {
        $value = envString($key, $default, $trim, $in);

        \assert($value !== null, "[{$key}] env is not string");

        return $value;
    }
}

if (! \function_exists('envBool')) {
    /**
     * Env bool resolver.
     *
     * @param array<mixed> $in
     */
    function envBool(string $key, ?bool $default = null, array $in = []): ?bool
    {
        $value = env($key, $default) ?? $default;

        if ($value === null) {
            return $default;
        }

        $value = \filter_var($value, \FILTER_VALIDATE_BOOL, \FILTER_NULL_ON_FAILURE);

        \assert($value !== null, "[{$key}] env is not bool or null");
        \assert(\count($in) <= 0 || \in_array($value, $in, true), "[{$key}] env is not in available options");

        return $value;
    }
}

if (! \function_exists('mustEnvBool')) {
    /**
     * Mandatory env bool resolver.
     *
     * @param array<mixed> $in
     */
    function mustEnvBool(string $key, ?bool $default = null, array $in = []): bool
    {
        $value = envBool($key, $default, $in);

        \assert($value !== null, "[{$key}] env is not bool");

        return $value;
    }
}

if (! \function_exists('envInt')) {
    /**
     * Env int resolver.
     *
     * @param array<mixed> $in
     */
    function envInt(string $key, ?int $default = null, array $in = []): ?int
    {
        $value = env($key, $default) ?? $default;

        if ($value === null) {
            return $default;
        }

        $value = \filter_var($value, \FILTER_VALIDATE_INT);

        \assert($value !== false, "[{$key}] env is not int or null");
        \assert(\count($in) <= 0 || \in_array($value, $in, true), "[{$key}] env is not in available options");

        return $value;
    }
}

if (! \function_exists('mustEnvInt')) {
    /**
     * Mandatory env int resolver.
     *
     * @param array<mixed> $in
     */
    function mustEnvInt(string $key, ?int $default = null, array $in = []): int
    {
        $value = envInt($key, $default, $in);

        \assert($value !== null, "[{$key}] env is not int");

        return $value;
    }
}

if (! \function_exists('envFloat')) {
    /**
     * Env float resolver.
     *
     * @param array<mixed> $in
     */
    function envFloat(string $key, ?float $default = null, array $in = []): ?float
    {
        $value = env($key, $default) ?? $default;

        if ($value === null) {
            return $default;
        }

        $value = \filter_var($value, \FILTER_VALIDATE_FLOAT);

        \assert($value !== false, "[{$key}] env is not float or null");
        \assert(\count($in) <= 0 || \in_array($value, $in, true), "[{$key}] env is not in available options");

        return $value;
    }
}

if (! \function_exists('mustEnvFloat')) {
    /**
     * Mandatory env float resolver.
     *
     * @param array<mixed> $in
     */
    function mustEnvFloat(string $key, ?float $default = null, array $in = []): float
    {
        $value = envFloat($key, $default, $in);

        \assert($value !== null, "[{$key}] env is not float");

        return $value;
    }
}

if (! \function_exists('currentEnv')) {
    /**
     * Get current env.
     */
    function currentEnv(): string
    {
        $current = resolveApp()->make('env');

        \assert(\is_string($current));

        return $current;
    }
}

if (! \function_exists('currentEnvEnv')) {
    /**
     * Get current env in env scope.
     */
    function currentEnvEnv(): string
    {
        return mustEnvString('APP_ENV');
    }
}

if (! \function_exists('isEnv')) {
    /**
     * Check for current env.
     *
     * @param array<int, string> $envs
     */
    function isEnv(array $envs): bool
    {
        return \in_array(currentEnv(), $envs, true);
    }
}

if (! \function_exists('isEnvEnv')) {
    /**
     * Check for current env in env scope.
     *
     * @param array<int, string> $envs
     */
    function isEnvEnv(array $envs): bool
    {
        return \in_array(currentEnvEnv(), $envs, true);
    }
}

if (! \function_exists('envConfig')) {
    /**
     * Get config in env scope.
     */
    function envConfig(): Illuminate\Config\Repository
    {
        $resolved = resolveApp()->make('config');

        \assert($resolved instanceof Illuminate\Config\Repository);

        return $resolved;
    }
}

if (! \function_exists('mapEnv')) {
    /**
     * Map env.
     *
     * @param array<string, mixed> $mapping
     */
    function mapEnv(array $mapping, mixed $default = null): mixed
    {
        if ($default === null) {
            return $mapping[currentEnv()];
        }

        return $mapping[currentEnv()] ?? $default;
    }
}

if (! \function_exists('mapEnvEnv')) {
    /**
     * Map env in env scope.
     *
     * @param array<string, mixed> $mapping
     */
    function mapEnvEnv(array $mapping, mixed $default = null): mixed
    {
        if ($default === null) {
            return $mapping[currentEnvEnv()];
        }

        return $mapping[currentEnvEnv()] ?? $default;
    }
}

if (! \function_exists('assertNever')) {
    /**
     * Assert never.
     */
    function assertNever(string $message = 'assert never'): never
    {
        throw new LogicException($message);
    }
}

if (! \function_exists('assertNeverIf')) {
    /**
     * Assert never if.
     */
    function assertNeverIf(bool $pass, string $message = 'assert never if'): void
    {
        if ($pass) {
            throw new LogicException($message);
        }
    }
}

if (! \function_exists('assertNeverIfNot')) {
    /**
     * Assert never if not.
     */
    function assertNeverIfNot(bool $pass, string $message = 'assert never if not'): void
    {
        if (! $pass) {
            throw new LogicException($message);
        }
    }
}

if (! \function_exists('assertNeverClosure')) {
    /**
     * Assert never closure.
     *
     * @return Closure(): never
     */
    function assertNeverClosure(string $message = 'assert never closure'): Closure
    {
        return static function () use ($message): never {
            throw new LogicException($message);
        };
    }
}

if (! \function_exists('strPutCsv')) {
    /**
     * Encode array to csv.
     *
     * @param array<mixed> $data
     */
    function strPutCsv(array $data): string
    {
        return \implode(',', \array_map(static function (mixed $value): string {
            if (\is_string($value)) {
                return '"'.\str_replace('"', '""', $value).'"';
            }

            \assert(\is_scalar($value) || $value === null);

            return (string) $value;
        }, $data));
    }
}
