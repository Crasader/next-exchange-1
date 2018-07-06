<?php

namespace App\Models;
use App\Models\User;
use App\Models\Suggestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SuggestionComment extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function suggestion()
    {
        return $this->belongsTo(Suggestion::class, 'suggestion_id', 'id');
    }
    public static function getComments( ) {

        $data  = DB::table('suggestion_comments')
        ->leftJoin('users', 'suggestion_comments.user_id', '=', 'users.id')
        ->select('suggestion_comments.*', 'users.name')
        ->groupBy('suggestion_comments.id')
        ->get();
        return $data;
    }
}
