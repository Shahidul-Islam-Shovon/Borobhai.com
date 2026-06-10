<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ১. শিক্ষা ইতিহাস (SSC, HSC, Hons আলাদা entry)
        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('degree');                 // SSC, HSC, BSc, MSc...
            $table->string('institution');            // প্রতিষ্ঠানের নাম
            $table->string('field')->nullable();      // গ্রুপ/বিষয় (Science, CSE...)
            $table->string('result')->nullable();     // GPA/CGPA/Result
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);  // এখনো চলছে কিনা
            $table->timestamps();
        });

        // ২. চাকরির অভিজ্ঞতা
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company');                // কোম্পানির নাম
            $table->string('designation');            // পদবি
            $table->string('location')->nullable();
            $table->string('employment_type')->nullable();  // Full-time, Part-time, Internship
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);  // এখনো করছে কিনা
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // ৩. সার্টিফিকেশন ও ট্রেনিং
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');                  // সার্টিফিকেট/ট্রেনিং নাম
            $table->string('organization')->nullable(); // প্রদানকারী সংস্থা
            $table->date('issue_date')->nullable();
            $table->string('credential_url')->nullable(); // লিংক (থাকলে)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifications');
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('educations');
    }
};