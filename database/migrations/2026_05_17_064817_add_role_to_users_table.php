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
        Schema::table('users', function (Blueprint $table) {
            // default 'student' হিসেবে থাকবে, বাকিগুলো 'admin' অথবা 'alumni'
            $table->enum('role', ['admin', 'alumni', 'student'])->default('student')->after('email');
            $table->enum('status', ['active', 'pending', 'suspended'])->default('active')->after('role'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status']);
        });
    }
};