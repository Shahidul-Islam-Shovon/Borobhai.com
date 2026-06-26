<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->string('reportable_type'); // 'post' | 'user'
            $table->unsignedBigInteger('reportable_id');
            $table->string('reason'); // spam, harassment, fake, inappropriate, other
            $table->text('details')->nullable();
            $table->string('status')->default('pending'); // pending | reviewed | dismissed
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index(['reportable_type', 'reportable_id']);
        });

        // Not Interested (suggested contact এ)
        Schema::create('not_interested_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ignored_user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'ignored_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('not_interested_users');
        Schema::dropIfExists('reports');
    }
};