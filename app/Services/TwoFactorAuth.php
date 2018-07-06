<?php

namespace App\Services;

use Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable;
use Srmklive\Authy\Contracts\Auth\TwoFactor\Provider;
use Srmklive\Authy\Services\Authy;

class TwoFactorAuth
{
    /**
     * TwoFactor auth provider object.
     *
     * @var Provider[]
     */
    private $providers = [
        'authy' => Authy::class,
        'google' => GoogleAuthenticator::class,
    ];

    /**
     * @var string
     */
    private $currentProvider = 'authy';

    /**
     * Get specific TwoFactor auth provider object to use.
     *
     * @param null|string $name
     * @return Provider
     */
    public function getProvider($name = null)
    {
        $name = $name ?: $this->currentProvider;
        $this->initProvider($name);
        return @$this->providers[$name];
    }

    /**
     * Set specific TwoFactor auth provider to use.
     *
     * @param string $name
     *
     * @return Provider
     */
    public function setProvider($name)
    {
        $this->currentProvider = $name;
        return $this->getProvider();
    }

    /**
     * @param Authenticatable $user
     * @return bool
     */
    public function isEnabled(Authenticatable $user)
    {
        return (bool)$this->getEnabled($user);
    }

    /**
     * @param Authenticatable $user
     * @return Provider
     */
    public function getEnabled(Authenticatable $user)
    {
        foreach ($this->providers as $name => $provider) {
            $provider = $this->getProvider($name);
            if ($provider->isEnabled($user)) {
                return $provider;
            }
        }

        return null;
    }

    /**
     * Determine if the given token is valid for the given user.
     *
     * @param \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @param string $token
     *
     * @return bool
     */
    public function tokenIsValid(Authenticatable $user, $token)
    {
        return $this->isEnabled($user) && $this->getEnabled($user)->tokenIsValid($user, $token);
    }

    /**
     * Delete the given user from the provider.
     *
     * @param \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     *
     * @return bool
     */
    public function delete(Authenticatable $user)
    {
        return $this->isEnabled($user) && $this->getEnabled($user)->delete($user);
    }


    /**
     * Initializes 2FA authentication provider.
     *
     * @param $name
     */
    private function initProvider($name)
    {
        if (!$this->isProviderInitialized($name)) {
            $this->providers[$name] = app($this->providers[$name]);
        }
    }

    /**
     * Determines if 2fa provider initialized.
     *
     * @param $name
     * @return bool
     */
    private function isProviderInitialized($name)
    {
        return $this->providers[$name] instanceof Provider;
    }
}