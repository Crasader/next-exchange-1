<?php

namespace App\Http\Controllers;
use App\Models\Suggestion;
use App\Models\SuggestionComment;
use Auth;
use Storage;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index(Request $request)
    {
        $current_user = Auth::user();
        $query = $request->q;
        $order = $request->order;
        $filter = $request->filter;
                
        $suggestions = Suggestion::getSuggestions($query,$order,$filter);
        return view('suggestions.index', compact('suggestions', 'count', 'current_user'));
    }
    
    public function add()
    {
        return view('suggestions.add');
    }

    public function store(Request $request)
    {
        if(Auth::check()) { // first check if there is a logged in user
            $user_id = Auth::user()->id; // get user id

            $this->validate(request(), [
                'title' => 'required|min:5|max:100',
                'description' => 'required',
                'symbol' => 'required|max:5|unique:suggestions',
                'file' => 'image|mimes:jpg,jpeg,png,gif,svg|max:250' // 250kb
            ]);

            if (request()->hasFile('file')) {
                $encrypt = new EncryptionController();
                $path = $encrypt->secureImageUpload($request, 'suggestions');
            } else {
                $path = null;
            }

            Suggestion::create([
                'title' => request('title'),
                'short_name' => request('title'),
                'image' => $path,
                'description' => request('description'),
                'user_id' => $user_id,
                'symbol' => request('symbol'),
                'status' => 'pending'
            ]);
            return redirect()->action('SuggestionController@index');
        }
       else 
       redirect('/login');
    }

    public function show($title, Request $request)
    {
        $suggestion = Suggestion::query()->where('short_name', $title)
            ->withCount(['votes', 'comments'])
            ->with(['comments' => function ($builder) {
                $builder->latest();
            }])
            ->firstOrFail();

        if (Auth::guest()) {
            $suggestion->votes_count = 0;
        }

        if ($suggestion->status !== 'pending' || Auth::user()->hasRole('admin')) {
            return view('suggestions.detail', [
                'suggestion' => $suggestion
            ]);
        } else {
            return redirect()->action('SuggestionController@index');
        }
    }

    public function reject($id)
    {
        if (Auth::user()->hasRole('admin')) {
            $suggestion = Suggestion::find($id);
            $suggestion->delete();
            return response()->json();
        } else {
            return response()->json([], 401);
        }
    }
    public function setStatus($id,Request $request)
    {
        if (Auth::user()->hasRole('admin')) {
            Suggestion::find($id)
                ->update(['status' => $request->status]);
            return response()->json();
        } else {
            return response()->json([], 401);
        }
    }
}