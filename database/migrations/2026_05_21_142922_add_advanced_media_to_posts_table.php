<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            // যদি আগে থেকে image কলাম থাকে, তবে সেটা ড্রপ করতে পারেন, অথবা রেখেই নতুনগুলো যোগ করতে পারেন
            $table->json('images')->nullable(); // মাল্টিপল ইমেজের জন্য JSON
            $table->string('video')->nullable(); // ভিডিও লিংকের জন্য
            $table->string('bg_color')->nullable(); // কালার প্লেট ক্লাসের জন্য
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['images', 'video', 'bg_color']);
        });
    }
};