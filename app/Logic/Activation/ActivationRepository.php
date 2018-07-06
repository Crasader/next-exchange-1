<?php

namespace App\Logic\Activation;

use App\Models\Activation;
use App\Models\User;
use App\Notifications\SendActivationEmail;
use App\Traits\CaptureIpTrait;
use Carbon\Carbon;

class ActivationRepository
{
    use CaptureIpTrait;

    public function createTokenAndSendEmail(User $user)
    {
        $activations = Activation::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subHours(config('settings.timePeriod')))
            ->count();

        if ($activations >= config('settings.maxAttempts')) {
            return true;
        }

        //if user changed activated email to new one
        if ($user->activated) {
            $user->update([
                'activated' => false,
            ]);
        }

        // Create new Activation record for this user
        $activation = self::createNewActivationToken($user);

        // Send activation email notification
        self::sendNewActivationEmail($user, $activation->token);
    }

    public function createNewActivationToken(User $user)
    {
        $activation = new Activation();
        $activation->user_id = $user->id;
        $activation->token = sha1($this->getTokenSource($user));
        $activation->ip_address = $this->getClientIp();
        $activation->save();

        return $activation;
    }

    public function sendNewActivationEmail(User $user, $token)
    {
        $user->notify(new SendActivationEmail($token));
    }
    public function deleteExpiredActivations()
    {
        Activation::where('created_at', '<=', Carbon::now()->subHours(72))->delete();
    }

    public function isTokenValid($token) : bool
    {
        return Activation::where('token', $token)->exists();
    }

    /**
     * Compose source to generate activation token.
     * The point is to avoid collisions and prevent
     * decoding activation token
     *
     * @param $user
     * @return string
     */
    private function getTokenSource($user) : string
    {
        return sprintf(
            '%s%s_salt_%s',
            $user->email,
            str_random(),
            time()
        );
    }

}