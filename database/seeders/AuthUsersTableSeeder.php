<?php

namespace Database\Seeders;

use App\Models\AuthUser;
use Illuminate\Database\Seeder;

class AuthUsersTableSeeder extends Seeder
{
    public function run()
    {
        factory(AuthUser::class, 3)->create();
    }
}
