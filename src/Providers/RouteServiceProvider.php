<?php

declare(strict_types=1);

namespace Tomchochola\Laratchi\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        parent::boot();

        $this->routes(static function (): void {
            $app = resolveApp();

            resolveRouteRegistrar()->middleware('api')
                ->prefix('api')
                ->group($app->basePath('routes/api.php'));

            resolveRouteRegistrar()->middleware('web')
                ->group($app->basePath('routes/web.php'));
        });
    }
}
