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
        $table->text('images')->nullable(); // মাল্টিপল ইমেজের পাথ JSON আকারে রাখার জন্য
        $table->string('video')->nullable(); // ভিডিও ফাইলের পাথ রাখার জন্য
        $table->string('bg_color')->nullable(); // ফেসবুক কালার প্লেটের ক্লাস বা গ্রেডিয়েন্ট
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
