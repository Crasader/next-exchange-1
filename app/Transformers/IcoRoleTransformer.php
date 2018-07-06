<?php

namespace App\Transformers;

use App\Models\IcoRole;
use League\Fractal\TransformerAbstract;

class IcoRoleTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'users',
        'ico'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(IcoRole $role)
    {
        return [
            'id'    => $role->id,
            'name'  => $role->name,
            'display_name'  => $role->display_name
        ];
    }

    public function includeUsers(IcoRole $role)
    {
        return $this->collection($role->users, new UserTransformer());
    }

    public function includeIco(IcoRole $role)
    {
        return $this->item($role->ico, new IcoTransformer());
    }
}
