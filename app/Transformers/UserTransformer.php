<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;
use Creativeorange\Gravatar\Facades\Gravatar;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'projectRoles',
        'connections',
        'acceptedConnections',
        'pendingConnections'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'                    => $user->id,
            'name'                  => $user->name,
            'avatar_url'            => Gravatar::get($user->email),
            'accepted_connections_count' => (int) $user->accepted_connections_count,
            'articles_count'        => (int) $user->articles_count,
            'followers_count'       => (int) $user->followers_count,
            'likes_count'           => (int) $user->likes_count,
            'projects_count'        => (int) $user->projects_count,
            'is_liked'              => (bool) $user->is_liked,
            'is_followed'           => (bool) $user->is_followed,
            'is_connected'          => (bool) $user->is_connected,
            'has_private_conversation'      => (bool) $user->has_private_conversation
        ];
    }

    public function includeProjectRoles(User $user)
    {
        return $this->collection($user->projectRoles, new IcoRoleTransformer());
    }

    public function includeConnections(User $user)
    {
        return $this->collection($user->connections, new UserTransformer());
    }

    public function includeAcceptedConnections(User $user)
    {
        return $this->collection($user->acceptedConnections, new UserTransformer());
    }

    public function includePendingConnections(User $user)
    {
        return $this->collection($user->pendingConnections, new UserTransformer());
    }
}
