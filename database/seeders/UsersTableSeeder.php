<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->delete();

        // Instructor
        User::create([
            'name'             => 'Ahmed Ali',
            'email'            => 'Ahmed@app.com',
            'password'         => bcrypt('123123'),
            'status'           => 'active',
            'phone'            => '01000000002',
            'user_type'        => 'instructor',
            'license_number'   => 'LIC54321',
            'experience_years' => 5,
            'bio'              => 'User with 5 years of driving experience.',
            'remember_token'   => Str::random(10),
        ]);

        // Learner
        User::create([
            'name'             => 'Ahmed Emam',
            'email'            => 'Ahmedemam@app.com',
            'password'         => bcrypt('123123'),
            'status'           => 'inactive',
            'phone'            => '01000000003',
            'user_type'        => 'learner',
            'license_number'   => null,
            'experience_years' => null,
            'bio'              => 'Inactive user with 3 years of experience.',
            'remember_token'   => Str::random(10),
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
