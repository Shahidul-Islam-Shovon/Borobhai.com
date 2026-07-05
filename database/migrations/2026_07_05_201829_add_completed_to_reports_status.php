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
        // ✅ enum এ completed যোগ করো
        \DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('pending','warned','dismissed','completed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('pending','warned','dismissed') DEFAULT 'pending'");
    }
};
