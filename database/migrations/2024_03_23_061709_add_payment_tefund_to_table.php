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
        // Schema::table('bookings', function (Blueprint $table) {
        //     $table->string('pay_id')->unique()->nullable()->after('location');
        //     $table->string('capture_id')->unique()->nullable()->after('pay_id');
        //     $table->dateTime('payed_on')->nullable()->after('capture_id');
        //     $table->longText('payment')->after('payed_on');
        // });
        // Schema::table('cancelled_bookings', function (Blueprint $table) {
        //     $table->string('refund_amount')->after('cancelled_date');
        //     $table->string('reason_for_refund')->after('refund_amount');
        //     $table->dateTime('refund_on')->nullable()->after('reason_for_refund');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('pay_id');
            $table->dropColumn('payment');
            $table->dropColumn('payed_on');
            $table->dropColumn('capture_id');
        });
        Schema::table('cancelled_bookings', function (Blueprint $table) {
            $table->dropColumn('refund_amount');
            $table->dropColumn('reason_for_refund');
            $table->dropColumn('refund_on');
        });
    }
};
