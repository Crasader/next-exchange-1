<?php

namespace App\Transformers;

use jeremykenedy\LaravelRoles\Models\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Role $role)
    {
        return [
            'name' => $role->name,
            'slug' => $role->slug
        ];
    }
}
