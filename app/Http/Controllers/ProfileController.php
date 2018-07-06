<?php

namespace App\Http\Controllers;

/*
 * This ProfileController and ProfilesController will be merged in future
 */

use App\Models\User;
use App\Models\Profile;
use App\Models\IcoWhitelist;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Logic\Image\ImageRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ProfileFormRequest;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\Response;


class ProfileController extends Controller
{

    protected $image;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->image = $imageRepository;
    }

    public function update_profile(Request $request)
    {
    }

    public function postUpload(Request $request)
    {
        return $this->image->upload($request->all());
    }

    public function deleteUpload(Request $request)
    {
        if (!$request->id) {
            return 0;
        }

        return $this->image->delete($request->id);
    }

    public function index()
    {
        $user = User::with([])
            ->withCount(['followers', 'acceptedConnections', 'likes', 'projects', 'articles'])
            ->findOrFail(Auth::id());

        return view('dashboard.profile', [
            'user' => $user
        ]);
    }

    public function update()
    {
        $user = Auth::user();
        $data = $user->profile ? $user->profile : $user;

        return view('dashboard.profile_update', ['user' => $data]);
    }

    public function store(ProfileFormRequest $request)
    {
        $userDetails = Auth::user()->profile;

        if ($userDetails) {
            $userDetails->fill($request->all());
            $userDetails->slug = str_slug($request->fullname);
            $userDetails->save();
        } else {
            $request->merge(['user_id' => Auth::id()]);
            Profile::create($request->all());
            $request->session()->flash('msg', 'Profile Update Successfully');
        }

        // Todo: put email notification here
        return response()->json(['status' => '200', 'success' => true, 'message' => trans('messages.noty_category_deleted')]);

    }

    public function getDetails()
    {
        $data = null;

        $profile = Auth::user()->profile;

        if ($profile) {
            $data = $profile;
            $success = 1;
        } else {
            //$data = User::where('id', Auth::id())->get();
            $success = 0;
        }
        return response()->json(['data' => $data, 'success' => $success]);
    }

    public function idProof()
    {
        return view('dashboard.idproof', [
            'user' => Auth::user()->profile
        ]);
    }

    public function icoWhitelist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'eth' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => $validator->messages(), 'success' => 0]);
        } else {
            IcoWhitelist::insert([
                'eth' => $request->eth,
                'user_id' => Auth::id(),
                'ico_id' => $request->ico_id,
                'created_at' => \Carbon\Carbon::now()
            ]);
            return response()->json(['success' => 1, 'message' => "Ethereum address is whitelisted."]);
        }
    }

    public function profileFiles(Request $request)
    {
        $this->validate($request, [
            'address_proof' => 'mimes:pdf,png,jpeg,jpg|max:2048',
            'id_proof' => 'mimes:pdf,png,jpeg,jpg|max:2048'
        ],[
            'address_proof.mimes' => 'Address Proof image must be of type .pdf, .png, .jpeg, .jpg',
            'address_proof.max' => 'Address Proof image can be maximum 2 MB',
            'id_proof.mimes' => 'ID Proof must be of type .pdf, .png, .jpeg, .jpg',
            'id_proof.max' => 'ID Proof image can be maximum 2 MB',
        ]);

        if ($request->file('id_proof') != '' || $request->file('address_proof') != '') {

            $userInfo = Profile::whereUserId(Auth::id())->select('address_proof', 'id_proof')->first();
            $insertData['address_proof'] = isset($userInfo->address_proof) ? $userInfo->address_proof : null;
            $insertData['id_proof'] = isset($userInfo->id_proof) ? $userInfo->id_proof : null;
            $insertData['updated_at'] = \Carbon\Carbon::now();

            if ($request->file('id_proof') && $request->file('id_proof')->isValid()) {

                $image = $request->file('id_proof');
                $extension = $image->getClientOriginalExtension(); // getting image extension;
                $filename = 'id_proof_' . Auth::id() . '_' . date("YmdHis") . rand(1111, 9999) . '.' . $extension;

                $storage_path = 'id_proof/';

                Storage::disk('local')->put($storage_path.$filename.'.aes', Crypt::encrypt(file_get_contents($image->getRealPath())));

                //$file = Storage::get($storage_path.$filename.'.aes');
                //Storage::put($storage_path.$filename,Crypt::decrypt($file));

                $insertData['id_proof'] = $filename;

            }

            if ($request->file('address_proof') && $request->file('address_proof')->isValid()) {

                $image = $request->file('address_proof');
                $extension = $image->getClientOriginalExtension(); // getting image extension;
                $filename = 'address_proof_' . Auth::id() . '_' . date("YmdHis") . rand(1111, 9999) . '.' . $extension;

                $storage_path = 'address_proof/';

                Storage::disk('local')->put($storage_path.$filename.'.aes', Crypt::encrypt(file_get_contents($image->getRealPath())));

                //$file = Storage::get($storage_path.$filename.'.aes');
                //Storage::put($storage_path.$filename,Crypt::decrypt($file));

                $insertData['address_proof'] = $filename;
            }

            if ($userInfo) {
                $check_upload = Profile::whereUserId(Auth::id())->update($insertData); // update into user detail
            } else {
                $insertData['created_at'] = \Carbon\Carbon::now();
                $insertData['user_id'] = Auth::id();
                $check_upload = Profile::insert($insertData); // insert into user detail
            }

            $request->session()->flash('success_msg', 'Profile Update Successfully.');

            return redirect(route('id-proof'));

        }
    }

    /**
     * Sends a connection request to target user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendConnectionRequest(Request $request)
    {
        /** @var User $user */
        $user = User::findOrFail($request->id);

        $requestExists = $user->connections()->where('connected_user_id', Auth::id())->exists();

        if (!$requestExists) {
            $user->connections()->save(Auth::user());
        }

        return transformModel($user, new UserTransformer())
            ->respond(Response::HTTP_ACCEPTED);
    }

    /**
     * Updates connection request. Set accept status
     * otherwise deletes connection request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateConnectionRequest(Request $request)
    {
        $this->validate($request, [
           'status' => 'boolean'
        ]);

        $user = User::findOrFail($request->id);
        if ($request->status) {
            Auth::user()->connections()
                ->updateExistingPivot($user->id, ['status' => 'accepted']);
        } else {
            Auth::user()->connections()
                ->detach([$user->id]);
        }

        return transformModel($user, new UserTransformer())
            ->respond(Response::HTTP_ACCEPTED);
    }

    /**
     * Fetches users connections depending on status
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConnections(Request $request)
    {
        $this->validate($request, [
           'status' => 'required|in:pending,accepted,all'
        ]);

        $relations = [
            'pending'   => 'pendingConnections',
            'accepted'  => 'acceptedConnections',
            'all'       => 'connections'
        ];
        $relation = $relations[$request->status];

        $connections = Auth::user()->{$relation}()->paginate();

        return transformModel($connections, new UserTransformer())
            ->respond();
    }
}
