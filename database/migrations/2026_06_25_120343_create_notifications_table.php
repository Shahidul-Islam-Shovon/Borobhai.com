<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bb_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();   // যে পাবে
            $table->foreignId('actor_id')->constrained('users')->cascadeOnDelete(); // যে করেছে
            $table->string('type'); // friend_request|friend_accept|post_like|post_comment|comment_like|post_share|job_apply|job_status
            $table->string('notifiable_type')->nullable(); // post|job|comment
            $table->unsignedBigInteger('notifiable_id')->nullable();
            $table->string('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            $table->index(['user_id', 'is_read']);
        });

        // Recent searches
        Schema::create('recent_searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('query');
            $table->string('result_type')->nullable(); // user | topic
            $table->unsignedBigInteger('result_id')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recent_searches');
        Schema::dropIfExists('bb_notifications');
    }
};