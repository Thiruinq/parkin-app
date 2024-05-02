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
        Schema::create('auth_owners', function (Blueprint $table) {
            $table->id('id');
            $table->string('username');
            $table->string('password');
            $table->string('email')->unique();
            // Add other relevant owner details
            $table->timestamps();
        });

        DB::table('auth_owners')->insert([
            'username' => 'owner1',
            'password' => bcrypt('ownerowner'),
            'email' => 'owner1@example.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('auth_owners')->insert([
            'username' => 'owner2',
            'password' => bcrypt('ownerowner'),
            'email' => 'owner2@example.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('auth_owners')->insert([
            'username' => 'owner3',
            'password' => bcrypt('ownerowner'),
            'email' => 'owner3@example.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_owners');
    }
};
