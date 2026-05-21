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
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        // ইউজার বা অথর আইডি (ফরেন কি)
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // পোস্টের মূল লেখা রাখার জন্য কলাম
        $table->text('content'); // আপনার প্রোজেক্টে content বা body যেকোনো একটা দিতে পারেন
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
