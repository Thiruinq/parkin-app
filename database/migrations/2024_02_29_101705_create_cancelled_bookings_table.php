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
        Schema::create('cancelled_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('cancelled_by');
            $table->integer('total_hours');
            $table->dateTime('cancelled_date');
            $table->string('refund_status');
            $table->string('reason_for_cancellation');
            $table->string('refund_amount');
            $table->string('reason_for_refund');
            $table->dateTime('refund_on')->nullable();
            $table->text('comments')->nullable();

            // $table->string('refund_amount')->after('cancelled_date');
            // $table->string('reason_for_refund')->after('refund_amount');
            // $table->dateTime('refund_on')->nullable()->after('reason_for_refund');
            // $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cancelled_bookings');
    }
};
