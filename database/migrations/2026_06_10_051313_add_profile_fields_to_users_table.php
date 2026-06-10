<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'cover_photo'))     $table->string('cover_photo')->nullable();
            if (!Schema::hasColumn('users', 'bio'))             $table->text('bio')->nullable();
            if (!Schema::hasColumn('users', 'phone'))           $table->string('phone')->nullable();
            if (!Schema::hasColumn('users', 'location'))        $table->string('location')->nullable();
            if (!Schema::hasColumn('users', 'department'))      $table->string('department')->nullable();
            if (!Schema::hasColumn('users', 'session'))         $table->string('session')->nullable();
            if (!Schema::hasColumn('users', 'section'))         $table->string('section')->nullable();
            if (!Schema::hasColumn('users', 'semester'))        $table->string('semester')->nullable();
            if (!Schema::hasColumn('users', 'skills'))          $table->json('skills')->nullable();
            if (!Schema::hasColumn('users', 'interests'))       $table->string('interests')->nullable();
            if (!Schema::hasColumn('users', 'linkedin_url'))    $table->string('linkedin_url')->nullable();
            if (!Schema::hasColumn('users', 'github_url'))      $table->string('github_url')->nullable();
            if (!Schema::hasColumn('users', 'facebook_url'))    $table->string('facebook_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach ([
                'cover_photo','bio','phone','location','department','session',
                'section','semester','skills','interests',
                'linkedin_url','github_url','facebook_url'
            ] as $col) {
                if (Schema::hasColumn('users', $col)) $table->dropColumn($col);
            }
        });
    }
};