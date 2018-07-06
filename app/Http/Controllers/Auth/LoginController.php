<?php

namespace App\Http\Controllers\Auth;

use TwoFactorAuth;
use App\Models\User;
use App\Models\UsersIp;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use App\Traits\SyncWalletBalanceTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Logic\Activation\ActivationRepository;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Notifications\AccountDeactivatedNotification;

class LoginController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | Login Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles authenticating users for the application and
     | redirecting them to your home screen. The controller uses a trait
     | to conveniently provide its functionality to your applications.
     |
     */


    /**
     * Throttle login attempts to 3x times over 24 hours
     */
    protected $maxAttempts = 3;
    protected $decayMinutes = 24 * 60;
    protected $lockoutTime = 300;

    protected $redirectTo = '/home';
    protected $redirectAfterLogout = '/';

    private $activationRepository;
    //private $provider;

    use AuthenticatesUsers,
        SyncWalletBalanceTrait;

    /**
     * Create a new controller instance.
     *
     * @param ActivationRepository $repository
     */
    public function __construct(ActivationRepository $repository)
    {
        $this->middleware('guest')->except('logout');
        $this->activationRepository = $repository;
    }

    /**
     * Deactivate user account when
     * too much login attempts
     *
     * @param Request $request
     */
    protected function sendLockoutResponse(Request $request)
    {
        $user = User::where('email', $request->email)
            ->first();

        if ($user->activated) {
            $this->deactivateUserAndSendNotification($user);
        }

        throw ValidationException::withMessages([
            $this->username() => [Lang::get('auth.throttle_deactivated')],
        ])->status(423);
    }

    protected function deactivateUserAndSendNotification(User $user)
    {
        $user->activated = false;
        $user->save();

        $activation = $this->activationRepository->createNewActivationToken($user);

        $user->notify(new AccountDeactivatedNotification($activation->token));
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $rules = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        if (Config::get('settings.reCaptchStatus')) {
            $rules['g-recaptcha-response'] = 'required|re_captcha';
        }

        $this->validate($request, $rules);
    }


    /**
     * Logout, Clear Session, and Return.
     *
     * @return void
     */
    public function logout()
    {
        $user = Auth::user();
        UsersIp::where('id', $user->id)->update(['last_online' => \Carbon::now()]);

        Log::info('User Logged Out. ', [$user]);

        Auth::logout();
        Session::flush();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    public function redirectTo() {
        return $this->redirectTo;
    }

    /*
     * I'm moving this code from AuthController
     * The current routes are using LoginController and RegisterController for Auth
     * */

    /**
     * Send the post-authentication response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, Authenticatable $user)
    {
        if (TwoFactorAuth::isEnabled($user)) {
            return $this->logoutAndRedirectToTokenScreen($request, $user);
        }

        $this->syncUserWallets();

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Generate a redirect response to the two-factor token screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return \Illuminate\Http\Response
     */
    protected function logoutAndRedirectToTokenScreen(Request $request, Authenticatable $user)
    {
        auth()->logout();

        $request->session()->put('twofactor:auth:id', $user->id);

        return redirect(url('auth/token'));
    }

    /**
     * Show two-factor authentication page
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function getToken()
    {
        return session('twofactor:auth:id') ? view('auth.token') : redirect(url('login'));
    } 

    /**
     * Verify the two-factor authentication token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postToken(Request $request)
    {
        $this->validate($request, ['token' => 'required']);
        if (! session('twofactor:auth:id')) {
            return redirect(url('login'));
        }

        /** @var User $user */
        $user = User::findOrFail(
            $request->session()->get('twofactor:auth:id')
        );

        if (TwoFactorAuth::tokenIsValid($user, $request->token)) {
            auth()->login($user);
            return redirect($this->redirectPath());
        } else {
            // set error message and redirect user
            return redirect(url('login'))->withErrors(['2fatoken' => 'Invalid two-factor authentication token provided!']);
        }
    }

    private function syncUserWallets ()
    {
        $start = now()->subSeconds(30);
        /** @var Collection $wallets */
        $wallets = Auth::user()
            ->wallet()
            ->with('address')
            ->where('synced_at', '<=', $start)
            ->orWhereNull('synced_at')
            ->get();

        $wallets->map(function ($wallet) {
           if (!$wallet->address) return;

           static::runSyncWalletBalance(
               $wallet->id,
               $wallet->address->address_address,
               null,
               $wallet->name
           );
        });
    }
}
