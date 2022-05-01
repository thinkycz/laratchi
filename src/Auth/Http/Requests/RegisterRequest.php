<?php

declare(strict_types=1);

namespace Tomchochola\Laratchi\Auth\Http\Requests;

use Illuminate\Auth\Access\Response;
use Tomchochola\Laratchi\Auth\Http\Validation\AuthValidity;
use Tomchochola\Laratchi\Http\Requests\SecureFormRequest;

class RegisterRequest extends SecureFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response|bool
    {
        mustBeGuest([$this->guardName()]);

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $authValidity = inject(AuthValidity::class);

        $guardName = $this->guardName();

        return [
            'remember' => $authValidity->remember($guardName)->required(),
            'email' => $authValidity->email($guardName)->required(),
            'password' => $authValidity->password($guardName)->confirmed()->required(),
            'name' => $authValidity->name($guardName)->required(),
            'terms_accepted' => $authValidity->termsAccepted($guardName)->accepted(),
        ];
    }

    /**
     * Get guard name.
     */
    public function guardName(): string
    {
        return resolveAuthManager()->getDefaultDriver();
    }

    /**
     * Get credentials.
     *
     * @return array<string, mixed>
     */
    public function credentials(): array
    {
        return $this->validatedInput()->only(['email']);
    }

    /**
     * Get password.
     *
     * @return array<string, mixed>
     */
    public function password(): array
    {
        return $this->validatedInput()->only(['password']);
    }

    /**
     * Get data.
     *
     * @return array<string, mixed>
     */
    public function data(): array
    {
        return $this->validatedInput()->except(['remember', 'password', 'password_confirmation', 'terms_accepted']);
    }

    /**
     * Get remember.
     */
    public function remember(): bool
    {
        return $this->validatedInput()->mustBool('remember');
    }
}
