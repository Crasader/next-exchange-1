<?php

namespace App\Services;

use Crypt;
use Exception;
use Google2FA;
use Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable;
use Srmklive\Authy\Contracts\Auth\TwoFactor\Provider as BaseProvider;

class GoogleAuthenticator implements BaseProvider
{
    /**
     * Determine if the given user has two-factor authentication enabled.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @return bool
     */
    public function isEnabled(Authenticatable $user)
    {
        return isset($user->getTwoFactorAuthProviderOptions()['gsecret']);
    }

    /**
     * Register the given user with the provider.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @param boolean $sms
     * @return void
     * @throws \Exception
     */
    public function register(Authenticatable $user, $sms = false)
    {
        $options = $user->getTwoFactorAuthProviderOptions();

        if (Google2FA::verifyKey($options['gsecret'], $options['otp'])) {
            $user->setTwoFactorAuthProviderOptions([
                'gsecret' => Crypt::encryptString($options['gsecret'])
            ]);
        } else {
            throw new Exception('Wrong OTP.');
        }
    }

    /**
     * Determine if the given token is valid for the given user.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @param  string $token
     * @return bool
     */
    public function tokenIsValid(Authenticatable $user, $token)
    {
        try {
            return Google2FA::verifyKey(Crypt::decryptString($user->getTwoFactorAuthProviderOptions()['gsecret']), $token);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete the given user from the provider.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @return bool
     */
    public function delete(Authenticatable $user)
    {
        $user->setTwoFactorAuthProviderOptions([]);
    }
}