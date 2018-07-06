<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Redirects user as usual, but if there is need
     * to authenticate over 2FA, logout user
     * and show to respective view
     *
     * @param $response
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function sendResetResponse($response)
    {
        $requiresTwoFactorAuth = session('twofactor:auth:id');

        if ($requiresTwoFactorAuth) {

            $this->guard()->logout();

            return view('auth.token');
        }

        return redirect($this->redirectPath())
            ->with('status', trans($response));
    }
}
