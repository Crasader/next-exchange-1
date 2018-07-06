<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Disable2faAuth;
use App\Models\User;
use App\Services\GoogleAuthenticator;
use Auth;
use Carbon\Carbon;
use Exception;
use Google2FA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Mail;
use Srmklive\Authy\Services\Authy as AuthyProvider;
use Srmklive\FlashAlert\Facades\FlashAlert;
use TwoFactorAuth;


class TwoFactorController extends Controller
{
    /**
     * Show two-factor authentication page.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function showTokenForm()
    {
        return session('twofactor:auth:id') ? view('auth.twofactor.token') : redirect(url('login'));
    }

    /**
     * Enable two-factor authentication.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enableTwoFactorAuth(Request $request, $provider)
    {
        $user = Auth::user();

        if ($provider = TwoFactorAuth::getProvider($provider)) {
            if ($provider instanceof AuthyProvider) {
                $input = $request->all();

                if (isset($input['phone_number'])) {
                    $input['authy-cellphone'] = preg_replace('/[^0-9]/', '', $input['authy-cellphone']);
                }

                $validator = \Validator::make($input, [
                    'country-code' => 'required|numeric|integer',
                    'authy-cellphone' => 'required|numeric',
                ]);

                if ($validator->fails()) {
                    FlashAlert::error('Error', $validator->errors()->getMessageBag());
                    return redirect(url('home'))->withErrors($validator->errors());
                }

                $user->setAuthPhoneInformation(
                    $input['country-code'], $input['authy-cellphone']
                );
            } elseif ($provider instanceof GoogleAuthenticator) {
                if ($request->session()->has('twofactor:auth:gsecret') && $request->has('otp')) {
                    $user->setTwoFactorAuthProviderOptions([
                        'gsecret' => $request->session()->get('twofactor:auth:gsecret'),
                        'otp' => $request->get('otp'),
                    ]);
                }
            }

            try {
                $provider->register($user, !empty($request->get('send_sms')) ? true : false);
                $user->save();
            } catch (Exception $e) {
                report($e);

                FlashAlert::error('Error', 'The provided information is invalid.');
            }

            FlashAlert::success('Success', 'Two-factor authentication has been enabled!');
        } else {
            FlashAlert::error('Error', 'Can not setup two-factor authentication.');
        }

        return redirect(url('home'));
    }

    /**
     * Disable two-factor authentication.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disableTwoFactorAuth(Request $request)
    {
        $user = Auth::user();

        try {
            TwoFactorAuth::delete($user);

            $user->save();
        } catch (Exception $e) {
            report($e);

            FlashAlert::error('Error', 'Unable to disable two-factor authentication.');
        }


        FlashAlert::success('Success', 'Two-factor authentication has been disabled!');

        return redirect(url('home'));
    }

    /**
     * Lead user through two-factor setup master.
     *
     * @param Request $request
     * @param string $step
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function setupTwoFactorAuth(Request $request, $step = '0-select-provider')
    {
        $user = Auth::user();

        if (!TwoFactorAuth::isEnabled($user)) {

            $data = [];

            if ($step === '1-ga-secret-key') {
                $data['gsecret'] = Google2FA::generateSecretKey();

                $request->session()->put('twofactor:auth:gsecret', $data['gsecret']);
                $data['qrUrl'] = Google2FA::getQRCodeInline(
                    'NEXT.exchange',
                    $user->email,
                    $data['gsecret']
                );
            }

            return view("auth.two-factor.step-$step", $data);
        } else {
            return redirect(url('home'));
        }
    }

    /**
     * @param Request $request
     */
    public function sendResetEmail(Request $request)
    {
        $user = User::find($request->session()->get('twofactor:auth:id'));
        Mail::to($user->email)->send(new Disable2faAuth($user,
            encrypt([
                'userId' => $user->id,
                'createdAt' => Carbon::now()->timestamp
            ])
        ));

        return Response::json([
            'ttl' => config('twofactor.reset.ttl')
        ]);
    }


    /**
     * @param Request $request
     * @param $resetToken
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reset(Request $request, $resetToken)
    {
        /** @var array $payload */
        $payload = decrypt($resetToken);
        $ttl = config('twofactor.reset.ttl');

        if(Carbon::createFromTimestamp($payload['createdAt'])->modify("+ {$ttl}")->greaterThan(Carbon::now())) {
            $user = User::find($payload['userId']);
            if(TwoFactorAuth::isEnabled($user)) {
                TwoFactorAuth::delete($user);
                $user->save();
            }
            return view('auth.two-factor.reset-succeed');
        } else {
            return view('auth.two-factor.reset-outdated');
        }
    }
}