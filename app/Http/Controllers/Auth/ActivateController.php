<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Logic\Activation\ActivationRepository;
use App\Models\Activation;
use App\Models\User;
use App\Models\Profile;
use App\Traits\ActivationTrait;
use App\Traits\CaptureIpTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use jeremykenedy\LaravelRoles\Models\Role;

class ActivateController extends Controller
{
    use ActivationTrait,
        CaptureIpTrait;

    private static $userHomeRoute = 'home';
    private static $adminHomeRoute = 'home';
    private static $activationView = 'auth.activation';
    private static $activationRoute = 'activation-required';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')
            ->except('activateSilently');
    }

    public function initial()
    {
        $user = Auth::user();
        $lastActivation = Activation::where('user_id', $user->id)->get()->last();
        $currentRoute = Route::currentRouteName();

        $rCheck = $this->activeRedirect($user, $currentRoute);
        if ($rCheck) {
            return $rCheck;
        }

        $data = [
            'email' => $user->email,
            'date'  => $lastActivation->created_at->format('m/d/Y'),
        ];

        return view($this->getActivationView())->with($data);
    }

    public static function activeRedirect($user, $currentRoute)
    {
        if ($user->activated) {
            Log::info('Activated user attempted to visit ' . $currentRoute . '. ', [$user]);

            if ($user->isAdmin()) {
                return redirect()->route(self::getAdminHomeRoute())
                    ->with('status', 'info')
                    ->with('message', trans('auth.alreadyActivated'));
            }

            return redirect()->route(self::getUserHomeRoute())
                ->with('status', 'info')
                ->with('message', trans('auth.alreadyActivated'));
        }

        return false;
    }

    public static function getAdminHomeRoute()
    {
        return self::$adminHomeRoute;
    }

    public static function getUserHomeRoute()
    {
        return self::$userHomeRoute;
    }

    public static function getActivationView()
    {
        return self::$activationView;
    }

    /**
     * @param User $user
     * @param Request $request
     */
    public function clearLoginAttempts(User $user, Request $request)
    {
        /** @var RateLimiter $limiter */
        $limiter = app(RateLimiter::class);
        $key = $user->email.'|'.$request->ip();

        $limiter->clear($key);
    }

    /**
     * Activates token holder without required login
     *
     * @param Request $request
     * @param ActivationRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activateSilently(Request $request, ActivationRepository $repository)
    {
        $token = $request->route()->parameter('token');

        if (!$repository->isTokenValid($token)) {
            return redirect()->route(self::getActivationRoute())
                ->with('status', 'danger')
                ->with('message', trans('auth.invalidToken'));
        }

        /** @var Activation $activation */
        $activation = Activation::where('token', $token)->first();

        $activation->user()->update([
           'activated'  => true
        ]);

        $this->clearLoginAttempts($activation->user, $request);

        return redirect()->route(self::getUserHomeRoute())
            ->with('status', 'success')
            ->with('message', trans('auth.successActivated'));
    }

    public function activationRequired()
    {
        $user = Auth::user();
        $lastActivation = Activation::where('user_id', $user->id)->get()->last();

        $currentRoute = Route::currentRouteName();

        $rCheck = $this->activeRedirect($user, $currentRoute);
        if ($rCheck) {
            return $rCheck;
        }

        if ($user->activated == false) {
            $activationsCount = Activation::where('user_id', $user->id)
                ->where('created_at', '>=', Carbon::now()->subHours(config('settings.timePeriod')))
                ->count();

            if ($activationsCount > config('settings.timePeriod')) {
                Log::info('Exceded max resends in last '.config('settings.timePeriod').' hours. '.$currentRoute.'. ', [$user]);

                $data = [
                    'email' => $user->email,
                    'hours' => config('settings.timePeriod'),
                ];

                return view('auth.exceeded')->with($data);
            }
        }

        Log::info('Registered attempted to navigate while unactivate. '.$currentRoute.'. ', [$user]);

        if (empty($lastActivation)) {
            // TODO: If user is not in database, there is no object, activation needs to send again to that user
            $date_activated = null;
            $this->resend();
        }
        else {
            $date_activated = $lastActivation->created_at->format('m/d/Y');
        }

        $data = [
            'email' => $user->email,
            'date'  => $date_activated, //
        ];

        return view($this->getActivationView())->with($data);
    }

    public function resend()
    {
        $user = Auth::user();
        $lastActivation = Activation::where('user_id', $user->id)->get()->last();
        $currentRoute = Route::currentRouteName();

        if ($user->activated == false) {
            $activationsCount = Activation::where('user_id', $user->id)
                ->where('created_at', '>=', Carbon::now()->subHours(config('settings.timePeriod')))
                ->count();

            if ($activationsCount >= config('settings.maxAttempts')) {
                Log::info('Exceded max resends in last ' . config('settings.timePeriod') . ' hours. ' . $currentRoute . '. ', [$user]);

                $data = [
                    'email' => $user->email,
                    'hours' => config('settings.timePeriod'),
                ];

                return view('auth.exceeded')->with($data);
            }

            $sendEmail = $this->initiateEmailActivation($user);

            Log::info('Activation resent to registered user. ' . $currentRoute . '. ', [$user]);

            return redirect()->route(self::getActivationRoute())
                ->with('status', 'success')
                ->with('message', trans('auth.activationSent'));
        }

        Log::info('Activated user attempte to navigate to ' . $currentRoute . '. ', [$user]);

        return $this->activeRedirect($user, $currentRoute)
            ->with('status', 'info')
            ->with('message', trans('auth.alreadyActivated'));
    }

    public static function getActivationRoute()
    {
        return self::$activationRoute;
    }

    /*User deactivate*/

    public function activate($token)
    {
        $user = Auth::user();
        $currentRoute = Route::currentRouteName();

        $role = Role::where('slug', '=', 'user')->first();
        $profile = new Profile();

        $rCheck = $this->activeRedirect($user, $currentRoute);
        if ($rCheck) {
            return $rCheck;
        }

        $activation = Activation::where('token', $token)->get()
            ->where('user_id', $user->id)
            ->first();

        if (empty($activation)) {
            Log::info('Registered user attempted to activate with an invalid token: '.$currentRoute.'. ', [$user]);

            return redirect()->route(self::getActivationRoute())
                ->with('status', 'danger')
                ->with('message', trans('auth.invalidToken'));
        }

        $user->activated = true;
        $user->detachAllRoles();
        $user->attachRole($role);
        $user->signup_confirmation_ip_address = $this->getClientIp();
        $user->profile()->save($profile);
        $user->save();

        $allActivations = Activation::where('user_id', $user->id)->get();
        foreach ($allActivations as $anActivation) {
            $anActivation->delete();
        }

        Log::info('Registered user successfully activated. '.$currentRoute.'. ', [$user]);

        if ($user->isAdmin()) {
            return redirect()->route(self::getAdminHomeRoute())
                ->with('status', 'success')
                ->with('message', trans('auth.successActivated'));
        }

        return redirect()->route(self::getUserHomeRoute())
            ->with('status', 'success')
            ->with('message', trans('auth.successActivated'));
    }

    public function deactivate($token)
    {
        $user = Auth::user();
        $currentRoute = Route::currentRouteName();

        $activationUser = User::where('remember_token', $token)->get();


        if (empty($activationUser)) {
            Log::info('Registered user attempted to deactivate with an invalid token: ' . $currentRoute . '. ', [$user]);

            return redirect()->route(self::getActivationRoute())
                ->with('status', 'danger')
                ->with('message', trans('auth.invalidToken'));
        }

        $user->activated = 0;
        $user->remember_token = '';
        $user->save();

        $allActivations = Activation::where('user_id', $user->id)->get();
        foreach ($allActivations as $anActivation) {
            $anActivation->delete();
        }

        Log::info('Registered user successfully deactivated. ' . $currentRoute . '. ', [$user]);

        if ($user->isAdmin()) {
            return redirect()->route('activation-required')
                ->with('status', 'success')
                ->with('message', trans('auth.successActivated'));
        }

        return redirect()->route('activation-required')
            ->with('status', 'success')
            ->with('message', trans('auth.successActivated'));
    }

    public function exceeded()
    {
        $user = Auth::user();
        $currentRoute = Route::currentRouteName();
        $timePeriod = config('settings.timePeriod');
        $lastActivation = Activation::where('user_id', $user->id)->get()->last();
        $activationsCount = Activation::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subHours($timePeriod))
            ->count();

        if ($activationsCount >= config('settings.maxAttempts')) {
            Log::info('Locked non-activated user attempted to visit '.$currentRoute.'. ', [$user]);

            $data = [
                'hours'    => config('settings.timePeriod'),
                'email'    => $user->email,
                'lastDate' => $lastActivation->created_at->format('m/d/Y'),
            ];

            return view('auth.exceeded')->with($data);
        }

        return $this->activeRedirect($user, $currentRoute)
            ->with('status', 'info')
            ->with('message', trans('auth.alreadyActivated'));
    }
}