<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');           // যে student আবেদন করল
            $table->foreignId('job_post_id')->constrained('job_posts')->onDelete('cascade'); // কোন job

            $table->string('applicant_name');           // আবেদনকারীর নাম
            $table->string('applicant_email');          // ইমেইল
            $table->string('phone')->nullable();        // ফোন
            $table->text('cover_note')->nullable();     // কেন উপযুক্ত (ছোট বার্তা)
            $table->string('resume_path')->nullable();  // CV/Resume (PDF)

            $table->string('apply_method')->default('inapp'); // inapp / external
            $table->string('status')->default('pending');     // pending/reviewed/shortlisted/rejected/accepted

            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'job_post_id']); // একই job এ একবারই আবেদন
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};