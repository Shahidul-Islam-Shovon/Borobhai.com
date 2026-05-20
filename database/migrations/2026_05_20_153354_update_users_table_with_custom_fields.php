<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // একসাথে সব কলাম অ্যাড করে দিচ্ছি যাতে কোনো এরর না আসে
            $table->string('role')->default('student')->after('email');
            $table->boolean('is_super_admin')->default(false)->after('role');
            $table->string('status')->default('active')->after('is_super_admin');
            $table->timestamp('suspended_until')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_super_admin', 'status', 'suspended_until']);
        });
    }
};