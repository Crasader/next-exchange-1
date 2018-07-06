<?php

namespace App\Http\Controllers;

use App\Models\Ico;
use App\Models\User;
use App\Models\IcoRole;
use App\Transformers\IcoTransformer;
use Illuminate\Support\Facades\Auth;
use App\Transformers\UserTransformer;
use App\Http\Requests\StoreIcoRequest;
use App\Http\Requests\Ico\ListUserIcosRequest;
use Symfony\Component\HttpFoundation\Response;

class IcoController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('ico.projects')->with([
            'projects' => $user->projects()->paginate()
        ]);
    }

    public function getDetails($ico)
    {
        return view('ico.details');
    }

    /**
     * List user project based on access level
     *
     * @param ListUserIcosRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userProjects(ListUserIcosRequest $request)
    {
        /** @var User $user */
        $user = User::findOrFail($request->id);

        return view('ico.projects')->with([
            'projects' => $user->projects()->paginate()
        ]);
    }

    public function create()
    {
        return view('ico.create');
    }

    public function store(StoreIcoRequest $request)
    {

        $ico = new Ico($request->only([
            'name',
            'symbol',
            'total_supply_token',
            'stage',
            'launch_date',
            'initial_price',
            'short_description',
            'full_description'
        ]));

        $ico = tap($ico, function (Ico $ico) {
            $ico->save();

            $role = IcoRole::create(['name' => 'CEO', 'display_name' => 'CEO', 'ico_id' => $ico->id]);
            $role->users()->save(Auth::user());

            $ico->members()->save(Auth::user());
            $ico->roles()->save($role);
        });


        return request()->ajax() ?
            transformModel($ico, new IcoTransformer())->respond(Response::HTTP_CREATED)
            : response()->redirectTo('test');
    }

    /**
     * List currently authenticated user projects
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listProjects()
    {
        $projects = Auth::user()
            ->projects()
            ->orderBy('created_at', 'desc')
            ->paginate();
        return transformModel($projects, new IcoTransformer())->respond();
    }

    /**
     * List project members with their roles
     *
     * @param $id
     * @return \Spatie\Fractal\Fractal
     */
    public function listProjectMembers($id)
    {
        /** @var Ico $project */
        $project = Ico::findOrFail($id);

        $projectRolesIds = $project->roles()->pluck('id');

        $members = $project->members()
            ->with(['projectRoles' => function($builder) use($projectRolesIds) {
                $builder->whereIn('ico_role_id', $projectRolesIds);
            }])
            ->paginate();

        return transformModel($members, new UserTransformer(), [], ['projectRoles']);
    }
}
