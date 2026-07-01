<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_application_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained()->cascadeOnDelete();
            $table->string('status');            // pending / reviewed / shortlisted / accepted / rejected
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();

            $table->index(['job_application_id', 'changed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_application_status_logs');
    }
};