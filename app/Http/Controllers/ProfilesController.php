<?php

namespace App\Http\Controllers;

/*
 * This ProfileController and ProfilesController will be merged in future
 */

use App\Models\Profile;
use App\Models\Theme;
use App\Models\User;
use App\Transformers\UserTransformer;
use File;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Image;

class ProfilesController extends Controller
{

    protected $idMultiKey     = '618423'; //int
    protected $seperationKey  = '****';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Determine whether user has private conversation
     * with authenticated user
     *
     * @param User $user
     * @return bool
     */
    private function hasPrivateConversationWithUser(User $user)
    {
        $conversations = $user->conversations()->withCount('participants')->whereHas('participants', function ($has) {
            $has->where('user_id', Auth::id());
        })->get();

        return $conversations->contains(function ($conversation) {
           return $conversation->participants_count == 2;
        });
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function profile_validator(array $data)
    {
        return Validator::make($data, [
            'theme_id'          => '',
            'location'          => '',
            'bio'               => 'max:500',
            'twitter_username'  => 'max:50',
            'github_username'   => 'max:50',
            'avatar'            => '',
            'avatar_status'     => '',
        ]);
    }

    /**
     * Fetch user
     * (You can extract this to repository method)
     *
     * @param $username
     * @return mixed
     */
    public function getUserByUsername($username)
    {
        return User::with('profile')->wherename($username)->firstOrFail();
    }

    public function getUserProfile($id)
    {
        try {
            /** @var User $user */
            $user = User::with([
                'profile',
                'acceptedConnections' => function($builder) {
                    $builder->take(4);
                }
            ])->withCount(['likes', 'followers', 'articles', 'projects', 'acceptedConnections'])
                ->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            abort(404);
        }

        $isFollowed = $user->followers()->where('follower_user_id', Auth::id())->exists();
        $isLiked = $user->likes()->where('liked_user_id', Auth::id())->exists();
        $isConnected = $user->connections()->where('connected_user_id', Auth::id())->exists();

        $user->is_liked = $isLiked;
        $user->is_followed = $isFollowed;
        $user->is_connected = $isConnected;
        $user->has_private_conversation = $this->hasPrivateConversationWithUser($user);;

        return transformModel($user, new UserTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param string $username
     * @return $this
     */
    public function show($username)
    {
        try {
            /** @var User $user */
            $user = User::with([
                'profile',
                'acceptedConnections' => function($builder) {
                    $builder->take(4);
                }
            ])->withCount(['likes', 'followers', 'articles', 'projects'])
                ->findOrFail($username);
        } catch (ModelNotFoundException $exception) {
            abort(404);
        }

        $user->member_since = date('F Y',strtotime($user->created_at)); // Chnage Date format of created_at to show Month and Year

        $isFollowing = $user->followers()->where('follower_user_id', Auth::id())->exists();
        $isLiked = $user->likes()->where('liked_user_id', Auth::id())->exists();

        $articles = $user->articles()
            ->with([
                'author',
                'comments' => function($builder) {
                    return $builder->take(5)->orderBy('created_at', 'desc');
                },
                'comments.author'
            ])
            ->withCount(['comments'])
            ->orderBy('created_at', 'desc')
            ->paginate();

        //$currentTheme = Theme::find($user->profile->theme_id);

        return view('profiles.show2')
            ->with([
                'user'          => $user,
                'isFollowing'   => $isFollowing,
                'isLiked'       => $isLiked,
                'articles' => $articles,
                //'currentTheme' => $currentTheme
            ]);
    }

    public function toggleLike(Request $request, $userId)
    {
        try {
            /** @var User $user */
            $user = User::findOrFail($userId);

            $wantsLike = $user->likes()->where('liked_user_id', Auth::id())->doesntExist();

            if ($wantsLike) {
                $user->likes()->attach(Auth::id());
            } else {
                $user->likes()->detach(Auth::id());
            }

            return $request->ajax() ? response()->json(['liked' => $wantsLike]) : redirect()->back();
        }catch (ModelNotFoundException $exception) {
            return $request->ajax() ? response()->json(['success' => false]) : redirect()->back();
        }
    }

    public function toggleFollow(Request $request, $userId)
    {
        try {
            /** @var User $user */
            $user = User::findOrFail($userId);

            $wantsFollow = $user->followers()->where('follower_user_id', Auth::id())->doesntExist();

            if ($wantsFollow) {
                $user->followers()->attach(Auth::id());
            } else {
                $user->followers()->detach(Auth::id());
            }

            return $request->ajax() ? response()->json(['followed' => $wantsFollow]) : redirect()->back();
        }catch (ModelNotFoundException $exception) {
            return $request->ajax() ? response()->json(['success' => false]) : redirect()->back();
        }
    }

    /**
     * /profiles/username/edit
     *
     * @param $username
     * @return mixed
     */
    public function edit($username)
    {
        try {

            $user = $this->getUserByUsername($username);

        } catch (ModelNotFoundException $exception) {
            return view('pages.status')
                ->with('error', trans('profile.notYourProfile'))
                ->with('error_title', trans('profile.notYourProfileTitle'));
        }

        $themes = Theme::where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        $currentTheme = Theme::find($user->profile->theme_id);

        $data = [
            'user' => $user,
            'themes' => $themes,
            'currentTheme' => $currentTheme
        ];

        return view('profiles.edit')->with($data);

    }

    /**
     * Update a user's profile
     *
     * @param $username
     * @return mixed
     * @throws Laracasts\Validation\FormValidationException
     */
    public function update($username, Request $request)
    {

        $user = $this->getUserByUsername($username);

        $input = Input::only('theme_id', 'location', 'bio', 'twitter_username', 'github_username', 'avatar_status');

        $profile_validator = $this->profile_validator($request->all());

        if ($profile_validator->fails()) {

            $this->throwValidationException(
                $request, $profile_validator
            );

            return redirect('profile/' . $user->name . '/edit')->withErrors($profile_validator)->withInput();
        }

        if ($user->profile == null) {

            $profile = new Profile;
            $profile->fill($input);
            $user->profile()->save($profile);

        } else {

            $user->profile->fill($input)->save();

        }

        $user->updated_ip_address = $this->getClientIp();

        $user->save();

        return redirect('profile/'.$user->name.'/edit')->with('success', trans('profile.updateSuccess'));

    }

    /**
     * Get a validator for an incoming update user request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'name'              => 'required|max:255',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUserAccount(Request $request, $id)
    {

        $user        = User::findOrFail($id);
        $emailCheck  = ($request->input('email') != '') && ($request->input('email') != $user->email);
        $ipAddress = new CaptureIpTrait();

        if ($user->name != $request->input('name')) {
            $usernameRules = [
                'name' => 'required|max:255|unique:users',
            ];
        } else {
            $usernameRules = [
                'name' => 'required|max:255',
            ];
        }
        if ($emailCheck) {
            $emailRules = [
                'email' => 'email|max:255|unique:users',
            ];
        } else {
            $emailRules = [
                'email' => 'email|max:255',
            ];
        }
        $additionalRules = [];

        $rules = array_merge($usernameRules, $emailRules, $additionalRules);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->name         = $request->input('name');

        if ($emailCheck) {
            $user->email = $request->input('email');
        }

        $user->updated_ip_address = $this->getClientIp();

        $user->save();

        return redirect('profile/'.$user->name.'/edit')->with('success', trans('profile.updateAccountSuccess'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUserPassword(Request $request, $id)
    {

        $user        = User::findOrFail($id);
        $ipAddress = new CaptureIpTrait();

        $validator = Validator::make($request->all(),
            [
                'password'              => 'required|min:6|max:20|confirmed',
                'password_confirmation' => 'required|same:password',
            ],
            [
                'password.required'     => trans('auth.passwordRequired'),
                'password.min'          => trans('auth.PasswordMin'),
                'password.max'          => trans('auth.PasswordMax'),
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->input('password') != null) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->updated_ip_address = $this->getClientIp();

        $user->save();

        return redirect('profile/'.$user->name.'/edit')->with('success', trans('profile.updatePWSuccess'));

    }

    /**
     * Upload and Update user avatar
     *
     * @param $file
     * @return mixed
     */
    public function upload() {
        if(Input::hasFile('file')) {

            $currentUser  = \Auth::user();
            $avatar       = Input::file('file');
            $filename     = 'avatar.' . $avatar->getClientOriginalExtension();
            $save_path    = storage_path() . '/users/id/' . $currentUser->id . '/uploads/images/avatar/';
            $path         = $save_path . $filename;
            $public_path  = '/images/profile/' . $currentUser->id . '/avatar/' . $filename;

            // Make the user a folder and set permissions
            File::makeDirectory($save_path, $mode = 0755, true, true);

            // Save the file to the server
            Image::make($avatar)->resize(300, 300)->save($save_path . $filename);

            // Save the public image path
            $currentUser->profile->avatar = $public_path;
            $currentUser->profile->save();
            return response()->json(array('path'=> $path), 200);
        } else {
            return response()->json(false, 200);
        }
    }

    /**
     * Show user avatar
     *
     * @param $id
     * @param $image
     * @return string
     */
    public function userProfileAvatar($id, $image)
    {
        return Image::make(storage_path() . '/users/id/' . $id . '/uploads/images/avatar/' . $image)->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteUserAccount(Request $request, $id)
    {

        $currentUser = \Auth::user();
        $user        = User::findOrFail($id);

        $validator = Validator::make($request->all(),
            [
                'checkConfirmDelete'            => 'required',
            ],
            [
                'checkConfirmDelete.required'   => trans('profile.confirmDeleteRequired'),
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($user->id != $currentUser->id) {
            return redirect('profile/'.$user->name.'/edit')->with('error', trans('profile.errorDeleteNotYour'));
        }

        // Create and encrypt user account restore token
        $sepKey       = $this->getSeperationKey();
        $userIdKey    = $this->getIdMultiKey();
        $restoreKey   = config('settings.restoreKey');
        $encrypter    = config('settings.restoreUserEncType');
        $level1       = $user->id * $userIdKey;
        $level2       = urlencode(Uuid::generate(4) . $sepKey . $level1);
        $level3       = base64_encode($level2);
        $level4       = openssl_encrypt($level3, $encrypter, $restoreKey);
        $level5       = base64_encode($level4);

        // Save Restore Token and Ip Address
        $user->token  = $level5;
        $user->deleted_ip_address = $this->getClientIp();
        $user->save();

        // Send Goodbye email notification
        $this->sendGoodbyEmail($user, $user->token);

        // Soft Delete User
        $user->delete();

        // Clear out the session
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect('/login')->with('success', trans('profile.successUserAccountDeleted'));

    }

    /**
     * Send GoodBye Email Function via Notify
     *
     * @param array $user
     * @param string $token
     * @return void
     */
    public static function sendGoodbyEmail(User $user, $token) {
        $user->notify(new SendGoodbyeEmail($token));
    }

    /**
     * Get User Restore ID Multiplication Key
     *
     * @return string
     */
    public function getIdMultiKey() {
        return $this->idMultiKey;
    }

    /**
     * Get User Restore Seperation Key
     *
     * @return string
     */
    public function getSeperationKey() {
        return $this->seperationKey;
    }

}
