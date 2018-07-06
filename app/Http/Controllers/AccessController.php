<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Access;

class AccessController extends Controller
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

    public function index(Request $request)
    {
        $query = Access::query();
        if ($request->get('search')) {
            $searchParam = $request->get('search');
            $query->whereHas('user', function($q) use ($searchParam){
                $q->where('name', 'like', '%'.$searchParam.'%');
            });
        }
        $data = $query->with('user')->paginate(10);
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

    public function access_status(Request $request)
    {
        $type = $request->get('type');
        $id = $request->get('id');
        if($type==1){
            Access::active($id);
        }else{
            Access::inActive($id);
        }
        $response = ['success' =>1];
        return response()->json($response);
    }


}
