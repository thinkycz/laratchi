<?php

declare(strict_types=1);

namespace Tomchochola\Laratchi\Validation;

use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

class SecureValidationFactory extends Factory
{
    /**
     * @inheritDoc
     */
    public function __construct(Factory $factory)
    {
        parent::__construct($factory->getTranslator(), $factory->getContainer());

        $this->setPresenceVerifier($factory->getPresenceVerifier());
    }

    /**
     * @inheritDoc
     *
     * @param array<mixed> $data
     * @param array<mixed> $rules
     * @param array<mixed> $messages
     * @param array<mixed> $customAttributes
     */
    protected function resolve(array $data, array $rules, array $messages, array $customAttributes): Validator
    {
        return new SecureValidator($this->translator, $data, $rules, $messages, $customAttributes);
    }
}
