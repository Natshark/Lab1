<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $entities = ['user', 'role', 'permission'];
        $actions = ['get-list', 'read', 'create', 'update', 'delete', 'restore', 'get-story', 'change'];
        $permissions = [];

        foreach ($entities as $entity)
        {
            foreach ($actions as $action)
            {
                $permissions[] =
                [
                    'name' => $action . "-" . $entity,
                    'description' => $action . "-" . $entity,
                    'cipher' => $action . "-" . $entity,
                    'created_at' => Carbon::now(),
                    'created_by' => 1
                ];
            }
        }

        $permissions[] =
        [
            'name' => 'get-list-logRequest',
            'description' => 'get-list-logRequest',
            'cipher' => 'get-list-logRequest',
            'created_at' => Carbon::now(),
            'created_by' => 1
        ];
        $permissions[] =
        [
            'name' => 'read-logRequest',
            'description' => 'read-logRequest',
            'cipher' => 'read-logRequest',
            'created_at' => Carbon::now(),
            'created_by' => 1
        ];
        $permissions[] =
        [
            'name' => 'delete-logRequest',
            'description' => 'delete-logRequest',
            'cipher' => 'delete-logRequest',
            'created_at' => Carbon::now(),
            'created_by' => 1
        ];


        DB::table('permissions')->insert($permissions);

    }
}
