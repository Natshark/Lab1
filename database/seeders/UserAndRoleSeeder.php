<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserAndRoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('users_and_roles')->insert([
            'user_id' => 1,
            'role_id' => 1,
            'created_by' => 1,
            'created_at' => Carbon::now(),
        ]);
    }
}
