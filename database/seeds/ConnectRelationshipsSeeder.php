<?php

use Illuminate\Database\Seeder;
use jeremykenedy\LaravelRoles\Models\Permission;
use jeremykenedy\LaravelRoles\Models\Role;

class ConnectRelationshipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Attach Permissions to Roles
         *
         */
        $roleAdmin = Role::where('name', '=', 'Admin')->first();
        Permission::all()->each(function (Permission $permission) use ($roleAdmin) {
            $roleAdmin->attachPermission($permission);
        });
    }

}