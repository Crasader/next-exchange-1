<?php

namespace App\Http\Controllers;
use App\Models\Suggestion;
use Auth;

class SuggestionVoteController extends Controller
{
    /**
     * Assign new vote for suggestion
     * if user hasn't voted yet.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function voteUp($id)
    {
        /** @var Suggestion $suggestion */
        $suggestion = Suggestion::findOrFail($id);

        $hasVote = $suggestion->votes()->where('user_id', Auth::id())->exists();

        if(Auth::check()) { // first check if there is a logged in user
            if($suggestion->status !== 'pending' && !$hasVote){
                $suggestion->votes()->attach(Auth::id());
            }
            return response()->json();
        }
        else
            redirect('/login');
    }

    /**
     * Deletes vote from suggestion if exists
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function voteDown($id){
        if(Auth::check()) {
            /** @var Suggestion $suggestion */
            $suggestion = Suggestion::findOrFail($id);
            $hasVote = $suggestion->votes()->where('user_id', Auth::id())->exists();

            if ($hasVote) {
                $suggestion->votes()->detach(Auth::id());
            }

            return response()->json();
        }
        else
        redirect('/login');
    }
}
