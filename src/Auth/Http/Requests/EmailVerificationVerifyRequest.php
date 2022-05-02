<?php

declare(strict_types=1);

namespace Tomchochola\Laratchi\Auth\Http\Requests;

use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tomchochola\Laratchi\Http\Requests\SecureFormRequest;

class EmailVerificationVerifyRequest extends SecureFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response|bool
    {
        if (! $this->hasValidSignature()) {
            return false;
        }

        $user = $this->retrieveUser();

        $routeId = $this->route('id');
        $authId = $user->getAuthIdentifier();

        \assert(\is_string($routeId) && \is_scalar($authId));

        if (! \hash_equals($routeId, (string) $authId)) {
            return false;
        }

        $routeHash = $this->route('hash');
        $authEmail = $user->getEmailForVerification();

        \assert(\is_string($routeHash));

        if (! \hash_equals($routeHash, \hash('sha256', $authEmail))) {
            return false;
        }

        return true;
    }

    /**
     * Get guard name.
     */
    public function guardName(): string
    {
        return resolveAuthManager()->getDefaultDriver();
    }

    /**
     * Retrieve user.
     */
    public function retrieveUser(): AuthenticatableContract & MustVerifyEmailContract
    {
        $user = mustResolveUser([$this->guardName()]);

        if (! $user instanceof MustVerifyEmailContract) {
            throw new HttpException(SymfonyResponse::HTTP_FORBIDDEN);
        }

        return $user;
    }
}