<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_courier', 50)->nullable()->after('shipping_address');
            $table->string('tracking_number', 120)->nullable()->after('shipping_courier');
            $table->text('shipping_note')->nullable()->after('tracking_number');
            $table->timestamp('shipped_at')->nullable()->after('shipping_note');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_courier', 'tracking_number', 'shipping_note', 'shipped_at']);
        });
    }
};

