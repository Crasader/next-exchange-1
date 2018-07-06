<?php

namespace App\Transformers;

use App\Models\User;
use Creativeorange\Gravatar\Facades\Gravatar;

class UserPrivateProfileTransformer extends UserTransformer
{
    protected $availableIncludes = [
        'projectRoles',
        'roles'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'avatar_url' => Gravatar::get($user->email),
            'accepted_connections_count' => (int) $user->accepted_connections_count,
            'articles_count'        => (int) $user->articles_count,
            'followers_count'       => (int) $user->followers_count,
            'likes_count'           => (int) $user->likes_count,
            'projects_count'        => (int) $user->projects_count,
            'unread_messages_count' => (int) $user->unread_messages_count
        ];
    }

    public function includeRoles(User $user)
    {
        return $this->collection($user->roles, new RoleTransformer());
    }
}
