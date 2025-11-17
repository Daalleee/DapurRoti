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
        // Update the status enum to match APK.md requirements: pending, confirmed, cancelled, completed
        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending'");
        
        // Update any existing 'processing' status to 'confirmed' since APK.md shows pending -> confirmed -> completed
        DB::table('orders')
            ->where('status', 'processing')
            ->update(['status' => 'confirmed']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the original enum values including 'processing'
        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
