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
        Schema::create('auth_admins', function (Blueprint $table) {
            $table->id('id');
            $table->string('username');
            $table->string('password');
            $table->string('email')->unique();
            // Add other relevant admin details
            $table->timestamps();
        });

        DB::table('auth_admins')->insert([
            'username' => 'admin1',
            'password' => bcrypt('adminadmin'),
            'email' => 'admin1@example.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('auth_admins')->insert([
            'username' => 'admin2',
            'password' => bcrypt('adminadmin'),
            'email' => 'admin2@example.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('auth_admins')->insert([
            'username' => 'admin3',
            'password' => bcrypt('adminadmin'),
            'email' => 'admin3@example.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_admins');
    }
};
