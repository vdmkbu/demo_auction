<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // add admin
        $data = [
            'name' => 'admin',
            'email' => 'admin@admin.loc',
            'password' => Hash::make('123456789'),
            'role' => User::ROLE_ADMIN,
            'account' => 10000000,
        ];
        DB::table('users')->insert($data);

        // add user
        // add admin
        $data = [
            'name' => 'user',
            'email' => 'user@user.loc',
            'password' => Hash::make('123456789'),
            'role' => User::ROLE_USER,
            'account' => 10000000,
        ];
        DB::table('users')->insert($data);
    }
}
