<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parking_spots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auth_owner_id');
            $table->foreign('auth_owner_id')->references('id')->on('auth_owners')->onDelete('cascade');

            $table->string('slot_name');
            $table->string('available_time');
            $table->string('photos')->nullable();
            $table->string('google_map');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('available_slots');
            $table->datetime('from_date_time');
            $table->datetime('to_date_time');
            $table->string('nearby_places');
            $table->text('vehicle_types');
            $table->text('vehicle_fees');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_spots');
    }
};
