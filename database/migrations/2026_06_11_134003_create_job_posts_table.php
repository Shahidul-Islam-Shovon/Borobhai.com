<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // যে alumni পোস্ট করল

            $table->string('title');                       // পদের নাম
            $table->string('company');                     // কোম্পানি
            $table->string('location')->nullable();        // স্থান
            $table->string('job_type')->default('Full-time'); // Full-time/Part-time/Internship/Remote/Contract
            $table->string('experience')->nullable();      // অভিজ্ঞতা (e.g. 1-2 years, Fresher)
            $table->string('salary')->nullable();          // বেতন (e.g. 30k-50k BDT)

            $table->text('description');                   // বিস্তারিত
            $table->text('requirements')->nullable();      // যোগ্যতা
            $table->string('skills')->nullable();          // দক্ষতা (comma separated)

            $table->string('apply_type')->default('link'); // link / email
            $table->string('apply_value')->nullable();     // আবেদনের লিংক বা ইমেইল

            $table->date('deadline')->nullable();          // শেষ তারিখ
            $table->string('category')->nullable();        // ক্যাটাগরি (IT, Marketing...)
            $table->string('status')->default('active');   // active / closed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};