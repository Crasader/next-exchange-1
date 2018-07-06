<?php

namespace App\Http\Controllers;

use Response;
use App\Models\User;

class CommunityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(9);
        return view('pages.community', ['users' => $users]);
    }

    /**
     * Every time there is need to display vue
     * part of community, use this action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function handleVue()
    {
        return view('community.index');
    }
}
