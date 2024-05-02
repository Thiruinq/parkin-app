<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auth_users', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('mobile');
            //0 = user, 1 = owner, 2 = admin
            $table->tinyInteger('role')->default(0);
            // Add other relevant user details
            $table->timestamps();
        });

        // Insert test data for auth_users table
        DB::table('auth_users')->insert([
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'password' => bcrypt('useruser'),
            'mobile' => '1234567890',
            'role' => 0, // 0 for user
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('auth_users')->insert([
            'name' => 'User 2',
            'email' => 'user2@example.com',
            'password' => bcrypt('useruser'),
            'mobile' => '9876543210',
            'role' => 0, // 0 for user
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('auth_users')->insert([
            'name' => 'User 3',
            'email' => 'user3@example.com',
            'password' => bcrypt('useruser'),
            'mobile' => '5555555555',
            'role' => 0, // 0 for user
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_users');
    }
};
