<?php

namespace App\Http\Controllers\Auth;

use App\Traits\CaptureIpTrait;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use jeremykenedy\LaravelRoles\Models\Role;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Traits\ActivationTrait;
use App\Traits\CaptchaTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Cookie;
use App\Models\UsersIp;
use App\Helpers\Helper;

class RegisterController extends Controller
{
    use CaptureIpTrait;

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use ActivationTrait;
    use CaptchaTrait;
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/activate';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['captcha'] = $this->captchaCheck();

        if (!config('settings.reCaptchStatus')) {
            $data['captcha'] = true;
        }
        //$data['captcha'] = true;
     
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:45|confirmed',
            'phone_number' => 'required|numeric',
            'g-recaptcha-response'  => '',
            'captcha'               => 'required|min:1',
            //'phone_country_code'    => 'required_with:phone_number',
            //'phone_number'          => 'required_with:phone_country_code|phone',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
       // print_r(\Carbon\Carbon::now());die;  
        // $recaptcha = new \ReCaptcha\ReCaptcha($secret);
        $referred_by = Helper::decryptId(Cookie::get('referral'));

        $role      = Role::where('slug', '=', 'unverified')->first();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'referred_by' => $referred_by,
            'signup_ip_address' => $this->getClientIp(),
            'token' => str_random(64),
            'activated' => !config('settings.activation')
        ]);

        // Save the IP address to usersIP table
         $user_id = new UsersIp;
         $user_id->user_id = $user->id;
         $user_id->ip = $this->getClientIp();
         $user_id->last_online = date('Y-m-d H:i:s');
         $user_id->save();

        // Added Extra data for the users to be inserted into the Authy
         $user->setAuthPhoneInformation(
            $data['phone_country_code'], $data['phone_number']
        );

        try {
            $user->save();
        } catch (Exception $e) {
            app(ExceptionHandler::class)->report($e);
            return response()->json(['error' => ['Unable To Register User']], 422);
        }
        $user->attachRole($role);
        $this->initiateEmailActivation($user);

        return $user;
    }
}
