<?php

declare(strict_types=1);

namespace Tomchochola\Laratchi\Container;

use Tomchochola\Laratchi\Support\Typer;

trait InjectTrait
{
    /**
     * Inject.
     */
    public static function inject(): self
    {
        $instance = Facade::getResolved(self::class);

        if ($instance !== null) {
            return Typer::assertInstance($instance, self::class);
        }

        return Facade::setResolved(self::class, new self());
    }
}
