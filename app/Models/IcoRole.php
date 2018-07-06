<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IcoRole extends Model
{
    protected $table = 'icos_roles';

    protected $fillable = [
        'name',
        'ico_id',
        'display_name'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function ico() : BelongsTo
    {
        return $this->belongsTo(Ico::class);
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_has_icos_roles', 'ico_role_id', 'user_id');
    }
}
