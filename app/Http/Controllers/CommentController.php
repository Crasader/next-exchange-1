<?php

namespace App\Http\Controllers;
use App\Models\Suggestion;
use App\Models\SuggestionComment;
use Auth;

class CommentController extends Controller
{
    public function store($id)
    {          
        $suggestion = Suggestion::find($id);
        if($suggestion->status !== 'pending'){
            if(Auth::check()) { // first check if there is a logged in user
                $user_id = Auth::user()->id; // get user id
                SuggestionComment::create([
                    'body' => request('formText'),
                    'user_id'=> $user_id,
                    'suggestion_id'=> $suggestion->id
                ]);
            }else{
                redirect('/login');
            }
        }
        return redirect()->action(
            'SuggestionController@show', ['title' => $suggestion->short_name]
        );
    }
}
