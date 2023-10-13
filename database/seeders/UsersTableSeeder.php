<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin68@gmail.com',
                'password' => Hash::make('123456'),
                'birthday' => new Carbon('first day of January 2018'),
                'is_teacher' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Teacher',
                'email' => 'teacher@gmail.com',
                'password' => Hash::make('123456'),
                'birthday' => new Carbon('first day of January 1999'),
                'is_teacher' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Teacher',
                'username' => 'Teacher',
                'email' => 'teacher@gmail.com',
                'password' => Hash::make('123456'),
                'is_teacher' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
