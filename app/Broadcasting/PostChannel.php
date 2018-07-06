<?php

namespace App\Broadcasting;

use App\Models\Post;
use App\Models\User;

class PostChannel
{
    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return array|bool
     */
    public function join(User $user, Post $post)
    {
        return true;
    }
}