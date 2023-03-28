<?php

declare(strict_types=1);

namespace Tomchochola\Laratchi\Auth\Http\Resources;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Tomchochola\Laratchi\Http\Resources\JsonApiResource;

/**
 * @property AuthenticatableContract $resource
 */
class MeResource extends JsonApiResource
{
    /**
     * @inheritDoc
     */
    public function __construct(AuthenticatableContract $resource)
    {
        parent::__construct($resource);
    }

    /**
     * @inheritDoc
     */
    public function getKey(): string
    {
        if ($this->resource instanceof Model) {
            $id = $this->resource->getKey();
        } else {
            $id = $this->resource->getAuthIdentifier();
        }

        \assert(\is_scalar($id));

        return (string) $id;
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        if ($this->resource instanceof Model) {
            $slug = $this->resource->getRouteKey();
        } else {
            $slug = $this->resource->getAuthIdentifier();
        }

        \assert(\is_scalar($slug));

        return (string) $slug;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        if ($this->resource instanceof Model) {
            return Str::snake($this->resource->getTable());
        }

        return Str::snake($this->resource::class);
    }
}