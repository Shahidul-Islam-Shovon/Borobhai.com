<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // একজন ইউজার একটা পোস্টে একবারই লাইক দিতে পারবে
            $table->unique(['user_id', 'post_id']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};