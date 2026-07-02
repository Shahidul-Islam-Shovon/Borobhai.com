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
    Schema::create('messages', function (Blueprint $table) {
        $table->id();

        $table->foreignId('sender_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->foreignId('receiver_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->longText('message')->nullable();

        $table->boolean('is_seen')->default(false);
        $table->timestamp('seen_at')->nullable();

        $table->boolean('is_unsent')->default(false);
        $table->timestamp('unsent_at')->nullable();

        $table->boolean('deleted_by_sender')->default(false);
        $table->boolean('deleted_by_receiver')->default(false);

        $table->timestamps();

        $table->index(['sender_id', 'receiver_id']);
        $table->index('created_at');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
