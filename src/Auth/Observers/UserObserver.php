<?php

declare(strict_types=1);

namespace Tomchochola\Laratchi\Auth\Observers;

use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Tomchochola\Laratchi\Auth\Actions\CycleRememberTokenAction;
use Tomchochola\Laratchi\Auth\Actions\LogoutOtherDevicesAction;
use Tomchochola\Laratchi\Auth\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if (blank($user->getAuthPassword())) {
            $this->sendPasswordInit($user);
        }
    }

    /**
     * Handle the User "updating" event.
     */
    public function updating(User $user): void
    {
        if ($user->isDirty('email') && $user instanceof MustVerifyEmailContract) {
            $this->clearEmailVerifiedAt($user);
        }

        if ($user->isDirty('password')) {
            $this->cycleRememberToken($user);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if ($user->wasChanged('email') && $user instanceof MustVerifyEmailContract && ! $user->hasVerifiedEmail()) {
            $this->sendEmailVerificationNotification($user);
        }

        if ($user->wasChanged('email')) {
            $this->clearPasswordResetAfterEmailUpdate($user);

            if (blank($user->getAuthPassword())) {
                $this->sendPasswordInit($user);
            }
        }

        if ($user->wasChanged('password')) {
            $this->logoutOtherDevices($user);
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->clearNotifications($user);
        $this->clearPasswordReset($user);
        $this->logoutOtherDevices($user);
    }

    /**
     * Delete all user password reset tokens.
     */
    protected function clearPasswordReset(User $user): void
    {
        $broker = resolvePasswordBrokerManager()->broker($user->getPasswordBrokerName());

        \assert($broker instanceof PasswordBroker);

        $broker->deleteToken($user);
    }

    /**
     * Send password init.
     */
    protected function sendPasswordInit(User $user): void
    {
        $broker = resolvePasswordBrokerManager()->broker($user->getPasswordBrokerName());

        \assert($broker instanceof PasswordBroker);

        $broker->sendResetLink(['email' => $user->getEmailForPasswordReset()]);
    }

    /**
     * Clear email verified at field.
     */
    protected function clearEmailVerifiedAt(User&MustVerifyEmailContract $user): void
    {
        $user->forceFill(['email_verified_at' => null]);
    }

    /**
     * Cycle remember token.
     */
    protected function cycleRememberToken(User $user): void
    {
        inject(CycleRememberTokenAction::class)->handle($user);
    }

    /**
     * Send email verification notification.
     */
    protected function sendEmailVerificationNotification(User&MustVerifyEmailContract $user): void
    {
        $user->sendEmailVerificationNotification();
    }

    /**
     * Clear notifications table.
     */
    protected function clearNotifications(User $user): void
    {
        $user->notifications()->getQuery()->delete();
    }

    /**
     * Clear password resets table after email update.
     */
    protected function clearPasswordResetAfterEmailUpdate(User $user): void
    {
        $oldEmail = $user->getOriginal('email');
        $newEmail = $user->getAttribute('email');

        $user->forceFill(['email' => $oldEmail]);

        $this->clearPasswordReset($user);

        $user->forceFill(['email' => $newEmail]);
    }

    /**
     * Logout other devices.
     */
    protected function logoutOtherDevices(User $user): void
    {
        inject(LogoutOtherDevicesAction::class)->handle($user);
    }
}
