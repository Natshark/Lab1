<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'username' => 'Alexander',
                'email' => 'modin.ru2@mail.ru',
                'password' => '$2y$12$TpImNvRc6ObLulHV3CKXu.stvq/luMqKRcXfGOBImxO6r.41oROgu',
                'birthday' => '2004-04-29',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
