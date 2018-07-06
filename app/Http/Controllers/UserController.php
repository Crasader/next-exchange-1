<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Firewall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use App\Transformers\UserPrivateProfileTransformer;

class UserController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function getUserSalt() {

        $user = auth()->user();
        $salt = $user->salt;

        if (empty($salt)){
            // Aanmaken salt
            $salt = EncryptionController::generateSalt();
            User::where('id', $user->id)->update(['salt' => $salt]);
            return $salt;
        }
        else {
            return $salt;
        }
    }

    function user_list(Request $request){

        $query = User::query();
        if($request->get('search')){
            $query->where('email','like','%'.$request->search.'%' );
        }

        $data = $query->with('user_ips')->paginate(10);
        foreach ($data as $key => $value) {

            @$ip = $value->user_ips->ip;

            $firewall = Firewall::where('ip_address','=', $ip)->count();

            if($firewall){
                $data[$key]['is_block'] = 1;
            }else{
                $data[$key]['is_block'] = 0;
            }
        };
        $response = [
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem()
            ],
            'data' => $data
        ];
        //event(new Transactionupdate($data));
        return response()->json($response);
    }


    function black_list(Request $request){

        $query = Firewall::query();
        if($request->get('search')){
            $query->where('ip_address','like','%'.$request->search.'%' );
        }
        $data = $query->paginate(10);

        $response = [
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem()
            ],
            'data' => $data
        ];
        return response()->json($response);
    }

    function whitelisted(Request $request)
    {
        if($request->get('type') == 1){
            $fir = \Firewall::blacklist($request->get('ip'), true);
            $response = ['sucess' => 1];
        }else{
            \Firewall::remove($request->get('ip'));
            $response = ['sucess' =>1];
        }
        $response = ['sucess' =>1];
        return response()->json($response);
    }

    function blacklisted(Request $request){
        $fir = \Firewall::blacklist($request->get('ip'), true);
        $response = ['sucess' => $fir];
        return response()->json($response);
    }

    function change_password(Request $request) {

        $validation = Validator::make($request->all(), [
            'old_password'     => 'required',
            'new_password'     => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validation->fails())
        {
            $response['message'] = $validation->errors();
            $response['success'] = 0;
            $response['error']= "";
        }else{

            $data = $request->all();
            $user = User::find(auth()->user()->id);
            if(!Hash::check($data['old_password'], $user->password)){
                $response = ['message' => ['old_password' => ["The specified password does not match the old password"]], 'success' => 0];
                return response()->json($response);
            }else{
                $update['password'] = Hash::make($data['new_password']);
                $update_password = User::where('id','=',auth()->user()->id)->update($update);
                if($update_password){
                    $response = ['message' => 'Password Update Successfully.', 'success' => 1];
                }else{
                    $response = ['message' => 'Someting Want worng.', 'success' => 0];
                }
            }
        }
        return response()->json($response);
    }


    public function currentUser()
    {
        $user_info = User::where('id', '=', auth()->user()->id)->with('roles')->select('id')->first();
        if(isset($user_info->roles[0]) && $user_info->roles[0]->slug == 'admin'){
            $is_admin = 1;
        } else{
            $is_admin = 0;
        }
        $response = ['user_type' => $is_admin];
        return response()->json($response);
    }

    public function getAuthenticatedUser()
    {
        $user = User::withCount([
            'likes',
            'followers',
            'projects',
            'articles',
            'connections',
            'acceptedConnections',
            'pendingConnections',
        ])->first(Auth::id());

        return transformModel($user, new UserPrivateProfileTransformer());
    }
}
