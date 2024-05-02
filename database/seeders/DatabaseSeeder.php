<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AuthUsersTableSeeder::class,
            // AuthOwnersTableSeeder::class,
            // AuthAdminsTableSeeder::class,
        ]);
    }
}
