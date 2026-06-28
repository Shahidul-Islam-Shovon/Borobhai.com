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
        Schema::table('bb_notifications', function (Blueprint $table) {
            $table->boolean('seen')->default(false)->after('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('bb_notifications', fn ($t) => $t->dropColumn('seen'));
    }
};
