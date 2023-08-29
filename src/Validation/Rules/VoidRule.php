<?php

declare(strict_types=1);

namespace Tomchochola\Laratchi\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VoidRule implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @param Closure(mixed, mixed=): void $callback
     */
    public function __construct(protected Closure $callback) {}

    /**
     * @inheritDoc
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        ($this->callback)($value, $attribute);
    }
}
