<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->text('message')->change();  // nullable করছি যাতে media-only message হতে পারে
            $table->string('file_path')->nullable()->after('message');
            $table->string('file_type')->nullable()->after('file_path');  // image, video, file
            $table->unsignedBigInteger('file_size')->nullable()->after('file_type');  // bytes
            $table->boolean('is_deleted')->default(false)->after('file_size');  // soft delete
            $table->timestamp('delivered_at')->nullable()->after('read_at');
            $table->timestamp('seen_at')->nullable()->after('delivered_at');
        });

        // Conversations table - একটা conversation unique থাকবে
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id_1')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_id_2')->constrained('users')->cascadeOnDelete();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id_1', 'user_id_2']);  // ডুপ্লিকেট prevent
            $table->index('last_message_at');
        });

        // Add conversation_id to messages
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('conversation_id')->nullable()->constrained()->cascadeOnDelete()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'file_type', 'file_size', 'is_deleted', 'delivered_at', 'seen_at', 'conversation_id']);
        });
        Schema::dropIfExists('conversations');
    }
};