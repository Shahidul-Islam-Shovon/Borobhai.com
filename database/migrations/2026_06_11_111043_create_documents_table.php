<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');                       // thesis/project এর নাম
            $table->string('type')->default('Thesis');     // Thesis / Project / Research / Paper
            $table->text('description')->nullable();       // সংক্ষিপ্ত বিবরণ
            $table->string('topic')->nullable();           // টপিক/সাবজেক্ট (search এর জন্য মুখ্য)
            $table->string('file_path');                   // আপলোড করা ফাইল
            $table->string('file_name');                   // আসল ফাইলের নাম
            $table->string('file_type')->nullable();       // pdf/docx/pptx
            $table->unsignedBigInteger('file_size')->nullable(); // bytes
            $table->string('publication_year')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};