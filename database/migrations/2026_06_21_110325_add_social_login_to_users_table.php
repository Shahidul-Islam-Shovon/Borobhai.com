<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Social login (Google/Facebook) এর জন্য দরকারি কলাম যোগ।
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // কোন provider দিয়ে এসেছে — google / facebook / null (normal user)
            $table->string('provider')->nullable()->after('password');

            // OAuth provider এর unique id (Google/FB এর নিজস্ব user id)
            $table->string('provider_id')->nullable()->after('provider');

            // social user এর password থাকে না — তাই nullable করা হলো
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['provider', 'provider_id']);
            // password আবার required করা (sqlite এ change লাগে না, mysql এ লাগে)
            $table->string('password')->nullable(false)->change();
        });
    }
};