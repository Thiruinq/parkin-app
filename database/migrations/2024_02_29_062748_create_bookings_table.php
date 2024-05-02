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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auth_user_id');
            // $table->unsignedBigInteger('user_id')->default(1);
            $table->foreign('auth_user_id')->references('id')->on('auth_users')->onDelete('cascade');

            $table->unsignedBigInteger('parking_spot_id');
            $table->foreign('parking_spot_id')->references('id')->on('parking_spots')->onDelete('cascade');

            $table->dateTime('from_datetime');
            $table->dateTime('to_datetime');
            $table->string('vehicle_name');
            $table->string('vehicle_number');
            $table->string('slot');
            $table->decimal('amount_paid', 10, 2);
            $table->dateTime('booked_on');
            $table->integer('total_hours');
            $table->string('status');
            $table->string('location');
            $table->string('pay_id')->unique()->nullable();
            $table->string('capture_id')->unique()->nullable();
            $table->dateTime('payed_on')->nullable();
            $table->longText('payment')->nullable();
            // $table->string('pay_id')->unique()->nullable()->after('location');
            // $table->string('capture_id')->unique()->nullable()->after('pay_id');
            // $table->dateTime('payed_on')->nullable()->after('capture_id');
            // $table->longText('payment')->after('payed_on');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
