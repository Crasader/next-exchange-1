<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Suggestion extends Model
{
    protected $table = 'suggestions';
    /**
     * The attributes that are mass assignable.
     *
     *
     */


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected $guarded = [];

    public static function getSuggestions($query,$order,$filter) {

        $data = Suggestion::withCount(['votes', 'comments'])
            ->where('suggestions.status', '!=', 'deleted')
            ->when($query, function ($q) use ($query) {
                return $q->where('title', 'LIKE', '%' . $query . '%');
            })
            ->when($order, function ($q) use ($order) {
                if ($order == 'top')
                    return $q->orderBy('votes_count', 'desc');
                else
                    return $q->orderByRaw('created_at', 'desc');
            }, function ($query) {
                return $query->orderBy('votes_count', 'desc');
            })
            ->when($filter, function ($q) use ($filter) {
                if ($filter == 'all-except-done')
                    return $q->where('status', '!=', 'done');
                else if ($filter !== 'all')
                    return $q->where('status', $filter);
                else
                    return $q;
            })
            ->paginate(20);

        return $data;
    }

    public function comments()
    {
        return $this->hasMany(SuggestionComment::class);
    }

    public function votes()
    {
        return $this->belongsToMany(
            User::class,
            'suggestion_votes',
            'suggestion_id',
            'user_id'
        );
    }

    public function setShortNameAttribute($value)
    {
        $this->attributes['short_name'] = strtolower($value);
        $this->attributes['short_name'] = str_replace(' ', '-', $this->attributes['short_name']);
        $this->attributes['short_name'] =preg_replace('/[^A-Za-z0-9\-]/', '-', $this->attributes['short_name']);
    }
}
